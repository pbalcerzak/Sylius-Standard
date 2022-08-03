<?php

namespace App\Checker;

use Sylius\Component\Core\Model\CustomerInterface;

interface TrustedCustomerCheckerInterface
{
    public function isTrusted(CustomerInterface $customer): bool;
}
