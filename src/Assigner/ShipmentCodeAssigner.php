<?php

declare(strict_types=1);

namespace App\Assigner;

use App\Provider\ShipmentCodeProviderInterface;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Core\Model\ShipmentInterface;

final class ShipmentCodeAssigner implements ShipmentCodeAssignerInterface
{
    private ShipmentCodeProviderInterface $shipmentCodeProvider;
    private ObjectManager $manager;

    public function __construct(ShipmentCodeProviderInterface $shipmentCodeProvider, ObjectManager $manager)
    {
        $this->shipmentCodeProvider = $shipmentCodeProvider;
        $this->manager = $manager;
    }

    public function assign(ShipmentInterface $shipment): void
    {
        if($shipment->getState() !== ShipmentInterface::STATE_SHIPPED) {
            return;
        }

        $shipment->setTracking($this->shipmentCodeProvider->provide($shipment));
        $this->manager->flush();
    }
}
