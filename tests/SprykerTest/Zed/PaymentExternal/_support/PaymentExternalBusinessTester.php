<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PaymentExternal;

use Codeception\Actor;
use Generated\Shared\DataBuilder\OrderFilterBuilder;
use Generated\Shared\DataBuilder\PaymentMethodAddedBuilder;
use Generated\Shared\DataBuilder\PaymentMethodBuilder;
use Generated\Shared\DataBuilder\PaymentMethodDeletedBuilder;
use Generated\Shared\DataBuilder\PaymentProviderBuilder;
use Generated\Shared\Transfer\OrderFilterTransfer;
use Generated\Shared\Transfer\PaymentMethodAddedTransfer;
use Generated\Shared\Transfer\PaymentMethodDeletedTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\PaymentExternal\Business\PaymentExternalFacadeInterface getFacade()
 * @method \Spryker\Zed\PaymentExternal\Business\PaymentExternalBusinessFactory getFactory()
 *
 * @SuppressWarnings(PHPMD)
 */
class PaymentExternalBusinessTester extends Actor
{
    use _generated\PaymentExternalBusinessTesterActions;

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function getPaymentMethodTransfer(array $seedData = []): PaymentMethodTransfer
    {
        return (new PaymentMethodBuilder($seedData))->build();
    }

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\PaymentMethodAddedTransfer
     */
    public function getPaymentMethodAddedTransfer(array $seedData = []): PaymentMethodAddedTransfer
    {
        return (new PaymentMethodAddedBuilder($seedData))->build();
    }

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\PaymentMethodDeletedTransfer
     */
    public function getPaymentMethodDeletedTransfer(array $seedData = []): PaymentMethodDeletedTransfer
    {
        return (new PaymentMethodDeletedBuilder($seedData))->build();
    }

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\PaymentProviderTransfer
     */
    public function getPaymentProviderTransfer(array $seedData = []): PaymentProviderTransfer
    {
        return (new PaymentProviderBuilder($seedData))->build();
    }

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\OrderFilterTransfer
     */
    public function getOrderFilterTransfer(array $seedData = []): OrderFilterTransfer
    {
        return (new OrderFilterBuilder($seedData))->build();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function haveGuestOrderEntity(): SpySalesOrder
    {
        $orderEntity = $this->haveSalesOrderEntity();
        $orderEntity->setCustomerReference(null);
        $orderEntity->save();

        return $orderEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodDeletedTransfer
     */
    public function mapPaymentMethodTransferToPaymentMethodDeletedTransfer(
        PaymentMethodTransfer $paymentMethodTransfer,
        PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer
    ): PaymentMethodDeletedTransfer {
        $paymentMethodDeletedTransfer
            ->setName($paymentMethodTransfer->getLabelName())
            ->setProviderName($paymentMethodTransfer->getGroupName());

        return $paymentMethodDeletedTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodAddedTransfer $paymentMethodAddedTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodAddedTransfer
     */
    public function mapPaymentMethodTransferToPaymentMethodAddedTransfer(
        PaymentMethodTransfer $paymentMethodTransfer,
        PaymentMethodAddedTransfer $paymentMethodAddedTransfer
    ): PaymentMethodAddedTransfer {
        $paymentMethodAddedTransfer
            ->setName($paymentMethodTransfer->getLabelName())
            ->setProviderName($paymentMethodTransfer->getGroupName())
            ->setCheckoutOrderTokenUrl($paymentMethodTransfer->getCheckoutOrderTokenUrl())
            ->setCheckoutRedirectUrl($paymentMethodTransfer->getCheckoutRedirectUrl());

        return $paymentMethodAddedTransfer;
    }
}
