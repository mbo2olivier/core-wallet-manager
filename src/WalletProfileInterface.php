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
 * Interface WalletProfileInterface.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
interface WalletProfileInterface
{
    /**
     * @return string
     */
    public function getCode(): string;
    /**
     * @param string $code
     */
    public function setCode(string $code);
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
    public function getPlatformId(): string;
    /**
     * @param string $pif
     */
    public function setPlatformId(string $pif);
}
