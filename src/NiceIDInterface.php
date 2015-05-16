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

/**
 * NiceId Interface
 *
 * @package   Diarmuidie\NiceID
 * @author    Diarmuid <hello@diarmuid.ie>
 * @copyright 2015 Diarmuid
 * @license   http://www.opensource.org/licenses/MIT The MIT License
 */
interface NiceIDInterface
{

    /**
     * @param null|string $secret Optionally provide a secret to use when encoding/decoding
     *
     * @return null
     */
    public function __construct($secret = null);

    /**
     * Encode a ID into a NiceID
     *
     * @param integer $id
     *
     * @return string the encoded ID
     */
    public function encode($id);

    /**
     * Decode a NiceID
     *
     * @param string $niceId
     *
     * @return integer The decoded ID
     */
    public function decode($niceId);

    /**
     * Get the maximum allowed value for the ID
     *
     * @return int the maximum value of ID
     */
    public function getMaxID();
}
