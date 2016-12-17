<?php

namespace App\Console\Commands;

use Premise\Utilities\PremiseUtilities;

class GetFilesForDir extends BaseFileDirCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timetrax:get-files-for-directory
                               {path : path containing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Given a directory find all files';

    /**
     * GetFilesForDir constructor.
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

        // Get all files from path.
        $files = $this->getFilesForPath($path);

        // echo the output.
        foreach ($files as $file) {
            $this->line($file);
        }

        return $this;
    }

    /**
     * @param $path
     *
     * @return array
     */
    protected function getFilesForPath($path)
    {
        return PremiseUtilities::getFilesInDirectoryRecursiveByFileExt($path);
    }
}
