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
class BaseConvertTest extends TestCase
{


    /**
     * Test converting to-from bases
     *
     * @param $fromNumber
     * @param $toNumber
     * @param $fromBase
     * @param $toBase
     *
     * @dataProvider conversionProvider
     */
    public function testBaseConversion($fromNumber, $toNumber, $fromBase, $toBase)
    {

        $convertedNumber = Utilities\BaseConvert::convert($fromNumber, $fromBase, $toBase);

        $this->assertEquals($toNumber, $convertedNumber);

    }

    /**
     * @return array
     */
    public function conversionProvider()
    {
        return array(
            array(8, 8, '0123456789', '0123456789'),
            array(8, 8, '012345678', '012345678'),
            array(180, 'b4', '0123456789', '0123456789abcdef'),
            array(14367, 'vgp', '0123456789', 'abcdefghijklmnopqrstuvwxyz'),
            array('g', 6, 'abcdefghi', '0123456789'),
            array(
                '70B1D707EAC2EDF4C6389F440C7294B51FFF57BB',
                '111000010110001110101110000011111101010110000101110110111110100110001100011100010011111010001000000110001110010100101001011010100011111111111110101011110111011',
                '0123456789ABCDEF',
                '01'
            )
        );
    }
}
