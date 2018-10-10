<?php

/**
 * This file is part of Diarmuidie\NiceID.
 *
 * (c) Diarmuid <hello@diarmuid.ie>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Diarmuidie\NiceID\Tests;

use Diarmuidie\NiceID\NiceID;
use PHPUnit\Framework\TestCase;

/**
 * Class NiceIDTest
 *
 * @category  test
 * @package   Diarmuidie\NiceID
 * @author    Diarmuid <hello@diarmuid.ie>
 * @copyright 2015 Diarmuid
 * @license   http://www.opensource.org/licenses/MIT The MIT License
 */
class NiceIDTest extends TestCase
{
    private $niceid;

    /**
     * Initialise a new NiceId instance for each test
     */
    protected function setUp()
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
    public function testSecret()
    {

        $testSecret = 'A secret';
        $this->niceid->setSecret($testSecret);

        $this->assertAttributeEquals($testSecret, 'secret', $this->niceid);

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
    public function testSecretConstructor()
    {

        $testSecret = 'A secret';

        $niceid = new NiceID($testSecret);

        $this->assertAttributeEquals($testSecret, 'secret', $niceid);

    }

    /**
     * Test setting and getting the characters works
     */
    public function testSettingCharacters()
    {

        $testCharacters = 'ab';
        $this->niceid->setCharacters($testCharacters);
        $this->niceid->setMinLength(0);

        $this->assertAttributeEquals($testCharacters, 'characters', $this->niceid);

        $encoded = $this->niceid->encode(1);

        $this->assertEquals(mb_strlen($encoded), 2);
        $this->assertContains('a', $encoded);
        $this->assertContains('b', $encoded);

    }

    /**
     * Test setting a minLength changes the length of the encoded string
     *
     * @param $minLength
     *
     * @dataProvider minLengthProvider
     */
    public function testMinLength($minLength)
    {

        $this->niceid->setMinLength($minLength);

        $this->assertAttributeEquals($minLength, 'minLength', $this->niceid);

        $encoded = $this->niceid->encode(10);

        $this->assertGreaterThanOrEqual($minLength, strlen($encoded));

    }

    /**
     * Test encoding a integer that is larger than the PHP_MAX_INT throws an exception
     */
    public function testMaxLengthOverflowException()
    {

        // Work out the max length based on the PHP_INT_MAX of this system
        $length = (log10(PHP_INT_MAX))/(log10(63));

        $over = ceil($length);
        $under = floor($length);

        // No exception
        $this->niceid->setMinLength($under + 2);
        $this->niceid->encode(1);

        $this->expectException('LengthException');

        // Length Exception
        $this->niceid->setMinLength($over + 2);
        $this->niceid->encode(1);

    }

    /**
     * Test encoding anything but an int throws an exception
     */
    public function testNotIntEncodeException()
    {

        $this->expectException('InvalidArgumentException');
        $this->niceid->encode('1');

    }

    /**
     * Test decoding anything but a scalar throws an exception
     */
    public function testNotScalarDecodeException()
    {

        $this->expectException('InvalidArgumentException');
        $this->niceid->decode(array());

    }

    /**
     * @return array
     */
    public function encodeDecodeProvider()
    {
        return array(
            array(0, 'siiig'),
            array(1, 'e2BB2'),
            array(987347623, 's27HCI'),
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
