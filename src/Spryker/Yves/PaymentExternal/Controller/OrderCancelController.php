<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\PaymentExternal\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderCancelRequestTransfer;
use Generated\Shared\Transfer\OrderCancelResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Spryker\Yves\Kernel\View\View;
use Spryker\Yves\PaymentExternal\Dependency\Client\PaymentExternalToCustomerClientInterface;
use Spryker\Yves\PaymentExternal\Dependency\Client\PaymentExternalToSalesClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Yves\PaymentExternal\PaymentExternalFactory getFactory()
 */
class OrderCancelController extends AbstractController
{
    protected const GLOSSARY_KEY_ORDER_CANCELLED = 'payment_external.order.cancelled';
    protected const REQUEST_PARAMETER_ORDER_REFERENCE = 'orderReference';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request): View
    {
        $salesClient = $this->getFactory()->getSalesClient();
        $customerClient = $this->getFactory()->getCustomerClient();

        $customerTransfer = $this->getCustomerTransfer($customerClient);

        $orderTransfer = $this->getOrderTransfer(
            $this->getOrderReference($request),
            $customerTransfer->getCustomerReferenceOrFail(),
            $salesClient
        );

        $orderCancelRequestTransfer = (new OrderCancelRequestTransfer())
            ->setCustomer($customerTransfer)
            ->setIdSalesOrder($orderTransfer->getIdSalesOrder());

        $orderCancelResponseTransfer = $salesClient->cancelOrder($orderCancelRequestTransfer);

        $this->handleResponseErrors($orderCancelResponseTransfer);

        if ($orderCancelResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_ORDER_CANCELLED);
            $customerClient->markCustomerAsDirty();
            $this->getFactory()->getCartClient()->clearQuote();
        }

        return $this->view([], [], '@PaymentExternal/views/order-cancel/index.twig');
    }

    /**
     * @param \Spryker\Yves\PaymentExternal\Dependency\Client\PaymentExternalToCustomerClientInterface $customerClient
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomerTransfer(PaymentExternalToCustomerClientInterface $customerClient): CustomerTransfer
    {
        $customerTransfer = $customerClient->getCustomer();

        if (!$customerTransfer) {
            throw new NotFoundHttpException(
                'Only logged in customers are allowed to access this page'
            );
        }

        return $customerTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     *
     * @return string
     */
    protected function getOrderReference(Request $request): string
    {
        $orderReference = (string)$request->query->get(static::REQUEST_PARAMETER_ORDER_REFERENCE);

        if (!$orderReference) {
            throw new BadRequestHttpException(
                'Provide orderReference parameter'
            );
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
     * @param string $orderReference
     * @param string $customerReference
     * @param \Spryker\Yves\PaymentExternal\Dependency\Client\PaymentExternalToSalesClientInterface $salesClient
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer(
        string $orderReference,
        string $customerReference,
        PaymentExternalToSalesClientInterface $salesClient
    ): OrderTransfer {
        $orderTransfer = (new OrderTransfer())
            ->setOrderReference($orderReference)
            ->setCustomerReference($customerReference);

        return $salesClient->getCustomerOrderByOrderReference($orderTransfer);
    }
}
