<?php
namespace Mukadi\Wallet\Core\Test;

use Mukadi\Wallet\Core\AuthorizationInterface;
use Mukadi\Wallet\Core\BatchInterface;

class Batch implements BatchInterface {

    public ?Authorization $auth;
    public array $entries;

    public function isDoubleEntry(): bool
    {
        return true;
    }

    public function buildAuthorization(): AuthorizationInterface
    {
        return $this->auth;
    }

    public function getEntries(): iterable
    {
        return $this->entries;
    }
}