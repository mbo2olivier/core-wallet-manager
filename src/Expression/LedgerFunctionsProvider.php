<?php
namespace Mukadi\Wallet\Core\Expression;

use Mukadi\Wallet\Core\Exception\WalletException;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class LedgerFunctionsProvider implements ExpressionFunctionProviderInterface {
    
    public function getFunctions(): array
    {
        return [
            new ExpressionFunction('publicId', 
            fn () => ''
            , function ($arguments, string $id): string {
                if (!isset($arguments['storage'])) {
                    throw new \RuntimeException('A WalletStorageLayer is required.');
                }

                /** @var \Mukadi\Wallet\Core\Storage\WalletStorageLayer $storage */
                $storage = $arguments['storage'];

                $wallet  = $storage->findWalletBy(['walletPublicId' => $id]);

                if (null === $wallet) {
                    throw new WalletException(sprintf('the wallet with public Id: %s doesn\'t exist', $id));
                }

                return $wallet->getWalletId();
            }),
        ];
    }
}