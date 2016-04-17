<?php
/**
 * Created by PhpStorm.
 * User: Rich Jones
 * Date: 4/17/16
 * Time: 12:50 PM
 */

namespace experiment\polymorphism\conditionalsViaStrategyPattern;


class abstractSendHtml extends SendHtml implements SendInterface
{
    // allows decoupling of the SendHtml class
}