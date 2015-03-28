<?php
/**
 * User: diarmuid <hello@diarmuid.ie>
 * Date: 21/03/15
 */

namespace Diarmuidie\NiceID;

use Diarmuidie\NiceID\Utilities\BaseConvert;
use Diarmuidie\NiceID\Utilities\FisherYates;

/**
 * Class NiceID
 * @package Diarmuidie\NiceID
 */
class NiceID
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
     * Get the maximum allowed value of the ID
     *
     * @return int the maximum value of the ID
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
     * @return string The encoded ID
     */
    public function encode($id)
    {

        // Check the ID is within bounds
        if ($id > $this->getMaxID()) {
            throw new \LengthException('The provided ID is greater than the maximum allowed ID');
        }

        $characters = $this->characters;

        // Split characters string into array
        $charactersArray = BaseConvert::mbStrSplit($characters);

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

        return $niceId;

    }

    /**
     * Decode a NiceId into an integer
     *
     * @param string $niceId
     * @return int
     */
    public function decode($niceId)
    {

        $characters = $this->characters;

        // Split characters string into array
        $charactersArray = BaseConvert::mbStrSplit($characters);

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
     * @param string $characters The characters tring
     * @param int $minLength Min Length
     * @return number
     */
    private function minLengthAdder($characters, $minLength) {
        return pow(strlen($characters), $minLength - 2);
    }

    /**
     * Extract the salt ID from the NiceID
     * @param string $niceID
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
     * @return string The un-salted NiceID
     */
    private function getNiceID($niceID)
    {

        // Return all but the last char in the ID
        return mb_substr($niceID, 0, -1);

    }
}
