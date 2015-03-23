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
}
