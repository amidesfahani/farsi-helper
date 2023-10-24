<?php

namespace AmidEsfahani\Helpers;

class ColorHelper
{
	public static function validateColor($input)
    {
        return preg_match(
            '/^(#(?:[0-9a-f]{2}){2,4}|#[0-9a-f]{3}|(?:rgba?|hsla?)\((?:\d+%?(?:deg|rad|grad|turn)?(?:,|\s)+){2,3}[\s\/]*[\d\.]+%?\))$/',
            $input,
            $matches
        ) || preg_match('/#([a-f0-9]{3}){1,2}\b/i', $input, $matches);
    }
}