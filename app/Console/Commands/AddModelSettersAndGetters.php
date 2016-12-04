<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AddModelSettersAndGetters extends Command
{

    protected $paramName;
    protected $modelClassName;
    protected $modelClassFileName;

    protected $fileContents;
    protected $arrVars;

    CONST MODEL_NS = "\\App\\";
    CONST DS = "/";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:settersGetters 
                               {model : model to add setters and getter to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds setters and getters to a model';

    /**
     * AddModelSettersAndGetters constructor.
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
        if (! $this->init()) {
            return false;
        }

        $this->processModel();

        return $this;
    }

    protected function init()
    {
        return $this->setModelClassNameAndClassFileName();
    }

    protected function processModel()
    {
        $filleables = $this->getFillables();

        foreach ($filleables as $filleable) {

            // process getter
            $arrGetter = $this->deriveGetter($filleable);
            $getterFuncName = $this->getGetterFunctionName($filleable);
            $this->findPlaceMethod($getterFuncName, $arrGetter);

            // process setter
            $arrSetter = $this->deriveSetter($filleable);
            $setterFuncName = $this->getSetterFunctionName($filleable);
            $this->findPlaceMethod($setterFuncName, $arrSetter);
        }

        return $this;
    }

    /**
     * Give a 'model' param, determine that both the file and class exists
     *
     * @return bool
     */
    protected function setModelClassNameAndClassFileName()
    {
//        $this->paramName = $this->argument('model');
        $this->paramName = "ClientTest";

        $this->modelClassName = self::MODEL_NS . "$this->paramName";
        $this->modelClassFileName = app_path() . self::DS . $this->paramName . ".php";

        if (! file_exists($this->modelClassFileName)) {
            echo "file $this->modelClassFileName not found\n";
            return false;
        }

        if (! class_exists($this->modelClassName)) {
            echo "class $this->modelClassName not found\n";
            return false;
        }

        return true;
    }

    /**
     * derive array list of fillables from modelClassName
     *
     * @return mixed
     */
    protected function getFillables()
    {
        $MyClass = new $this->modelClassName();

        return $MyClass->getFillable();
    }

    protected function snakeToCamel($val)
    {
        $val = str_replace(' ', '', ucwords(str_replace('_', ' ', $val)));
        $val = strtolower(substr($val, 0, 1)).substr($val, 1);
        return ucfirst($val);
    }


    /**
     * @param $filleable
     * @return array
     */
    protected function deriveGetter($filleable)
    {
        $arr = [];

        $funcName = $this->getGetterFunctionName($filleable);

        array_push($arr, "\n");
        array_push($arr, "    public function $funcName()\n");
        array_push($arr, "    {\n");
        array_push($arr, '        return $this->attributes[' . "'" . $filleable . "'" . "];\n");
        array_push($arr, "    }\n");
        array_push($arr, "\n");

        return $arr;
    }

    protected function deriveSetter($filleable)
    {
        $arr = [];

        $funcName = "set" . $this->snakeToCamel($filleable);

        $param = "$".$funcName;

        array_push($arr, "    public function $funcName($param)\n");
        array_push($arr, "    {\n");
        array_push($arr, '        $this->attributes[' . "'" . $filleable . "'" . "] = $param;\n");
        array_push($arr, "    }\n");
        array_push($arr, "");

        return $arr;
    }


    /**
     * @param $getterFuncName
     * @param $arrGetter
     * @return $this
     */
    protected function findPlaceMethod($getterFuncName, $getterOrSetter)
    {
        $this->fileContents = file($this->modelClassFileName);
        $count = count($this->fileContents);

        $tmpContents = null;
        $foundAny = false;

        for ($i = 0; $i < $count; $i++) {
            if (strpos($this->fileContents[$i], $getterFuncName)) {

                $startPos = $i;
                $endPos = 0;
                $found = false;
                $leftParen = 0;
                $rightParen = 0;

                for ($j = $i; $j < $count && !$found; $j++) {
                    if (strpos($this->fileContents[$j], '{')) {
                        $leftParen++;
                    }

                    if (strpos($this->fileContents[$j], '}')) {
                        $rightParen++;
                    }

                    if ($leftParen == $rightParen && $leftParen) {
                        $endPos = $j;
                        $found = true;
                    }
                }

                if ($found) {
                    $tmpContents = $this->fileContents;
                    $top = array_slice($tmpContents, 0, $startPos);
                    $bottom = array_slice($tmpContents, $endPos+1);
                    $tmpContents = array_merge($top, $getterOrSetter);
                    $tmpContents = array_merge($tmpContents, $bottom);
                    $this->fileContents = $tmpContents;
                    $foundAny = true;
                }
            }
        }

        if ($foundAny) {
            file_put_contents($this->modelClassFileName, $this->fileContents);
        } else {
            $tmpContents = $this->fileContents;

            // greb one less than the bottom.
            $top = array_slice($tmpContents, 0, -1);
            $tmpContents = array_merge($top, $getterOrSetter);
            $tmpContents = array_merge($tmpContents, ["}\n"]);
            file_put_contents($this->modelClassFileName, $tmpContents);
            $this->fileContents = $tmpContents;
        }


        return $this;
    }

    /**
     * @param $filleable
     * @return string
     */
    protected function getGetterFunctionName($filleable):string
    {
        $funcName = "get" . $this->snakeToCamel($filleable);
        return $funcName;
    }

    protected function getSetterFunctionName($filleable):string
    {
        $funcName = "set" . $this->snakeToCamel($filleable);
        return $funcName;
    }


}
