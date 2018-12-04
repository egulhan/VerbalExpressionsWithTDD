<?php
/**
 * Date: 12/4/2018
 * Time: 11:26 PM
 */

namespace app;

class VerbalExp
{
    protected $_regex = '';
    protected $specialChars = ['/', '.', '{', '}', '[', ']', '?', '+', '*', ':'];

    public function startOfLine()
    {
        $this->append('^');
        return $this;
    }

    public function endOfLine()
    {
        $this->append('$');
        return $this;
    }

    public function then($str)
    {
        $str = $this->escapeString($str);
        $this->append("($str)");
        return $this;
    }

    public function maybe($str)
    {
        $str = $this->escapeString($str);
        $this->append("($str)?");
        return $this;
    }

    public function anythingBut($str)
    {
        $str = $this->escapeString($str);
        $this->append("([^$str]*)");
        return $this;
    }

    public function test($str)
    {
        return !!preg_match($this->regex(), $str);
    }

    public function regex()
    {
        return '/' . $this->_regex . '/';
    }

    protected function append($str)
    {
        $this->_regex .= $str;
    }

    protected function escapeString($str)
    {
        if ($str != ' ') {
            foreach ($this->specialChars as $specialChar) {
                $str = str_replace($specialChar, "\\$specialChar", $str);
            }
        } else {
            $str = '\\ ';
        }

        return $str;
    }

    public function __toString()
    {
        return $this->regex();
    }
}