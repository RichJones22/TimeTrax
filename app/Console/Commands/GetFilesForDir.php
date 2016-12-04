<?php

namespace App\Console\Commands;

use App\Utilities\FileAndDirectoryUtilities;

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

        // 3. for each file:
        // 4. find non scalar types
        // 5. for each non scalar type
        // 6. determine if non scalar type extends the Enum type
        // 7. build EnumParameterType class

//        $this->info($path . PHP_EOL);

        return $this;
    }

    /**
     * @param $path
     *
     * @return array
     */
    protected function getFilesForPath($path)
    {
        return FileAndDirectoryUtilities::getFileForDirectory($path);
    }
}
