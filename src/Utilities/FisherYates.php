<?php
/**
 * User: diarmuid <hello@diarmuid.ie>
 * Date: 22/03/15
 */

namespace Diarmuidie\NiceID\Utilities;

/**
 * Class FisherYates
 * @package Diarmuidie\NiceID\Utilities
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
        mt_srand($secret);

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

}
