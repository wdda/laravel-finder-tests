<?php
namespace WDDA\LaravelFinderTests;

use Symfony\Component\Finder\Finder;
use Adagio\ClassFinder\ClassFinder;

class FinderTests
{
    protected $config;
    protected $directory;

    public function __construct($config = false)
    {
        $this->config = $config;

        if (!$this->config) {
            $this->config = $this->getConfig();
        }
    }

    public function getDefaultConfig()
    {
        return include 'config.php';
    }

    public function getCustomConfig()
    {
        if (app('config')->has('finder-tests')) {
            return config('finder-tests');
        }

        return false;
    }

    public function getConfig()
    {
        $customConfig = $this->getCustomConfig();

        if ($customConfig) {
            return $customConfig;
        } else {
            $this->config = include 'config.php';
        }

        if (empty($this->config['directory'])) {
            throw new \Exception("Not directory in config finder-tests.php");
        }

        if (empty($this->config['directory'][0])) {
            throw new \Exception("Not directory in config finder-tests.php");
        }

        if (empty($this->config['directory'][0]['classes'])) {
            throw new \Exception("Not classes in config finder-tests.php");
        }

        if (empty($this->config['directory'][0]['classes']['dir'])) {
            throw new \Exception("Not dir for classes in config finder-tests.php");
        }

        if (empty($this->config['directory'][0]['tests'])) {
            throw new \Exception("Not tests in config finder-tests.php");
        }

        if (empty($this->config['directory'][0]['tests']['dir'])) {
            throw new \Exception("Not dir for tests in config finder-tests.php");
        }

        return $this->config;
    }

    public function getFilesFromDirectory($directory, $type)
    {
        if (!empty($directory['rootPath'])) {
            $path = $directory['rootPath'];
        } else {
            $path = app_path();
        }

        $dirFiles = $path . '/' . $directory[$type]['dir'];
        $files = [];

        if (is_dir($dirFiles)) {
            $filesFinder = Finder::create()
                ->files()
                ->in($dirFiles)
                ->name('*.php');

            if (!empty($directory[$type]['dirExclude'])) {
                $filesFinder->notPath($directory[$type]['dirExclude']);
            }

            if (!empty($directory[$type]['exclude'])) {
                foreach ($directory[$type]['exclude'] as $name) {
                    $filesFinder->notName($name);
                }
            }

            if ($filesFinder->count()) {
                foreach ($filesFinder as $file) {
                    $files[] = $file;
                }
            }
        }

        return $files;
    }

    public function getFiles($type)
    {
        $directory = $this->config['directory'];
        $files = [];

        foreach ($directory as $setting) {
            $methodsExclude = false;
            if (!empty($setting[$type]['methodsExclude'])) {
                $methodsExclude = $setting[$type]['methodsExclude'];
            }

            $classesExclude = false;
            if (!empty($setting[$type]['classesExclude'])) {
                $classesExclude = $setting[$type]['classesExclude'];
            }

            $files[] = [
                'dir' => $setting[$type]['dir'],
                'methodsExclude' => $methodsExclude,
                'classesExclude' => $classesExclude,
                'files' => $this->getFilesFromDirectory($setting, $type)
            ];
        }

        return $files;
    }

    public function getClass($filePath)
    {
        $classes = (new ClassFinder)->find(file_get_contents($filePath));

        if (count($classes)) {
            return $classes[0];
        }

        return false;
    }

    public function getMethods($class, $methodsExclude, $path)
    {
        $methods = [];
        $reflectionClass = new \ReflectionClass($class);

        foreach ($reflectionClass->getMethods() as $method) {
            if ($method->getFileName() == $path) {
                $exclude = false;

                if ($methodsExclude) {
                    foreach ($methodsExclude as $methodExclude) {
                        if ($methodExclude == $method->name) {
                            $exclude = true;
                            continue;
                        }
                    }
                }

                if (!$exclude) {
                    $methods[] = $method;
                }
            }
        }

        return $methods;
    }

    public function findClasses($type)
    {
        $dirsFiles = $this->getFiles($type);
        $classes = [];

        foreach ($dirsFiles as $dirFiles) {
            foreach ($dirFiles['files'] as $file) {
                $path = $file->getRealPath();
                $class = $this->getClass($path);

                if ($class) {
                    $exclude = false;
                    if ($dirFiles['classesExclude']) {
                        foreach ($dirFiles['classesExclude'] as $classExclude) {
                            if ($classExclude == $class) {
                                $exclude = true;
                                continue;
                            }
                        }
                    }

                    if (!$exclude) {
                        $classes[] = [
                            'name' => $class,
                            'dir' => $dirFiles['dir'],
                            'fileData' => $file,
                            'fileName' => basename($path, '.php'),
                            'uses' => $this->getUses($path),
                            'methods' => $this->getMethods($class, $dirFiles['methodsExclude'], $path)
                        ];
                    }
                }
            }
        }

        return $classes;
    }

    public function getUses($path)
    {
        $fileData = file_get_contents($path);
        preg_match_all("#use\s+([^;\s]*)#smi", $fileData, $matches);
        return $matches[1];
    }

    public function finder()
    {
        $classes = $this->findClasses('classes');
        $tests = $this->findClasses('tests');

        return ['classes' => collect($classes), 'tests' => collect($tests)];
    }

    public function findDiff()
    {
        $classesAndTests = $this->finder();

        $diff = [
            'minus' => [
                'classes' => [],
                'methods' => [],
            ],
            'plus' => [
                'classes' => [],
                'methods' => [],
            ]
        ];

        $cnt = 0;
        foreach ($classesAndTests['classes'] as $class) {
            $fileName = $class['fileName'];
            $fileTest = $classesAndTests['tests']->where('fileName', $fileName . 'Test')
                ->filter(function ($value, $key) use($class) {
                    return in_array($class['name'], $value['uses']);
                })->first();

            if (!$fileTest) {
                $diff['minus']['classes'][] = $class['name'];

                foreach ($class['methods'] as $method) {
                    $diff['minus']['methods'][] = [
                        'name' => $method->name,
                        'class' => $class['name'],
                        'dir' => $class['dir']
                    ];
                }
            } else {
                $methodsTest = collect($fileTest['methods']);
                $diff['plus']['classes'][] = $class['name'];

                foreach ($class['methods'] as $method) {
                    $methodTest = $methodsTest->where('name', 'test' . ucfirst($method->name))->first();

                    $method = [
                        'name' => $method->name,
                        'class' => $class['name'],
                        'dir' => $class['dir']
                    ];

                    if (!$methodTest) {
                        $diff['minus']['methods'][] = $method;
                    } else {
                        $diff['plus']['methods'][] = $method;
                    }
                }
            }
        }

        return $diff;
    }
}
