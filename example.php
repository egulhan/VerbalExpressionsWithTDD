<?php
/**
 * Date: 12/5/2018
 * Time: 12:24 AM
 */

use app\VerbalExp;

require('vendor/autoload.php');

$verbalExp = new VerbalExp();

$tester = $verbalExp
    ->startOfLine()
    ->then('http')
    ->maybe('s')
    ->then('://')
    ->maybe('www.')
    ->anythingBut(' ')
    ->endOfLine();

$testMe = 'https://www.google.com';
$result = $tester->test($testMe) ? 'matched' : 'not matched';

echo <<<OUTPUT

REGEX: {$tester->regex()}

INPUT: $testMe

RESULT: $result

OUTPUT;
