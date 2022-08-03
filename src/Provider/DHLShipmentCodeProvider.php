<?php

declare(strict_types=1);

namespace App\Provider;

use Sylius\Component\Core\Model\ShipmentInterface;

final class DHLShipmentCodeProvider implements ShipmentCodeProviderInterface
{
    public function provide(ShipmentInterface $shipment): string
    {
        return '123123123';
    }
}
