<?php
namespace Mukadi\Wallet\Core\Test;

use Mukadi\Wallet\Core\WalletInterface;
use Mukadi\Wallet\Core\Manager\AbstractWalletManager;

class WalletManager extends AbstractWalletManager {

    protected function generateWalletIdFor(WalletInterface $wallet): string
    {
        return "WA01";
    }

    protected function getNextAuthorizationId(): string
    {
        return "001";
    }

}