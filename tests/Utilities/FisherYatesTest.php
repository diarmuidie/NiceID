<?php

/**
 * This file is part of Diarmuidie\NiceID.
 *
 * (c) Diarmuid <hello@diarmuid.ie>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Diarmuidie\NiceID\Tests\Utilities;

use Diarmuidie\NiceID\Utilities;

/**
 * Class NiceIDTest
 *
 * @category  test
 * @package   Diarmuidie\NiceID
 * @author    Diarmuid <hello@diarmuid.ie>
 * @copyright 2015 Diarmuid
 * @license   http://www.opensource.org/licenses/MIT The MIT License
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
