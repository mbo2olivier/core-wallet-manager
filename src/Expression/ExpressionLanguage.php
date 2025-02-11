<?php
namespace Mukadi\Wallet\Core\Expression;
use Psr\Cache\CacheItemPoolInterface;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage as Base;

class ExpressionLanguage extends Base {

    public function __construct(?CacheItemPoolInterface $cache = null, array $providers = [])
    {
        // prepends the default provider to let users override it
        array_unshift($providers, new LedgerFunctionsProvider());

        parent::__construct($cache, $providers);
    }
}