<?php
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 9/1/16
 * Time: 7:31 PM
 */

namespace shell;

use Illuminate\Console\Command;
use Laracasts\Integrated\Services\Laravel\Application as Laravel;

/**
 * Class AddModelSettersAndGetters
 * @package shell
 */
class AddModelSettersAndGetters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addModelSettersAndGetters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display an inspiring quote';

    /**
     * @var
     */
    private $modelClassName;
    /**
     * @var
     */
    private $fileContents;


    public function handle()
    {
        $this->initialize($argv[1]);
    }

    /**
     * @param $modelClassName
     * @return bool
     */
    public function initialize($modelClassName)
    {
        // bootstrap laravel
        $this->setUpLaravel();

        // does class exist
        $classPath = $this->app->path() . "/$modelClassName";
        if (!class_exists($classPath)) {
            echo "model $modelClassName not found";
            return false;
        }

        // set fileContents and modelClassName vars
        $this->modelClassName = $modelClassName;
        $this->fileContents = file_get_contents($this->modelClassName);

        // verify fillable variable exists in model
        if (!$this->verifyFillableVariableExists()) {
            echo "model $modelClassName does not contain a fillable variable";
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function verifyFillableVariableExists()
    {
        $tokens = token_get_all($this->fileContents);
        $count = count($tokens);
        for ($i = 1; $i < $count; $i++) {
            if ($tokens[$i - 1][0] == T_VARIABLE) {
                if ($tokens[$i - 1][0] == '$fillable') {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     *
     */
    public function run()
    {

        // derive elements of fillable array
        // make a method from each element.
        // if the method exists remove it along with it's comments.
        // add the method to the file along with comments.


    }
}


$shell = new AddModelSettersAndGetters();

// validate command line arguments
if ($shell->initialize($argv[1])) {
    $shell->run();
}
