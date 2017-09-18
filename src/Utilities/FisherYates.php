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
 * Class FisherYates
 *
 * @package   Diarmuidie\NiceID
 * @author    Diarmuid <hello@diarmuid.ie>
 * @copyright 2015 Diarmuid
 * @license   http://www.opensource.org/licenses/MIT The MIT License
 */
class FisherYates
{

    /**
     * Shuffle an array using a salt
     *
     * @param array $array The array to shuffle
     * @param string $secret The secret to salt the shuffle
     * @return array The shuffled array
     */
    public static function shuffle(array $array, $secret)
    {

        // Hash the secret and convert to decimal
        $secret = hexdec(substr(md5($secret), -6));

        // Seed the random number generator
        version_compare(phpversion(),'7.1', '<') ?
          mt_srand($secret) :
          mt_srand($secret, MT_RAND_PHP);

        $shuffledArray = array();
        while (count($array) > 0) {
            // Seed the random number generator

            // Pick a random key from the original array
            $key = mt_rand(0, count($array) - 1);

            // Add it to the shuffled array
            $shuffledArray[] = $array[$key];

            // Unset the value from the original array
            unset($array[$key]);

            // Re-index the array
            $array = array_values($array);
        }

        // Reset the seed
        mt_srand();

        return $shuffledArray;

    }

    /**
     * Un-shuffle an array using a salt
     *
     * @param array $shuffledArray The array to un-shuffle
     * @param string $secret The secret to salt the un-shuffle
     * @return array The un-shuffled array
     */
    public static function unshuffle($shuffledArray, $secret)
    {
        // Hash the secret and convert to decimal
        $secret = hexdec(substr(md5($secret), -6));

        // Seed the random number generator
        version_compare(phpversion(),'7.1', '<') ?
          mt_srand($secret) :
          mt_srand($secret, MT_RAND_PHP);

        $keys = array();
        // Build the encoding keys
        for ($i = count($shuffledArray) - 1; $i >= 0; $i--) {
            $keys[$i] = mt_rand(0, $i);
        }

        $shuffledArray = array_reverse($shuffledArray);

        $array = array();
        // Rebuild the array using the keys
        foreach ($shuffledArray as $key => $value) {
            // Add the value to the array in it's new location
            array_splice($array, $keys[$key], 0, $value);
        }

        // Reset the seed
        mt_srand();

        return $array;
    }
}
