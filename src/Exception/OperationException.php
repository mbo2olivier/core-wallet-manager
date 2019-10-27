<?php
/**
 * This file is part of the mukadi/core-wallet-manager
 * (c) 2019 Genius Conception
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mukadi\Wallet\Core\Exception;

use Mukadi\Wallet\Core\OperationInterface;
/**
 * Class OperationException.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
class OperationException extends \Exception 
{
    /** @var OperationInterface $operation */
    protected $operation;

    public function __construct($message, OperationInterface $operation = null) {
        parent::__construct($message);
        $this->operation = $operation;
    }

    /**
     * Get the value of operation
     * @return OperationInterface
     */ 
    public function getOperation()
    {
        return $this->operation;
    }
}