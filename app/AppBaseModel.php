<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AppBaseModel
 * @package App
 */
abstract class AppBaseModel extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function queryExec()
    {
        $model = app()->make(get_called_class());
        return $model->newQuery();
    }

    /**
     * @return mixed
     */
    public static function getModel()
    {
        return app()->make(get_called_class());
    }

}