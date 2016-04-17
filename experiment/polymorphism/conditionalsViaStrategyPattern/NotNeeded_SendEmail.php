<?php
/**
 * Created by PhpStorm.
 * User: Rich Jones
 * Date: 4/17/16
 * Time: 9:07 AM
 */

namespace experiment\polymorphism\conditionalsViaStrategyPattern;


class SendEmail
{
    private $emailType=null;

    public function __construct(SendInterface $emailType) {
        $this->emailType = $emailType;
    }

    public function send($text) {
        $this->emailType->send($text);
    }
}