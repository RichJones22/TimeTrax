<?php
/**
 * Created by PhpStorm.
 * User: Rich Jones
 * Date: 4/17/16
 * Time: 9:04 AM
 */

namespace experiment\polymorphism\conditionalsViaStrategyPattern;


class SendHtml implements SendInterface
{
    public function send($textToSend)
    {
        var_dump("Html sent: " . $textToSend);
    }
}