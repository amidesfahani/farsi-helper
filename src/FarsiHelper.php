<?php

namespace Amid\Helpers;

class FarsiHelper
{
    public static function isValidBarcode($barcode)
    {
        //checks validity of: GTIN-8, GTIN-12, GTIN-13, GTIN-14, GSIN, SSCC
        //see: http://www.gs1.org/how-calculate-check-digit-manually
        $barcode = (string) $barcode;
        //we accept only digits
        if (!preg_match("/^[0-9]+$/", $barcode)) {
            return false;
        }
        //check valid lengths:
        $l = strlen($barcode);
        if (!in_array($l, [8,12,13,14,17,18]))
            return false;
        //get check digit
        $check = substr($barcode, -1);
        $barcode = substr($barcode, 0, -1);
        $sum_even = $sum_odd = 0;
        $even = true;
        while(strlen($barcode)>0) {
            $digit = substr($barcode, -1);
            if ($even)
                $sum_even += 3 * $digit;
            else 
                $sum_odd += $digit;
            $even = !$even;
            $barcode = substr($barcode, 0, -1);
        }
        $sum = $sum_even + $sum_odd;
        $sum_rounded_up = ceil($sum/10) * 10;
        return ($check == ($sum_rounded_up - $sum));
    }

    public static function isPersianAlpha($input)
    {
        return (bool) preg_match("/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}\s]+$/u", $input);
    }

    public static function isPersianNum($input)
    {
        return (bool) preg_match('/^[\x{6F0}-\x{6F9}]+$/u', $input);
    }

    public static function isPersianAlphaNum($input)
    {
        return (bool) preg_match('/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}\s]+$/u', $input);
    }

    public static function validateColor($input)
    {
        return preg_match(
            '/^(#(?:[0-9a-f]{2}){2,4}|#[0-9a-f]{3}|(?:rgba?|hsla?)\((?:\d+%?(?:deg|rad|grad|turn)?(?:,|\s)+){2,3}[\s\/]*[\d\.]+%?\))$/',
            $input,
            $matches
        ) || preg_match('/#([a-f0-9]{3}){1,2}\b/i', $input, $matches);
    }

    public static function isMelliCode($string)
    {
        if(!preg_match('/^[0-9]{10}$/',$string))
            return false;
        for($i=0;$i<10;$i++)
            if(preg_match('/^'.$i.'{10}$/',$string))
                return false;
        for($i=0,$sum=0;$i<9;$i++)
            $sum+=((10-$i)*intval(substr($string, $i,1)));
        $ret=$sum%11;
        $parity=intval(substr($string, 9,1));
        if(($ret<2 && $ret==$parity) || ($ret>=2 && $ret==11-$parity))
            return true;
        return false;
    }

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

    public static function Mobile($mobile)
    {
        $mobile = self::toEnNumber($mobile);
		if (preg_match("/^09\d{9}$/", $mobile))
		{
			return $mobile;
		}
        if (preg_match("/^9\d{9}$/", $mobile))
		{
			return "0" . $mobile;
		}

        // safety
        return $mobile;
    }

    public static function isMobile($mobile)
    {
        return preg_match("/^(0)?9\d{9}$/", self::FarsiNumbersToEnglish($mobile));
    }

    public static function FarsiNumbersToEnglish($string)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠'];
        $fixedarabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

        $num = range(0, 9);

        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($fixedarabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }

    public static function toEnNumber($input)
    {
        return self::FarsiNumbersToEnglish($input);
        // $replace_pairs = array(
        //     '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
        //     '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'
        // );
        // return strtr($input, $replace_pairs);
    }

    public static function RemoveArabian($string)
    {
        $characters = [
            'ك' => 'ک',
            'دِ' => 'د',
            'بِ' => 'ب',
            'زِ' => 'ز',
            'ذِ' => 'ذ',
            'شِ' => 'ش',
            'سِ' => 'س',
            'ى' => 'ی',
            'ي' => 'ی',
            '١' => '۱',
            '٢' => '۲',
            '٣' => '۳',
            '٤' => '۴',
            '٥' => '۵',
            '٦' => '۶',
            '٧' => '۷',
            '٨' => '۸',
            '٩' => '۹',
            '٠' => '۰',
        ];
        return str_replace(array_keys($characters), array_values($characters), $string);
    }

    public static function jdateFromString($string)
    {
        $months = [
            "January" => "ژانویه",
            "February" => "فوریه",
            "March" => "مارچ",
            "April" => "آوریل",
            "May" => "می",
            "June" => "جون",
            "July" => "جولای",
            "August" => "آگوست",
            "September" => "سپتامبر",
            "October" => "اوکتبر",
            "November" => "نوامبر",
            "December" => "دسامبر"
        ];
        $string = str_replace($months, array_keys($months), $string);
        return jdate($string);
    }
}
