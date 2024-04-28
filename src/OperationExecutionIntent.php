<?php
namespace Mukadi\Wallet\Core;

class OperationExecutionIntent {

    public function __construct(
        private EntryInterface $entry,
        private WalletInterface $wallet,
        private string $availableBalance,
        /** @var Lien[] */
        private array $activeLiens = [], 
    ) {

    }

        /**
         * Get the value of operation
         */ 
        public function getEntry(): EntryInterface
        {
                return $this->entry;
        }

        /**
         * Get the value of wallet
         */ 
        public function getWallet(): WalletInterface
        {
                return $this->wallet;
        }

        /**
         * Get the value of availableBalance
         */ 
        public function getAvailableBalance(): string
        {
                return $this->availableBalance;
        }

        /**
         * Get the value of activeLiens
         * @return Lien[]
         */ 
        public function getActiveLiens(): array
        {
                return $this->activeLiens;
        }
}