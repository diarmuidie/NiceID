<?php


namespace Diarmuidie\NiceID\Tests\Utilities;

use Diarmuidie\NiceID\Utilities;

/**
 * Class NiceIDTest
 * @package Diarmuidie\NiceID\Tests
 */
class FisherYatesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array Test unshuffled array
     */
    private $unShuffledArray = ['a','b','c','d','e','f'];

    /**
     * @var array Test shuffled array
     */
    private $shuffledtestArray = ['a','c','e','f','b','d'];

    /**
     * @var string Test shuffle secret
     */
    private $testSecret = 'My Test Secret';

    /**
     * Test shuffling
     */
    public function testShuffleArray()
    {

        $shuffled = Utilities\FisherYates::shuffle($this->unShuffledArray, $this->testSecret);

        $this->assertEquals($shuffled, $this->shuffledtestArray);

    }

    /**
     * Test unshuffling
     */
    public function testUnShuffleArray()
    {

        $unshuffled = Utilities\FisherYates::unshuffle($this->shuffledtestArray, $this->testSecret);

        $this->assertEquals($unshuffled, $this->unShuffledArray);

    }
}
