<?php
/**
 * Created by PhpStorm.
 * User: Rich Jones
 * Date: 4/17/16
 * Time: 9:06 AM
 */

namespace experiment\polymorphism\conditionalsViaStrategyPattern;


class SendFactory
{
    private $builtClass;

    public function __construct($type) {
        $className = "experiment\\polymorphism\\conditionalsViaStrategyPattern\\" . "Send" . ucfirst($type);

        if (! class_exists($className)) {
            throw new \RuntimeException('Incorrect email Send type');
        }

        $this->builtClass = new $className;
    }

    public function getBuiltClass() : SendInterface {
        return $this->builtClass;
    }
}