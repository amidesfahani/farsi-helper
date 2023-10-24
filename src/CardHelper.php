<?php

namespace AmidEsfahani\Helpers;

class CardHelper
{
	public static function isCardNumber($value)
    {
        preg_match('/^([1-9]{1})([0-9]{15})$/', $value, $matches, PREG_OFFSET_CAPTURE, 0);
        return boolval(count($matches));
    }

    public static function GenerateCard($type = 'Mastercard')
    {
        $pos = 0;
		$str = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
		$sum = 0;
		$final_digit = 0;
		$t = 0;
		$len_offset = 0;
		$len = 16;

        // Visa
        if ($type == 'Visa') {
            $str[0] = 4;
            $pos = 1;
        }
        // Mastercard
        else if ($type == 'Mastercard') {
            $str[0] = 5;
            $t = floor((float)rand()/(float)getrandmax() * 5) % 5;
            $str[1] = 1 + $t; // Between 1 and 5.
            $pos = 2;
        }
        // American Express
        else if ($type == 'AmericanExpress') {
            $str[0] = 3;
            $t = floor((float)rand()/(float)getrandmax() * 4) % 4;
            $str[1] = 4 + $t; // Between 4 and 7.
            $pos = 2;
        }
        // Discover
        else if ($type == 'Discover') {
            $str[0] = 6;
            $str[1] = 0;
            $str[2] = 1;
            $str[3] = 1;
            $pos = 4;
        }
        while ($pos < $len - 1) {
            $str[$pos++] = floor((float)rand()/(float)getrandmax() * 10) % 10;
        }

        $len_offset = ($len + 1) % 2;
        for ($pos = 0; $pos < $len - 1; $pos++) {
            if (($pos + $len_offset) % 2) {
                $t = $str[$pos] * 2;
                if ($t > 9) {
                    $t -= 9;
                }
                $sum += $t;
            }
            else {
                $sum += $str[$pos];
            }
        }

        $final_digit = (10 - ($sum % 10)) % 10;
        $str[$len - 1] = $final_digit;

        $t = join('', $str);
        $t = $t.substr(0, $len);
        return $t;
    }
}