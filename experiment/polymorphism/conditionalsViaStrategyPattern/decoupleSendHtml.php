<?php
/**
 * Created by PhpStorm.
 * User: Rich Jones
 * Date: 4/17/16
 * Time: 12:50 PM
 */

namespace experiment\polymorphism\conditionalsViaStrategyPattern;


class decoupleSendHtml extends SendHtml implements SendInterface
{
    // allows decoupling of the SendHtml class
    // 1. no implementations for this class; that is done in the extended class.
    // 2. must have extends and implements in the class definition.
}