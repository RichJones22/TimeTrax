<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AddModelSettersAndGetters extends Command
{

    protected $modelClassName;
    protected $modelClassFileName;
    protected $fileContents;
    protected $arrVars;

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
        $this->init();
    }

    protected function init()
    {
        $this->modelClassName = $this->argument('model');

        $this->modelClassFileName = app_path() . "/$this->modelClassName.php";

        if (! file_exists($this->modelClassFileName)) {
            echo "model $this->modelClassFileName not found\n";
            return false;
        }

        // set fileContents and modelClassName vars
//        $this->fileContents = file_get_contents($this->modelClassFileName);
//        $this->fileContents = file($this->modelClassFileName);

        $this->fileContents = file_get_contents($this->modelClassFileName);
        $tokens = token_get_all($this->fileContents);

        var_dump($tokens);

//        $lines = $this->fileContents;
//        foreach ($lines as $line) {
//            if (strpos($line, 'name'))
//        }

//        var_dump($this->fileContents);


        // verify fillable variable exists in model
        if (!$this->verifyFillableVariableExists()) {
            echo "model $this->modelClassFileName does not contain a fillable variable\n";
            return false;
        }

        return true;

    }

    public function verifyFillableVariableExists()
    {

//        $myclass = "<?php class MyClass extends \\$this->modelClassName {}\n";

//        $myfile = app_path() . "/Console/Commands" . "/tmpFile";
//        $myfile = __NAMESPACE__ . "\\tmpFile";
//        dd($myfile);

//        if (!class_exists($myfile)) {
//            return false;
//        }

        $myfile = __NAMESPACE__ . "\\MyClass";
        if (!class_exists($myfile)) {
            return false;
        }

        $inst = new $myfile;

        $myArray = $inst->getFillableArr();

        foreach($myArray as $key => $value) {
            echo "for key $key the value is $value\n";
        }





//        $handle = file_put_contents($myfile, $myclass);

//        class_exists($myfile) ? true : false;














//        $this->arrVars = [];
//
//        $tokens = token_get_all($this->fileContents);
//
////        dd($tokens);
////        dd(token_name(378));
//
//        $count = count($tokens);
//        for ($i = 1; $i < $count; $i++) {
//            if ($tokens[$i - 1][0] == T_VARIABLE) {
//                if ($tokens[$i - 1][1] == '$fillable') {
//                    for ($j = $i; $j < $count || $tokens[$j] == ']'; $j++) {
////                        var_dump($tokens[$j]);
//                        if ($tokens[$j - 1][0] == T_CONSTANT_ENCAPSED_STRING) {
//                            var_dump($tokens[$j]);
//
////                              dd($tokens)
//                            array_push($this->arrVars, $tokens[$j - 1][1]);
//                        }
//                    }
//                }
//            }
//        }

//        return $this->arrVars ? false : true;
    }
}
