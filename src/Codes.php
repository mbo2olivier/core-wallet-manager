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

    const OPERATION_STATUS_INIT = "I";
    const OPERATION_STATUS_SUCCESS = "S";
    const OPERATION_STATUS_AUTHORIZED = "A";
    const OPERATION_STATUS_UNAUTHORIZED = "U";
    const OPERATION_STATUS_ERROR = "E";

    const AUTH_STATUS_PENDING = "P";
    const AUTH_STATUS_FINALIZED = "F";
    const AUTH_STATUS_REVERSED = "R";

    const AUTH_TYPE_DEBIT = 'D';
    const AUTH_TYPE_REVERSE = 'R';
}
