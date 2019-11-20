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
 * Class Reversal.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
class Reversal  
{
    /**
     * @var string
     */
    private $previousAuthId;
    /**
     * @var string
     */
    private $maker;

    /**
     * @return string
     */
    public function getMaker()
    {
        return $this->maker;
    }

    /**
     * @param string $maker
     */
    public function setMaker($maker)
    {
        $this->maker = $maker;
    }

    /**
     * @return string
     */
    public function getPreviousAuthId()
    {
        return $this->previousAuthId;
    }

    /**
     * @param string $previousAuthId
     */
    public function setPreviousAuthId($previousAuthId)
    {
        $this->previousAuthId = $previousAuthId;
    }


}
