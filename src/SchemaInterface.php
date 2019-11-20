<?php
/**
 * This file is part of the mukadi/core-wallet-manager
 * (c) 2019 Genius Conception
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mukadi\Wallet\Core;
/**
 * Interface SchemaInterface.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
interface SchemaInterface
{
    /**
     * @return string
     */
    public function getSchemaId();
    /**
     * @param string $id
     */
    public function setSchemaId($id);
    /**
     * @return string
     */
    public function getPlatformId();
    /**
     * @param string $pif
     */
    public function setPlatformId($pif);
    /**
     * @return string
     */
    public function getCode();
    /**
     * @param string $code
     */
    public function setCode($code);
}
