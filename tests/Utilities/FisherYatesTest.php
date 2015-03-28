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
    private $unShuffledArray = array('a','b','c','d','e','f');

    /**
     * @var array Test shuffled array
     */
    private $shuffledArray = array('a','c','e','f','b','d');

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

        $this->assertEquals($shuffled, $this->shuffledArray);

    }

    /**
     * Test un-shuffling
     */
    public function testUnShuffleArray()
    {

        $unShuffled = Utilities\FisherYates::unshuffle($this->shuffledArray, $this->testSecret);

        $this->assertEquals($unShuffled, $this->unShuffledArray);

    }
}
