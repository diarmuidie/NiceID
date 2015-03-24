<?php


namespace Diarmuidie\NiceID\Tests;

use Diarmuidie\NiceID\NiceID;

/**
 * Class NiceIDTest
 * @package Diarmuidie\NiceID\Tests
 */
class NiceIDTest extends \PHPUnit_Framework_TestCase
{
    private $niceid;

    /**
     *
     */
    public function setUp()
    {
        $this->niceid = new NiceID();
    }

    /**
     * Test encoding of IDs
     *
     * @param $int
     *
     * @dataProvider encodeDecodeProvider
     */
    public function testEncodesID($int)
    {

        $encoded = $this->niceid->encode($int);
        $decodedInt = $this->niceid->decode($encoded);

        $this->assertEquals($int, $decodedInt);

    }

    /**
     * Test Decoding of IDs
     *
     * @param $int
     * @param $niceID
     *
     * @dataProvider encodeDecodeProvider
     */
    public function testDecodesID($int, $niceID)
    {

        $decoded = $this->niceid->decode($niceID);
        $this->assertEquals($int, $decoded);

    }

    /**
     * Test changing the secret changes the encoding
     */
    public function testSecret() {

        $testSecret = 'A secret';
        $this->niceid->setSecret($testSecret);

        $int = 12;
        $encoded = $this->niceid->encode($int);

        // Set the incorrect secret
        $this->niceid->setSecret('');
        $decodedInt = $this->niceid->decode($encoded);

        $this->assertNotEquals($decodedInt, $int);

        // Change the secret back
        $this->niceid->setSecret($testSecret);
        $decodedInt = $this->niceid->decode($encoded);

        $this->assertEquals($decodedInt, $int);

    }

    /**
     * Test changing the secret in the constructor changes the encoding
     */
    public function testSecretConstructor() {

        $testSecret = 'A secret';

        $niceid = new NiceID($testSecret);

        $int = 12;
        $encoded = $niceid->encode($int);

        // Set the incorrect secret
        $niceid->setSecret('');
        $decodedInt = $niceid->decode($encoded);

        $this->assertNotEquals($decodedInt, $int);

        // Change the secret back
        $niceid->setSecret($testSecret);
        $decodedInt = $niceid->decode($encoded);

        $this->assertEquals($decodedInt, $int);

    }

    /**
     * Test setting and getting the characters works
     */
    public function testSettingCharacters() {

        $testCharacters = 'a';
        $this->niceid->setCharacters($testCharacters);

        $characters = $this->niceid->getCharacters();

        $this->assertEquals($testCharacters, $characters);

    }

    /**
     * Test setting and getting the minLength works
     */
    public function testSettingMinLength() {

        $testMinLength = 2;
        $this->niceid->setMinLength($testMinLength);

        $minLength = $this->niceid->getMinLength();

        $this->assertEquals($testMinLength, $minLength);

    }

    /**
     * Test setting a minLength changes the length of the encoded string
     *
     * @param $minLength
     *
     * @dataProvider minLengthProvider
     */
    public function testMinLength($minLength) {

        $this->niceid->setMinLength($minLength);

        $encoded = $this->niceid->encode(10);

        $this->assertGreaterThanOrEqual($minLength, strlen($encoded));

    }

    /**
     * @return array
     */
    public function encodeDecodeProvider()
    {
        return array(
            array(0, 'qcccc'),
            array(1, 'rTTrQ'),
            array(987347623, 'r8LgCJQ'),
        );
    }

    /**
     * @return array
     */
    public function minLengthProvider()
    {
        return array(
            array(1),
            array(2),
            array(3),
            array(10),
        );
    }
}
