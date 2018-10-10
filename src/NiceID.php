<?php

/**
 * This file is part of Diarmuidie\NiceID.
 *
 * (c) Diarmuid <hello@diarmuid.ie>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Diarmuidie\NiceID;

use Diarmuidie\NiceID\Utilities\BaseConvert;
use Diarmuidie\NiceID\Utilities\FisherYates;

/**
 * Class NiceID
 *
 * @package   Diarmuidie\NiceID
 * @author    Diarmuid <hello@diarmuid.ie>
 * @copyright 2015 Diarmuid
 * @license   http://www.opensource.org/licenses/MIT The MIT License
 */
class NiceID implements NiceIDInterface
{

    /**
     * @var string The default characters to use when encoding
     */
    protected $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_';

    /**
     * @var int The default min length of the encoded string
     */
    protected $minLength = 5;

    /**
     * @var string The default secret to use for shuffling
     */
    protected $secret = 'Random secret string';

    /**
     * Optionally set the secret at initialisation
     *
     * @param null|string $secret
     */
    public function __construct($secret = null)
    {

        if ($secret !== null) {
            $this->secret = $secret;
        }
    }

    /**
     * Set the secret
     *
     * @param string $secret
     */
    public function setSecret($secret)
    {

        $this->secret = $secret;
    }

    /**
     * Set the characters string to use for encoding
     *
     * @param string $characters
     */
    public function setCharacters($characters)
    {

        $this->characters = $characters;
    }

    /**
     * Set the min length of the encoded string
     *
     * @param int $minLength
     */
    public function setMinLength($minLength)
    {

        $this->minLength = $minLength;
    }

    /**
     * Get the maximum allowed value for the ID
     *
     * @return int the maximum value of ID
     */
    public function getMaxID()
    {

        $maxID = PHP_INT_MAX - $this->minLengthAdder($this->characters, $this->minLength);
        return $maxID;
    }

    /**
     * Encode an integer into a NiceID string
     *
     * @param int $id The ID to encode
     *
     * @return string The encoded ID
     */
    public function encode($id)
    {

        if (!is_int($id)) {
            throw new \InvalidArgumentException(
                'The provided ID must be an integer. ' . ucfirst(gettype($id)) . ' provided.'
            );
        }

        // Check the ID is within bounds
        if ($id > $this->getMaxID()) {
            throw new \LengthException(
                'The provided ID is greater than the maximum allowed ID'
            );
        }

        // Split characters string into array
        $charactersArray = BaseConvert::mbStrSplit($this->characters);

        // Pick a random salt character
        $salt = $charactersArray[mt_rand(0, count($charactersArray) - 1)];

        // Shuffle the array
        $shuffledCharactersArray = FisherYates::shuffle($charactersArray, $this->secret . $salt);

        $characters = implode($shuffledCharactersArray);

        // If a minLength is set bump up the input ID by this many orders of magnitude
        if ($this->minLength > 2) {
            $id += $this->minLengthAdder($this->characters, $this->minLength);
        }

        // Encode the ID
        $niceId = BaseConvert::convert($id, '0123456789', $characters) . $salt;

        // Shuffle the ID to avoid repeated characters in padded strings
        $niceIdArray = BaseConvert::mbStrSplit($niceId);
        $niceId = FisherYates::shuffle($niceIdArray, $this->secret);
        $niceId = implode($niceId);

        return $niceId;
    }

    /**
     * Decode a NiceId into an integer
     *
     * @param string $niceId
     *
     * @return int
     */
    public function decode($niceId)
    {

        if (!is_scalar($niceId)) {
            throw new \InvalidArgumentException(
                'The provided NiceId must be a scalar. ' . ucfirst(gettype($niceId)) . ' provided.'
            );
        }

        // Unshuffle the ID
        $niceIdArray = BaseConvert::mbStrSplit($niceId);
        $niceId = FisherYates::unshuffle($niceIdArray, $this->secret);
        $niceId = implode($niceId);

        // Split characters string into array
        $charactersArray = BaseConvert::mbStrSplit($this->characters);

        $salt = $this->getSaltChar($niceId);

        // Shuffle the array
        $shuffledCharactersArray = FisherYates::shuffle($charactersArray, $this->secret . $salt);
        $characters = implode($shuffledCharactersArray);

        $niceId = $this->getNiceID($niceId);

        // Decode the ID
        $id = (int)BaseConvert::convert($niceId, $characters, '0123456789');

        // If a minLength is set remove the value additions from the ID
        if ($this->minLength > 2) {
            $id -= $this->minLengthAdder($this->characters, $this->minLength);
        }

        return $id;
    }

    /**
     * Get value to add to id to satisfy min length requirement
     *
     * @param string $characters The characters string
     * @param int $minLength Min Length
     *
     * @return number
     */
    private function minLengthAdder($characters, $minLength)
    {

        return pow(strlen($characters), $minLength - 2);
    }

    /**
     * Extract the salt ID from the NiceID
     *
     * @param string $niceID
     *
     * @return string The salt
     */
    private function getSaltChar($niceID)
    {

        // Return the last char in the ID
        return mb_substr($niceID, -1);
    }

    /**
     * Get the NiceID from a salted NiceID
     *
     * @param string $niceID The salted NiceID
     *
     * @return string The un-salted NiceID
     */
    private function getNiceID($niceID)
    {

        // Return all but the last char in the ID
        return mb_substr($niceID, 0, -1);
    }
}
