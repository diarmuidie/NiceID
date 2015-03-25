<?php
/**
 * Convert from any base to any other base and specify the character set.
 *
 * Based on http://php.net/manual/en/function.base-convert.php#106546
 */

namespace Diarmuidie\NiceID\Utilities;

/**
 * Class BaseConvert
 * @package Diarmuidie\NiceID\Utilities
 */
class BaseConvert
{

    /**
     * Convert number from any base to any base.
     *
     * @param string $numberInput The number to convert
     * @param string $fromBaseInput The base charset of the supplied number
     * @param string $toBaseInput The base charset for the converted number
     * @return int|string
     */
    public static function convert($numberInput, $fromBaseInput, $toBaseInput)
    {
        if ($fromBaseInput == $toBaseInput) {
            return $numberInput;
        }

        // Build base character arrays
        $toBase = self::mb_str_split($toBaseInput, 1);

        // Get base lengths
        $toLen = mb_strlen($toBaseInput);

        $returnValue = '';

        // Convert to base 10
        if ($toBaseInput == '0123456789') {
            return self::base10convert($numberInput, $fromBaseInput);
        }

        // If First convert the number to base10
        if ($fromBaseInput != '0123456789') {
            $base10 = self::base10convert($numberInput, $fromBaseInput);
        } else {
            $base10 = $numberInput;
        }

        if ($base10 < mb_strlen($toBaseInput)) {
            return $toBase[$base10];
        }

        while ($base10 != '0') {
            $returnValue = $toBase[bcmod($base10, $toLen)] . $returnValue;
            $base10 = bcdiv($base10, $toLen, 0);
        }

        return $returnValue;
    }

    private static function base10convert($numberInput, $fromBaseInput)
    {

        // Build base character arrays
        $numberLen = mb_strlen($numberInput);
        $fromLen = mb_strlen($fromBaseInput);

        // Get base lengths
        $number = self::mb_str_split($numberInput, 1);
        $fromBase = self::mb_str_split($fromBaseInput);

        $returnValue = 0;
        for ($i = 1; $i <= $numberLen; $i++) {
            $returnValue = bcadd(
                $returnValue,
                bcmul(array_search($number[$i - 1], $fromBase), bcpow($fromLen, $numberLen - $i))
            );
        }
        return $returnValue;

    }

    /**
     * Split a (multibyte) string into array
     *
     * @param $string
     * @return array
     */
    private static function mb_str_split($string)
    {
        return preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
    }
}
