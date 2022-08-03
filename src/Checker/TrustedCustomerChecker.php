<?php

declare(strict_types=1);

namespace App\Checker;

use Sylius\Component\Core\Model\CustomerInterface;

final class TrustedCustomerChecker implements TrustedCustomerCheckerInterface
{
    public function isTrusted(CustomerInterface $customer): bool
    {
        return $customer->getGroup() && $customer->getGroup()->getCode() == 'TRUSTED';
    }
}
