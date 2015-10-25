<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 28/09/2015
 * Time: 7:08 PM
 */

class Output {

    public static function phpOutput($string)
    {
        return htmlentities($string);
    }

    public static function phpPrice($price)
    {
        return number_format($price, 2);
    }

    /**
     * Shorten a output if its too long
     *
     * @param $value
     * @param $length
     * @return string
     */
    public static function shortenString($value, $length)
    {
        $short = substr($value, 0, $length);
        return $short;
    }
}