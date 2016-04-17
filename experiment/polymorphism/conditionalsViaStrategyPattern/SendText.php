<?php
/**
 * Created by PhpStorm.
 * User: Rich Jones
 * Date: 4/17/16
 * Time: 9:05 AM
 */

namespace experiment\polymorphism\conditionalsViaStrategyPattern;


class SendText implements SendInterface
{
    public function send($textToSend)
    {
        var_dump("text sent: " . $textToSend);
    }
}