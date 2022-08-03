<?php

declare(strict_types=1);

namespace App\ShippingCalculator;

use Sylius\Component\Core\Exception\MissingChannelConfigurationException;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface as BaseShipmentInterface;
use Webmozart\Assert\Assert;

class WeightBasedShippingCalculator implements CalculatorInterface
{

    /**
     * @param ShipmentInterface $subject
     */
    public function calculate(BaseShipmentInterface $subject, array $configuration): int
    {
        Assert::isInstanceOf($subject, ShipmentInterface::class);

        $channelCode = $subject->getOrder()->getChannel()->getCode();

        if (!isset($configuration[$channelCode]['weight'])) {
            throw new MissingChannelConfigurationException(sprintf(
                'Channel %s has no weight defined for shipping method %s',
                $subject->getOrder()->getChannel()->getName(),
                $subject->getMethod()->getName(),
            ));
        }

        if (!isset($configuration[$channelCode]['above_or_equal'])) {
            throw new MissingChannelConfigurationException(sprintf(
                'Channel %s has no above or equal price defined for shipping method %s',
                $subject->getOrder()->getChannel()->getName(),
                $subject->getMethod()->getName(),
            ));
        }

        if (!isset($configuration[$channelCode]['below'])) {
            throw new MissingChannelConfigurationException(sprintf(
                'Channel %s has no below price defined for shipping method %s',
                $subject->getOrder()->getChannel()->getName(),
                $subject->getMethod()->getName(),
            ));
        }

        $totalWeight = 0.0;

        /** @var OrderItemInterface $item */
        foreach ($subject->getOrder()->getItems() as $item) {
            $totalWeight += $item->getVariant()->getWeight() * $item->getQuantity();
        }

        if($totalWeight >= $configuration[$channelCode]['weight'])
        {
            return $configuration[$channelCode]['above_or_equal'];
        }

        return $configuration[$channelCode]['below'];
    }

    public function getType(): string
    {
        return 'weight_based';
    }
}
