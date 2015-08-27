<?php

/**
 * This file is part of Diarmuidie\NiceID.
 *
 * (c) Diarmuid <hello@diarmuid.ie>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Diarmuidie\NiceID\Utilities;

/**
 * Class BaseConvert
 *
 * Convert from any base to any other base and specify the character set.
 * Based on http://php.net/manual/en/function.base-convert.php#106546
 *
 * @package   Diarmuidie\NiceID
 * @author    Diarmuid <hello@diarmuid.ie>
 * @copyright 2015 Diarmuid
 * @license   http://www.opensource.org/licenses/MIT The MIT License
 */
class BaseConvert
{

    /**
     * Convert number from any base to any base.
     *
     * @param string $numberInput        The number to convert
     * @param string $fromBaseCharacters The base charset of the supplied number
     * @param string $toBaseCharacters   The base charset for the converted number
     *
     * @return int|string
     */
    public static function convert($number, $fromBaseCharacters, $toBaseCharacters)
    {
        $decimalNumber = self::nonDecimalToDecimal($number, $fromBaseCharacters);

        if (self::isDecimalCharacterSet($toBaseCharacters)) {
            return $decimalNumber;
        }

        return self::decimalToNonDecimal($decimalNumber, $toBaseCharacters);
    }

    /**
     * Is the character set a decimal characterset
     *
     * @param  string  $characterSet
     *
     * @return boolean
     */
    private static function isDecimalCharacterSet($characterSet)
    {
        return ($characterSet == '0123456789');
    }

    /**
     * Convert a number from any base to decimal
     *
     * @param string $number               The number to convert
     * @param string $fromBaseCharacterSet The base charset of the input number
     *
     * @return int The base 10 number
     */
    private static function nonDecimalToDecimal($number, $fromBaseCharacterSet)
    {
        // Build base character arrays
        $numberCharacters = self::mbStrSplit($number);
        $fromBaseCharacters = self::mbStrSplit($fromBaseCharacterSet);

        // Get base lengths
        $numberLen = count($numberCharacters);
        $fromLen = count($fromBaseCharacters);

        $decimal = 0;

        foreach ($numberCharacters as $count => $numberCharacter) {
            $characterAsDecimal = array_search($numberCharacter, $fromBaseCharacters);
            $exponent = bcpow($fromLen, $numberLen - ($count + 1));

            $total = bcmul($characterAsDecimal, $exponent);
            $decimal = bcadd($decimal, $total);
        }
        return $decimal;
    }

    /**
     * Convert a number from any base to decimal
     *
     * @param string $numberInput The number to convert
     * @param string $fromBaseInput The base charset of the input number
     *
     * @return int The base 10 number
     */
    private static function decimalToNonDecimal($number, $toBaseCharacterSet)
    {
        // Build base character arrays
        $toBaseCharacters = self::mbStrSplit($toBaseCharacterSet);

        // If the input number is less than the decimal number do a direct lookup
        if ($number < count($toBaseCharacters)) {
            return $toBaseCharacters[$number];
        }

        return self::decimaltoMultiDigitNonDecimal($number, $toBaseCharacters);
    }

    /**
     * Convert a decimal number to a multi digit non decimal number
     *
     * @param  Int   $number           Decimal Input
     * @param  Array $toBaseCharacters Array of characters in non decimal base
     *
     * @return String
     */
    private static function decimaltoMultiDigitNonDecimal($number, $toBaseCharacters)
    {
        $returnValue = '';

        while ($number != '0') {
            $SmallestExponent = bcmod($number, count($toBaseCharacters));
            $returnValue = $toBaseCharacters[$SmallestExponent] . $returnValue;
            $number = bcdiv($number, count($toBaseCharacters), 0);
        }

        return $returnValue;
    }

    /**
     * Split a (multibyte) string into array
     *
     * @param $string
     *
     * @return array
     */
    public static function mbStrSplit($string)
    {
        return preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
    }
}
