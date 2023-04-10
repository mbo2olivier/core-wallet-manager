<?php
/**
 * This file is part of the mukadi/core-wallet-manager
 * (c) 2019 Genius Conception
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mukadi\Wallet\Core\Exception;

use Mukadi\Wallet\Core\AuthorizationInterface;

/**
 * Class AuthorizationException.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
class AuthorizationException extends \Exception 
{
    public function __construct(private AuthorizationInterface $auth, string $message, \Throwable $previuous = null)
    {
        parent::__construct($message, 0, $previuous);
    }

    public function getAuthorization(): AuthorizationInterface {
        return $this->auth;
    }
}
