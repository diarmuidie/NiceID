<?php
/**
 * Taken from http://php.net/manual/en/function.base-convert.php#106546
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

        $fromBase = str_split($fromBaseInput, 1);
        $toBase = str_split($toBaseInput, 1);
        $number = str_split($numberInput, 1);
        $fromLen = strlen($fromBaseInput);
        $toLen = strlen($toBaseInput);
        $numberLen = strlen($numberInput);
        $retval = '';

        if ($toBaseInput == '0123456789') {
            $retval = 0;
            for ($i = 1; $i <= $numberLen; $i++) {
                $retval = bcadd(
                    $retval,
                    bcmul(array_search($number[$i - 1], $fromBase), bcpow($fromLen, $numberLen - $i))
                );
            }
            return $retval;
        }

        if ($fromBaseInput != '0123456789') {
            $base10 = self::convert($numberInput, $fromBaseInput, '0123456789');
        } else {
            $base10 = $numberInput;
        }

        if ($base10 < strlen($toBaseInput)) {
            return $toBase[$base10];
        }

        while ($base10 != '0') {
            $retval = $toBase[bcmod($base10, $toLen)] . $retval;
            $base10 = bcdiv($base10, $toLen, 0);
        }
        return $retval;
    }
}
