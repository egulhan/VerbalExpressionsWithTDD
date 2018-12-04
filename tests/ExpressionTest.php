<?php
/**
 * Date: 12/4/2018
 * Time: 10:58 PM
 */

use PHPUnit\Framework\TestCase;
use app\VerbalExp;


class ExpressionTest extends TestCase
{
    protected $verbalExp;

    public function setUp()
    {
        parent::setUp();
        $this->verbalExp = new VerbalExp();
    }

    /** @test */
    public function it_matches_regex_against_string()
    {
        $shouldMatch = 'https://www.google.com';
        $shouldNotMatch = 'www.google.com';

        $tester = $this->verbalExp
            ->startOfLine()
            ->then('http')
            ->maybe('s')
            ->then('://')
            ->maybe('www.')
            ->anythingBut(' ')
            ->endOfLine();

        $this->assertTrue($tester->test($shouldMatch));
        $this->assertFalse($tester->test($shouldNotMatch));
    }

    /** @test */
    public function it_escapes_if_it_is_only_one_space()
    {
        $str = ' ';
        $this->verbalExp->anythingBut($str);

        $this->assertEquals('/([^\ ]*)/', $this->r());
    }

    /** @test */
    public function it_should_escape_all_special_chars_in_string()
    {
        $str = '?hello[ yes yes/';

        $this->verbalExp->then($str);

        $expected = '/(\?hello\[ yes yes\/)/';

        $this->assertEquals($expected, $this->r());
    }

    /** @test */
    public function it_escapes_all_special_characters()
    {
        $possibleSpecialChars = ['/', '.', '{', '}', '[', ']', '?', '+', '*', ':'];

        foreach ($possibleSpecialChars as $char) {
            $this->verbalExp->then($char);
        }

        array_walk($possibleSpecialChars, function (&$val, $key) {
            $val = '(\\' . $val . ')';
        });

        $expected = '/' . implode('', $possibleSpecialChars) . '/';

        $this->assertEquals($expected, $this->r());
    }

    /** @test */
    public function it_generates_start_of_line_and_end_of_line()
    {
        $this->verbalExp->startOfLine()->endOfLine();
        $this->assertEquals('/^$/', $this->r());
    }

    /** @test */
    public function it_is_an_empty_if_no_expressions_added()
    {
        $this->assertEquals('//', $this->r());
    }

    /** @test */
    public function it_generates_start_of_line()
    {
        $this->verbalExp->startOfLine();
        $this->assertEquals('/^/', $this->r());
    }

    /** @test */
    public function it_generates_then()
    {
        $str = 'hello';

        $this->verbalExp->then($str);
        $this->assertEquals("/($str)/", $this->r());
    }

    /** @test */
    public function it_generates_maybe()
    {
        $str = 'hello';
        $this->verbalExp->maybe($str);
        $this->assertEquals('/(' . $str . ')?/', $this->r());
    }

    /** @test */
    public function it_generates_anything_but()
    {
        $str = 'hello';
        $this->verbalExp->anythingBut($str);
        $this->assertEquals("/([^$str]*)/", $this->r());
    }

    /** @test */
    public function it_generates_end_of_line()
    {
        $this->verbalExp->endOfLine();
        $this->assertEquals('/$/', $this->r());
    }

    protected function r()
    {
        return $this->verbalExp->regex();
    }
}