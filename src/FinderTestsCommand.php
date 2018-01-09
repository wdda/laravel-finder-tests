<?php

namespace WDDA\LaravelFinderTests;

use Illuminate\Console\Command;

class FinderTestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finder-tests {--limit=default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finder tests';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cmd = $this->option('limit');

        if ($cmd == 'default') {
            $cmd = $this->choice('choose limit', ['only not found', 'only found', 'all'], 0);
        }


        $finder = new FinderTests();
        $diff = $finder->findDiff();


        if ($cmd == 0) {
            $this->printToTable($diff, 'minus');
        }

        if ($cmd == 1) {
            $this->printToTable($diff, 'plus');
        }

        if ($cmd == 2) {
            $this->printToTable($diff, 'minus');
            $this->printToTable($diff, 'plus');
        }


        return true;
    }

    public function printToTable($diff, $type)
    {
        if (!empty($diff[$type]['classes'])) {
            if ($type == 'minus') {
                $this->comment('Not found classes tests');
            }

            if ($type == 'plus') {
                $this->info('Found classes tests');
            }

            foreach ($diff[$type]['classes'] as $key => $className) {
                $diff[$type]['classes'][$key] = [$className];
            }

            $this->table(['Classes'], $diff[$type]['classes']);
        }

        if (!empty($diff[$type]['classes'])) {
            if ($type == 'minus') {
                $this->comment('Not methods classes tests');
            }

            if ($type == 'plus') {
                $this->info('Found methods tests');
            }

            $this->table(['Name', 'Class', 'Directory'], $diff[$type]['methods']);
        }
    }
}
