<?php
/**
 * Created by PhpStorm.
 * User: Rich Jones
 * Date: 4/17/16
 * Time: 9:03 AM
 */

namespace experiment\polymorphism\conditionalsViaStrategyPattern;


interface SendInterface
{
    public function send($textToSend);
}

