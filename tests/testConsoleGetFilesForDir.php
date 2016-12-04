<?php

use App\Console\Commands\GetFilesForDir;

/**
 * Class testConsoleGetFilesForDir.
 */
class testConsoleGetFilesForDir extends TestCase
{
    /**
     * test path not found.
     */
    public function testPathNotFound()
    {
        // directory not found.
        $dir = 'tests1';

        // run command on $dir; expecting a not found message.
        Artisan::call('timetrax:get-files-for-directory', [
            'path' => $dir,
        ]);

        $this->assertContains(GetFilesForDir::PATH_NOT_FOUND, Artisan::output());
    }

    /**
     * test path found.
     */
    public function testPathFound()
    {
        // directory found.
        $dir = 'tests';

        // run command on $dir; expecting a not found message.
        Artisan::call('timetrax:get-files-for-directory', [
            'path' => $dir,
        ]);

        $this->assertNotContains(GetFilesForDir::PATH_NOT_FOUND, Artisan::output());
    }
}
