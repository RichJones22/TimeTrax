<?php
/**
 * Created by PhpStorm.
 * User: Rich Jones
 * Date: 4/17/16
 * Time: 9:08 AM
 */

namespace experiment\polymorphism\conditionalsViaStrategyPattern;

use experiment\polymorphism\conditionalsViaStrategyPattern\SendFactory as BuildEmailClass;

require_once '../../../vendor/autoload.php';

class AppEmail
{
    const EMAIL_HTML = 'Html';
    const EMAIL_TEXT = 'Text';
    const EMAIL_FILE1 = 'File1';

    static function sendIt($type, $text) {
        (new BuildEmailClass($type))->getBuiltClass()->send($text);
    }

    static function sendHtml($text) {
        (new BuildEmailClass(AppEmail::EMAIL_HTML))->getBuiltClass()->send($text);
    }

    static function sendText($text) {
        (new BuildEmailClass(AppEmail::EMAIL_TEXT))->getBuiltClass()->send($text);
    }
}

AppEmail::sendHtml('this is it');
AppEmail::sendText('this is it');
//
AppEmail::sendIt(AppEmail::EMAIL_HTML, 'my message');
AppEmail::sendIt(AppEmail::EMAIL_TEXT, 'my message');