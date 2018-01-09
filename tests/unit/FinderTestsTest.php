<?php
use WDDA\LaravelFinderTests\FinderTests;

class FinderTestsTest extends TestCase
{
    public $finderTests;
    public $config;

    protected function setUp()
    {
        $this->config = include 'tests/config.php';
        $this->finderTests = new FinderTests($this->config);
        parent::setUp();
    }

    public function testGetDefaultConfig()
    {
        $defaultConfig = $this->finderTests->getDefaultConfig();
        $this->assertInternalType('array', $defaultConfig);
        $this->assertNotEmpty($defaultConfig);
        $this->assertNotEmpty($defaultConfig['directory']);
        $this->assertNotEmpty($defaultConfig['directory'][0]['classes']['dir']);
        $this->assertNotEmpty($defaultConfig['directory'][0]['tests']['dir']);
    }

    public function testGetCustomConfig()
    {
        $customConfig = $this->finderTests->getCustomConfig();
        $this->assertFalse($customConfig);
    }

    public function testGetConfig()
    {
        $config = $this->finderTests->getConfig();
        $this->assertNotEmpty($config);
        $this->assertNotEmpty($config['directory']);
        $this->assertNotEmpty($config['directory'][0]['classes']['dir']);
    }

    public function testGetFilesFromDirectory()
    {
        $configTest = $this->config;
        $files = $this->finderTests->getFilesFromDirectory($configTest['directory'][0], 'classes');
        $this->assertInternalType('array', $files);
    }

    public function testGetFiles()
    {
        $files = $this->finderTests->getFiles('classes');
        $this->assertInternalType('array', $files);
    }

    public function testGetClass()
    {
        $configTest = $this->config;
        $files = $this->finderTests->getFilesFromDirectory($configTest['directory'][0], 'classes');
        $class = $this->finderTests->getClass($files[0]);
        $this->assertInternalType('string', $class);
    }

    public function testGetMethods()
    {
        $configTest = $this->config;
        $files = $this->finderTests->getFilesFromDirectory($configTest['directory'][0], 'classes');
        $class = $this->finderTests->getClass($files[0]);
        $methods = $this->finderTests->getMethods($class, false);
        $this->assertInternalType('array', $methods);
        $this->assertNotEmpty($methods);
    }

    public function testFindClasses()
    {
        $classes = $this->finderTests->findClasses('classes');
        $this->assertInternalType('array', $classes);
    }

    public function testFinder()
    {
        $classes = $this->finderTests->finder();
        $this->assertInternalType('array', $classes);
        $this->assertInternalType('array', $classes['classes']->toArray());
        $this->assertInternalType('array', $classes['tests']->toArray());
    }

    public function testFindDiff()
    {
        $diff = $this->finderTests->findDiff();
        $this->assertInternalType('array', $diff);
        $this->assertInternalType('array', $diff['minus']);
        $this->assertInternalType('array', $diff['plus']);
    }
}
