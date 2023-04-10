<?php
/**
 * This file is part of the mukadi/core-wallet-manager
 * (c) 2019 Genius Conception
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mukadi\Wallet\Core;
/**
 * Class Codes.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
class Codes 
{
    const SYSTEM_VALIDATOR = "SYSTEM";

    const OPERATION_TYPE_CASH_IN = "C";
    const OPERATION_TYPE_CASH_OUT = "D";

    const AUTH_STATUS_PENDING = "P";
    const AUTH_STATUS_ACCEPTED = "A";
    const AUTH_STATUS_REFUSED = "R";

    const PLATFORM_MODE_BANK= 'BANK';
    const PLATFORM_MODE_MERCHANT= 'MERCHANT';
    const PLATFORM_MODE_MARKETPLACE= 'MARKETPLACE';
    const PLATFORM_MODE_AGGREGATOR= 'AGGREGATOR';

    public static function getAvailableModes(): array {
        return [
            'Bank' => static::PLATFORM_MODE_BANK,
            'Merchant' => static::PLATFORM_MODE_MERCHANT,
            'Marketplace' => static::PLATFORM_MODE_MARKETPLACE,
            'Aggregator' => static::PLATFORM_MODE_AGGREGATOR,
        ];
    }
}
