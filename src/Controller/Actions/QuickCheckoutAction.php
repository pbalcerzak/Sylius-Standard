<?php

declare(strict_types=1);

namespace App\Controller\Actions;

use Doctrine\Persistence\ObjectManager;
use SM\Factory\FactoryInterface as StateMachineFactoryInterface;
use Sylius\Component\Core\Context\ShopperContextInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\OrderCheckoutTransitions;
use Sylius\Component\Core\Repository\ProductVariantRepositoryInterface;
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class QuickCheckoutAction
{
    private ProductVariantRepositoryInterface $productVariantRepository;

    private FactoryInterface $orderFactory;

    private FactoryInterface $orderItemFactory;

    private OrderItemQuantityModifierInterface $orderItemQuantityModifier;

    private ShopperContextInterface $shopperContext;

    private StateMachineFactoryInterface $stateMachineFactory;

    private ObjectManager $orderManager;

    private UrlGeneratorInterface $router;

    public function __construct(ProductVariantRepositoryInterface $productVariantRepository, FactoryInterface $orderFactory, FactoryInterface $orderItemFactory, OrderItemQuantityModifierInterface $orderItemQuantityModifier, ShopperContextInterface $shopperContext, StateMachineFactoryInterface $stateMachineFactory, ObjectManager $orderManager, UrlGeneratorInterface $router)
    {
        $this->productVariantRepository = $productVariantRepository;
        $this->orderFactory = $orderFactory;
        $this->orderItemFactory = $orderItemFactory;
        $this->orderItemQuantityModifier = $orderItemQuantityModifier;
        $this->shopperContext = $shopperContext;
        $this->stateMachineFactory = $stateMachineFactory;
        $this->orderManager = $orderManager;
        $this->router = $router;
    }


    public function __invoke(Request $request, int $variantId): Response
    {
        /** @var ProductVariantInterface $variant */
        $variant = $this->productVariantRepository->find($variantId);
        /** @var OrderInterface $order */
        $order = $this->orderFactory->createNew();
        /** @var OrderItemInterface $orderItem */
        $orderItem = $this->orderItemFactory->createNew();
        $orderItem->setVariant($variant);

        $this->orderItemQuantityModifier->modify($orderItem, 1);

        $order->addItem($orderItem);

        /** @var CustomerInterface $customer */
        $customer = $this->shopperContext->getCustomer();
        $order->setCustomer($customer);
        $order->setShippingAddress($customer->getDefaultAddress());
        $order->setBillingAddress($customer->getDefaultAddress());

        $order->setChannel($this->shopperContext->getChannel());
        $order->setLocaleCode($this->shopperContext->getLocaleCode());
        $order->setCurrencyCode($this->shopperContext->getCurrencyCode());

        $stateMachine = $this->stateMachineFactory->get($order, OrderCheckoutTransitions::GRAPH);
        $stateMachine->apply(OrderCheckoutTransitions::TRANSITION_ADDRESS);
        $stateMachine->apply(OrderCheckoutTransitions::TRANSITION_SELECT_SHIPPING);
        $stateMachine->apply(OrderCheckoutTransitions::TRANSITION_SELECT_PAYMENT);
        $stateMachine->apply(OrderCheckoutTransitions::TRANSITION_COMPLETE);

        $this->orderManager->persist($order);
        $this->orderManager->flush();

        return new RedirectResponse($this->router->generate('sylius_shop_homepage'));
    }

}
