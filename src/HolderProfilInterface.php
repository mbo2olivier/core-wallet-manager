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
 * Class HolderProfilInterface.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
interface HolderProfilInterface  
{
    /**
     * @return string
     */
    public function getCode();
    /**
     * @param string $code
     */
    public function setCode($code);
    /**
     * @return string
     */
    public function getLabel();
    /**
     * @param string $label
     */
    public function setLabel($label);
    /**
     * @return string
     */
    public function getPlatformId();
    /**
     * @param string $pif
     */
    public function setPlatformId($pif);
}
