<?php

declare(strict_types=1);

namespace App\Notifier;

use App\Entity\SupplierInterface;

interface SupplierTrustNotifierInterface
{
        public function notify(SupplierInterface $supplier): void;
}
