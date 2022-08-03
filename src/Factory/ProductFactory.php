<?php

declare(strict_types=1);

namespace App\Factory;

use Sylius\Component\Product\Factory\ProductFactoryInterface as BaseProductFactoryInterface;
use Sylius\Component\Product\Model\ProductInterface;

class ProductFactory implements ProductFactoryInterface
{

    private BaseProductFactoryInterface $baseProductFactory;

    public function __construct(BaseProductFactoryInterface $baseProductFactory)
    {
        $this->baseProductFactory = $baseProductFactory;
    }

    public function createNew()
    {
        return $this->baseProductFactory->createNew();
    }

    public function createTShirt(): ProductInterface
    {
        $product = $this->createWithVariant();
        $product->setCode('T-Shirt-' . (new \DateTime())->getTimestamp());

        return $product;
    }

    public function createWithVariant(): ProductInterface
    {
        return $this->baseProductFactory->createWithVariant();
    }
}
