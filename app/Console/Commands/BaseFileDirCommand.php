<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
class BaseFileDirCommand extends Command
{
    /**
     * path parameter directory must exist.
     */
    const PATH_NOT_FOUND = 'does not exist.  Please enter a valid path...';

    /**
     * @param $path
     *
     * @return bool
     */
    protected function validatePath($path)
    {
        if (!file_exists($path)) {
            $this->FormatErrorMessage("path ($path) ".self::PATH_NOT_FOUND);

            return false;
        }

        return true;
    }

    /**
     * @param string $string
     */
    protected function FormatErrorMessage(string $string)
    {
        $message = ''.PHP_EOL;
        $message .= $string.PHP_EOL;
        $message .= ''.PHP_EOL;

        $this->error($message);
    }
}