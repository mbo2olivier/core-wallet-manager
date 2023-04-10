<?php
namespace Mukadi\Wallet\Core;

class ProcessingEntry {

    public function __construct(
        public EntryInterface $entry,
        public WalletInterface $wallet,
    )
    {
        
    }
}