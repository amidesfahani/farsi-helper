<?php

namespace AmidEsfahani\Helpers;

class MobileHelper
{
	public static function Mobile($mobile): string
    {
        $mobile = FarsiHelper::toEnNumber($mobile);
		if (preg_match("/^09\d{9}$/", $mobile))
		{
			return $mobile;
		}
        if (preg_match("/^9\d{9}$/", $mobile))
		{
			return "0" . $mobile;
		}
        return $mobile;
    }

    public static function isMobile($mobile): int|bool
    {
        return preg_match("/^(0)?9\d{9}$/", FarsiHelper::FarsiNumbersToEnglish($mobile));
    }

	public static function mobileNumberPrefixes(): array
    {
        return [
            '0910#######', //mci
            '0911#######',
            '0912#######',
            '0913#######',
            '0914#######',
            '0915#######',
            '0916#######',
            '0917#######',
            '0918#######',
            '0919#######',
            '0901#######',
            '0901#######',
            '0902#######',
            '0903#######',
            '0930#######',
            '0933#######',
            '0935#######',
            '0936#######',
            '0937#######',
            '0938#######',
            '0939#######',
            '0920#######',
            '0921#######',
            '0937#######',
            '0990#######', // MCI
        ];
    }
}