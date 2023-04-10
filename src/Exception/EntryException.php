<?php
/**
 * This file is part of the mukadi/core-wallet-manager
 * (c) 2019 Genius Conception
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mukadi\Wallet\Core\Exception;

use Mukadi\Wallet\Core\EntryInterface;
/**
 * Class EntryException.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
class EntryException extends \Exception 
{
    /** @var EntryInterface|null $operation */
    protected ?EntryInterface $operation;

    public function __construct($message, EntryInterface $operation = null) {
        parent::__construct($message);
        $this->operation = $operation;
    }

    /**
     * Get the value of operation
     * @return EntryInterface
     */ 
    public function getOperation(): ?EntryInterface
    {
        return $this->operation;
    }
}