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
    public function getSchemaId(): string;
    /**
     * @param string $id
     */
    public function setSchemaId(string $id);
    /**
     * @return string
     */
    public function getPlatformId(): string;
    /**
     * @param string $pif
     */
    public function setPlatformId(string $pif);
    /**
     * @return string
     */
    public function getCode(): string;
    /**
     * @param string $code
     */
    public function setCode(string $code);
}
