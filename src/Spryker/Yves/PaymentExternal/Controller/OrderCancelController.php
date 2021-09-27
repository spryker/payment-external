<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\PaymentExternal\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderCancelRequestTransfer;
use Generated\Shared\Transfer\OrderCancelResponseTransfer;
use Generated\Shared\Transfer\OrderFilterTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Spryker\Yves\Kernel\View\View;
use Spryker\Yves\PaymentExternal\Dependency\Client\PaymentExternalToCartClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Yves\PaymentExternal\PaymentExternalFactory getFactory()
 * @method \Spryker\Client\PaymentExternal\PaymentExternalClientInterface getClient()
 */
class OrderCancelController extends AbstractController
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ORDER_CANCELLED = 'payment_external.order.cancelled';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_ORDER_REFERENCE = 'orderReference';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request): View
    {
        $customerClient = $this->getFactory()->getCustomerClient();
        $cartClient = $this->getFactory()->getCartClient();

        $orderReference = $this->getOrderReference($request);
        $customerTransfer = $customerClient->getCustomer();

        $orderTransfer = $this->getOrderTransfer(
            $cartClient,
            $orderReference,
            $customerTransfer
        );

        $orderCancelRequestTransfer = (new OrderCancelRequestTransfer())
            ->setCustomer($customerTransfer)
            ->setIdSalesOrder($orderTransfer->getIdSalesOrder());

        $orderCancelResponseTransfer = $this->getClient()->cancelOrder($orderCancelRequestTransfer);

        $this->handleResponseErrors($orderCancelResponseTransfer);

        if ($orderCancelResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_ORDER_CANCELLED);
            $customerClient->markCustomerAsDirty();
            $cartClient->clearQuote();
        }

        return $this->view([], [], '@PaymentExternal/views/order-cancel/index.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return string
     */
    protected function getOrderReference(Request $request): string
    {
        $orderReference = (string)$request->query->get(static::REQUEST_PARAMETER_ORDER_REFERENCE);

        if (!$orderReference) {
            throw new NotFoundHttpException();
        }

        return $orderReference;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderCancelResponseTransfer $orderCancelResponseTransfer
     *
     * @return void
     */
    protected function handleResponseErrors(OrderCancelResponseTransfer $orderCancelResponseTransfer): void
    {
        foreach ($orderCancelResponseTransfer->getMessages() as $messageTransfer) {
            if ($messageTransfer->getValue()) {
                $this->addErrorMessage($messageTransfer->getValueOrFail());
            }
        }
    }

    /**
     * @param \Spryker\Yves\PaymentExternal\Dependency\Client\PaymentExternalToCartClientInterface $cartClient
     * @param string $orderReference
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer(
        PaymentExternalToCartClientInterface $cartClient,
        string $orderReference,
        ?CustomerTransfer $customerTransfer = null
    ): OrderTransfer {
        if (!$customerTransfer) {
            return $this->getGuestOrderTransfer(
                $cartClient->getQuote(),
                $orderReference
            );
        }

        return $this->getCustomerOrderTransfer(
            $orderReference,
            $customerTransfer->getCustomerReferenceOrFail()
        );
    }

    /**
     * @param string $orderReference
     * @param string $customerReference
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getCustomerOrderTransfer(
        string $orderReference,
        string $customerReference
    ): OrderTransfer {
        $orderTransfer = (new OrderTransfer())
            ->setOrderReference($orderReference)
            ->setCustomerReference($customerReference);

        $orderTransfer = $this->getFactory()->getSalesClient()
            ->getCustomerOrderByOrderReference($orderTransfer);

        if (!$orderTransfer->getIdSalesOrder()) {
            throw new NotFoundHttpException();
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $orderReference
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getGuestOrderTransfer(
        QuoteTransfer $quoteTransfer,
        string $orderReference
    ): OrderTransfer {
        if ($quoteTransfer->getOrderReference() !== $orderReference) {
            throw new NotFoundHttpException();
        }

        $orderFilterTransfer = (new OrderFilterTransfer())
            ->setOrderReference($orderReference);

        $orderTransfer = $this->getClient()->getGuestOrder($orderFilterTransfer);

        if (!$orderTransfer->getIdSalesOrder()) {
            throw new NotFoundHttpException();
        }

        return $orderTransfer;
    }
}
