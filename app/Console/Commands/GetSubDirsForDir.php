<?php

namespace app\Console\Commands;

use App\Utilities\FileAndDirectoryUtilities;

/**
 * Class GetSubDirsForDir.
 */
class GetSubDirsForDir extends BaseFileDirCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timetrax:get-sub-dirs-for-directory
                                   {path : path containing directories}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Given a directory find all sub directories';

    /**
     * GetSubDirsForDir constructor.
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
        $path = $this->argument('path');

        // Validate the path
        if (!$this->validatePath($path)) {
            return $this;
        }

        // Get all directories from path.
        $dirs = $this->getDirectoriesForDirectory($path);

        // echo the output.
        foreach ($dirs as $dir) {
            $this->line($dir);
        }

        return $this;
    }

    /**
     * @param $path
     *
     * @return array
     */
    protected function getDirectoriesForDirectory($path)
    {
        return FileAndDirectoryUtilities::getDirectoriesForDirectory($path);
    }
}
