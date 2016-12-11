<?php

declare(strict_types=1);

namespace premise\utilities;

use DirectoryIterator;

/**
 * Class PremiseUtilities.
 */
class PremiseUtilities
{
    /**
     * @param $path
     *
     * @return array
     */
    public static function getFileForDirectory($path)
    {
        $files = [];
        $dir = new DirectoryIterator($path);
        foreach ($dir as $fileInfo) {
            if ($fileInfo->isFile()) {
                $files[] = $fileInfo->getFilename();
            }
        }

        return $files;
    }

    /**
     * @param $path
     *
     * @return array
     */
    public static function getDirectoriesForDirectory($path)
    {
        $files = [];
        $iterator = new DirectoryIterator($path);
        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isDir() && ! $fileInfo->isDot()) {
                $files[] = $fileInfo->getFilename();
            }
        }

        return $files;
    }

    /**
     * @param $path
     *
     * @return array
     */
    public static function getPhpFilesInDirectoryRecursive($path)
    {
        $files = [];
        $directory = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($directory);
        foreach ($iterator as $fileInfo) {
            $files[] = $fileInfo->getFilename();
        }

        return $files;
    }

    /**
     * @param $path
     * @param string $ext
     *
     * @return array
     */
    public static function getFilesInDirectoryRecursiveByFileExt($path, $ext = 'PHP')
    {
        $matchString = '/^.+\.'.$ext.'$/i';

        $files = [];
        $directory = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($directory);
        foreach ($iterator as $fileInfo) {
            if (preg_match($matchString, $fileInfo->getFilename())) {
                //                dd($fileInfo);
                $files[] = $fileInfo->getPathName();
            }
        }

        return $files;
    }
}
