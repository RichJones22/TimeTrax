<?php
/**
 * Created by PhpStorm.
 * User: Rich Jones
 * Date: 4/17/16
 * Time: 12:45 PM
 */

namespace experiment\polymorphism\conditionalsViaStrategyPattern;


class abstractSendText extends SendText implements SendInterface
{
    // allows decoupling of SendText class
}