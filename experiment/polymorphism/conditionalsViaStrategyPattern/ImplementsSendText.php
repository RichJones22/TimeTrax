<?php
/**
 * Created by PhpStorm.
 * User: Rich Jones
 * Date: 4/17/16
 * Time: 12:45 PM
 */

namespace experiment\polymorphism\conditionalsViaStrategyPattern;


class ImplementsSendText extends SendText implements SendInterface
{
    // decoupling of the SendHtml class; allows for implemented by another interface.
    // 1. no implementations for this class; that is done in the extended class.
    // 2. must have extends and implements in the class definition.
}