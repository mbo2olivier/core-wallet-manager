<?php
/**
 * This file is part of the mukadi/core-wallet-manager
 * (c) 2019 Genius Conception
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mukadi\Wallet\Core\Storage;

use Mukadi\Wallet\Core\InstructionInterface;
/**
 * Class SchemaStorageLayer.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
abstract class SchemaStorageLayer
{
    /**
     * @param string $code;
     * @return InstructionInterface[]
     */
    public abstract function getInstructions($code);
}
