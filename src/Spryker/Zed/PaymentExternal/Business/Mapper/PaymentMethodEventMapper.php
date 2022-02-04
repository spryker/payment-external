<?php

namespace Spryker\Zed\PaymentExternal\Business\Mapper;


use Generated\Shared\Transfer\PaymentMethodAddedTransfer;
use Generated\Shared\Transfer\PaymentMethodDeletedTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;

class PaymentMethodEventMapper implements PaymentMethodEventMapperInterface
{

    public function mapPaymentMethodAddedTransferToPaymentMethodTransfer(
        PaymentMethodAddedTransfer $paymentMethodAddedTransfer,
        PaymentMethodTransfer $paymentMethodTransfer
    ): PaymentMethodTransfer
    {
        $paymentMethodTransfer
            ->setLabelName($paymentMethodAddedTransfer->getName())
            ->setGroupName($paymentMethodAddedTransfer->getProviderName())
            ->setCheckoutOrderTokenUrl($paymentMethodAddedTransfer->getCheckoutOrderTokenUrl())
            ->setCheckoutRedirectUrl($paymentMethodAddedTransfer->getCheckoutRedirectUrl());

        return $paymentMethodTransfer;
    }

    public function mapPaymentMethodDeletedTransferToPaymentMethodTransfer(
        PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer,
        PaymentMethodTransfer $paymentMethodTransfer
    ): PaymentMethodTransfer
    {
        $paymentMethodTransfer
            ->setLabelName($paymentMethodDeletedTransfer->getName())
            ->setGroupName($paymentMethodDeletedTransfer->getProviderName());

        return $paymentMethodTransfer;
    }
}
