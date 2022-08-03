<?php

declare(strict_types=1);

namespace App\Applicator;

use App\Entity\Supplier;
use SM\Factory\FactoryInterface;

final class TrustSupplierApplicator
{
    private FactoryInterface $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function __invoke(Supplier $data): Supplier
    {
        $stateMachine = $this->factory->get($data, 'app_supplier');

        $stateMachine->apply('trust');

        return $data;
    }
}
