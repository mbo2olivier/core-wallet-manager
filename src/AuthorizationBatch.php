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
 * Class AuthorizationBatch.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
final class AuthorizationBatch  implements BatchInterface
{
    protected bool $_isDoubleEntry;
    /** @var array<Entry>  */
    protected iterable $_entries;

    public function __construct(private AuthorizationInterface $auth, iterable $entries,bool $isDoubleEntry = true)
    {
        $this->_isDoubleEntry = $isDoubleEntry;
        $this->_entries = $entries;
    }

    public function isDoubleEntry(): bool
    {
        return $this->_isDoubleEntry;
    }

    public function getEntries(): iterable
    {
        return $this->_entries;
    }

    public function buildAuthorization(): AuthorizationInterface
    {
        return $this->auth;
    }
}
