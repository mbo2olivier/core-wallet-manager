<?php
namespace Mukadi\Wallet\Core\Test;

use Mukadi\Wallet\Core\Manager\AbstractWalletManager;
use Mukadi\Wallet\Core\AuthorizationInterface;
use Mukadi\Wallet\Core\WalletInterface;
use Mukadi\Wallet\Core\OperationInterface;

class WalletManager extends AbstractWalletManager {

    /**
     * @param OperationInterface $op 
     * @return OperationInterface
     * @throws OperationException
     */
    public function beforeExecuteOperation(OperationInterface $op) { return $op; }

    /**
     * @param OperationInterface $op 
     * @return OperationInterface
     */
    public function afterExecuteOperation(OperationInterface $op) { return $op; }

    /**
     * @param WalletInterface $wallet 
     * @return WalletInterface
     * @throws WalletException
     */
    public function beforeOpenWallet(WalletInterface $wallet) { return $wallet; }

    /**
     * @param WalletInterface $wallet 
     * @return WalletInterface
     */
    public function afterOpenWallet(WalletInterface $wallet) { return $wallet; }

    /**
     * @param WalletInterface $wallet 
     * @return WalletInterface
     * @throws WalletException
     */
    public function beforeCloseWallet(WalletInterface $wallet) { return $wallet; }

    /**
     * @param WalletInterface $wallet 
     * @return WalletInterface
     */
    public function afterCloseWallet(WalletInterface $wallet) { return $wallet; }

    /**
     * Generate an identifier for the given operation
     * 
     * @param OperationInterface $op
     * @return string
     */
    public function generateOperationIdFor(OperationInterface $op) { return $op; }

    /**
     * generate a new free authorization identifier
     * 
     * @return string
     */
    public function getNextAuthorizationId() { return 'A0001'; }

    /**
     * @param WalletInterface $wallet
     * @return string
     */
    public function generateWalletIdFor(WalletInterface $wallet) { return 'WA0001'; }

    /**
     * @param AuthorizationInterface $auth
     * @return AuthorizationInterface
     */
    public function beforeAuthorizationRedemption(AuthorizationInterface $auth) { return $auth; }

    /**
     * @param AuthorizationInterface $auth
     * @return AuthorizationInterface
     */
    public function afterAuthorizationRedemption(AuthorizationInterface $auth) { return $auth; }

    /**
     * @param AuthorizationInterface $auth
     * @return AuthorizationInterface
     */
    public function beforeAuthorizationReversal(AuthorizationInterface $auth) { return $auth; }

    /**
     * @param AuthorizationInterface $auth
     * @return AuthorizationInterface
     */
    public function afterAuthorizationReversal(AuthorizationInterface $auth) { return $auth; }
}