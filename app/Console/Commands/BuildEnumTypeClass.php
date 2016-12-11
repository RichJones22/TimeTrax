<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Utilities\FileAndDirectoryUtilities;
use ReflectionClass;
use ReflectionMethod;

class BuildEnumTypeClass extends BaseFileDirCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timetrax:build-enum-type-class
                               {entity-path : root dir containing all entities}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the EnumTypeClass';

    /**
     * BuildEnumTypeClass constructor.
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
        $path = $this->argument('entity-path');

        // Validate the path
        if ( ! $this->validatePath($path)) {
            return $this;
        }

        // Get all files from path.
        $NameSpaces = $this->getFilesInDirectoryRecursiveByFileExt($path, 'PHP');

        // echo the output.
        foreach ($NameSpaces as $NameSpace) {
            $NameSpace = $this->formatNameSpace($NameSpace);

            if (class_exists($NameSpace, true) === true) {
                $Class = new ReflectionClass($NameSpace);
                $Methods = $Class->getMethods();
                foreach ($Methods as $method) {
                    //                    var_dump($method->getName());
                    $reflection = new ReflectionMethod($method->class, $method->getName());
                    $doc = $reflection->getDocComment();
                    preg_match('/@param\s+([^\s]+)/', $doc, $type);

                    if (isset($type[1])) {
                        $type = $type[1];
//                        if ($result === 'string' ||
//                            $result === 'int'    ||
//                            $result === 'datetime' ||
//                            $result === 'date'     ||
//                            $result === 'float') {
//                            continue;
//                        }
                        if ($type === 'int') {
                            var_dump($method->class, $method->getName(), $type);
                        }
                    }
                }
//                dd();
            }
        }

        return $this;
    }

    protected function getFilesInDirectoryRecursiveByFileExt($path, $ext)
    {
        return FileAndDirectoryUtilities::getFilesInDirectoryRecursiveByFileExt($path, $ext);
    }

    /**
     * @param $NameSpace
     *
     * @return mixed
     */
    protected function formatNameSpace($NameSpace)
    {
        $NameSpace = str_replace('/', ' ', $NameSpace);
        $result = ucwords($NameSpace);
        $NameSpace = str_replace(' ', '\\', $result);
        $NameSpace = preg_replace('/\\.[^.\\s]{3,4}$/', '', $NameSpace);

        return $NameSpace;
    }
}
