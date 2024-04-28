<?php
namespace Mukadi\Wallet\Core\Test;

use Mukadi\Wallet\Core\WalletInterface;
use Mukadi\Wallet\Core\Codes;
use Mukadi\Wallet\Core\OperationExecutionIntent;
use Mukadi\Wallet\Core\Manager\AbstractWalletManager;

class MinimuBalanceWalletManager extends AbstractWalletManager {

    public function __construct(\Mukadi\Wallet\Core\Manager\AbstractSchemaManager $schema, \Mukadi\Wallet\Core\Storage\WalletStorageLayer $storage, $authClass, private string $minimumBalance) {
        parent::__construct($schema, $storage, $authClass);
    }

    protected function generateWalletIdFor(WalletInterface $wallet): string
    {
        return "WA01";
    }

    protected function getNextAuthorizationId(): string
    {
        return "001";
    }

    protected function beforeExecuteOperation(OperationExecutionIntent $intent) {

        if ($intent->getEntry()->getType() === Codes::OPERATION_TYPE_CASH_OUT
            && $intent->getAvailableBalance() - $intent->getEntry()->getAmount() < $this->minimumBalance) {
                
                throw new \Mukadi\Wallet\Core\Exception\EntryException('insufficent balance', $intent->getEntry());
            }
    }

}