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
 * Interface PlatformInterface.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
interface PlatformInterface
{
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
    public function getLabel(): string;
    /**
     * @param string $label
     */
    public function setLabel(string $label);
    /**
     * @return string
     */
    public function getMode(): string;
    /**
     * @param string $label
     */
    public function setMode(string $label);
}
