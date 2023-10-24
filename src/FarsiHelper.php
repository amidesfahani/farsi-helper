<?php

namespace AmidEsfahani\Helpers;

class FarsiHelper
{
    public static function isPersianAlpha($input): bool
    {
        return (bool) preg_match("/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}\s]+$/u", $input);
    }

    public static function isPersianNum($input): bool
    {
        return (bool) preg_match('/^[\x{6F0}-\x{6F9}]+$/u', $input);
    }

    public static function isPersianAlphaNum($input): bool
    {
        return (bool) preg_match('/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}\s]+$/u', $input);
    }

    public static function isMelliCode($string): bool
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

    public static function FarsiNumbersToEnglish($string): array|string
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠'];
        $fixedarabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

        $num = range(0, 9);

        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($fixedarabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }

    public static function toEnNumber($input): array|string
    {
        if (is_null($input)) return null;
        if (preg_match("/^\d+$/", $input)) return $input;
        return self::FarsiNumbersToEnglish($input);
    }

    public static function RemoveArabian($string): array|string
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

    public static function convertKa($str): array|string|null
    {
        $re = '/ك/';
        return preg_replace($re, 'ک', $str);
    }

    public static function convertYa($str): array|string|null
    {
        $re = '/ي/';
        return preg_replace($re, 'ی', $str);
    }

    public static function convertArabicNum($str): array|string|null
    {
        $regexDecimal = '/([۰۱۲۳۴۵۶۷۸۹]+)([\.\/])([۰۱۲۳۴۵۶۷۸۹]+)/';
        $regexFix = '/([۰۱۲۳۴۵۶۷۸۹]+)٫([۰۱۲۳۴۵۶۷۸۹]+)\/([۰۱۲۳۴۵۶۷۸۹]+)/';

        $str = preg_replace([
            '/٠/', '/١/', '/٢/', '/٣/', '/٤/', '/٥/', '/٦/', '/٧/', '/٨/', '/٩/'
        ], [
            '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'
        ], $str);

        $str = preg_replace($regexDecimal, '$1٫$3', $str);
        $str = preg_replace($regexFix, '$1/$2/$3', $str);

        return $str;
    }

    public static function convertEnglishNum($str): array|string|null
    {
        $regexDecimal = '/([۰۱۲۳۴۵۶۷۸۹]+)([\.\/])([۰۱۲۳۴۵۶۷۸۹]+)/';
        $regexFix = '/([۰۱۲۳۴۵۶۷۸۹]+)٫([۰۱۲۳۴۵۶۷۸۹]+)\/([۰۱۲۳۴۵۶۷۸۹]+)/';

        $str = preg_replace([
            '/0/', '/1/', '/2/', '/3/', '/4/', '/5/', '/6/', '/7/', '/8/', '/9/'
        ], [
            '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'
        ], $str);
        $str = preg_replace($regexDecimal, '$1٫$3', $str);
        $str = preg_replace($regexFix, '$1/$2/$3', $str);

        return $str;
    }

    public static function convertDecimalSeparator($str): array|string|null
    {
        $regexDecimal = '/([۰۱۲۳۴۵۶۷۸۹]+)([\.\/])([۰۱۲۳۴۵۶۷۸۹]+)/';
        $regexFix = '/([۰۱۲۳۴۵۶۷۸۹]+)٫([۰۱۲۳۴۵۶۷۸۹]+)\/([۰۱۲۳۴۵۶۷۸۹]+)/';

        $str = preg_replace($regexDecimal, '$1٫$3', $str);
        $str = preg_replace($regexFix, '$1/$2/$3', $str);

        return $str;
    }

    public static function convertParenthesisSpace($str): array|string|null
    {
        $regexParenthesis1 = '/([\wا-ی]+)(\s{0}|\s{2,})([\(\[\{])/';
        $regexParenthesis2 = '/([\(\[\{])\s+/';
        $regexParenthesis3 = '/\s+([\)\]\}])/';
        $regexParenthesis4 = '/([\)\]\}])(\s{0}|\s{2,})([\wا-ی]+)/';

        $str = preg_replace($regexParenthesis1, '$1 $3', $str);
        $str = preg_replace($regexParenthesis2, '$1', $str);
        $str = preg_replace($regexParenthesis3, '$1', $str);
        $str = preg_replace($regexParenthesis4, '$1 $3', $str);

        return $str;
    }

    public static function convertPunctuationSpace($str): array|string|null
    {
        $regexPunctuation1 = '/([\wا-ی]+)\s+([\.\؟\!\?])/';
        $regexPunctuation2 = '/([\.\؟\!\?])(\s{0}|\s{2,})([\wا-ی]+)/';

        $str = preg_replace($regexPunctuation1, '$1$2', $str);
        $str = preg_replace($regexPunctuation2, '$1 $3', $str);

        return $str;
    }
}
