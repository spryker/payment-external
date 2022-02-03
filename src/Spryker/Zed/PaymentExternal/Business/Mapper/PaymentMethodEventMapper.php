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
        $paymentMethodTransfer->setName($paymentMethodAddedTransfer->getName())
            ->setPaymentProvider((new PaymentProviderTransfer())->setName($paymentMethodAddedTransfer->getProviderName()))
            ->setCheckoutOrderTokenUrl($paymentMethodAddedTransfer->getCheckoutOrderTokenUrl())
            ->setCheckoutRedirectUrl($paymentMethodAddedTransfer->getCheckoutRedirectUrl());

        return $paymentMethodTransfer;
    }

    public function mapPaymentMethodDeletedTransferToPaymentMethodTransfer(
        PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer,
        PaymentMethodTransfer $paymentMethodTransfer
    ): PaymentMethodTransfer
    {
        $paymentMethodTransfer->setName($paymentMethodDeletedTransfer->getName())
            ->setPaymentProvider((new PaymentProviderTransfer())->setName($paymentMethodDeletedTransfer->getProviderName()))
            ->setCheckoutOrderTokenUrl($paymentMethodDeletedTransfer->getCheckoutOrderTokenUrl())
            ->setCheckoutRedirectUrl($paymentMethodDeletedTransfer->getCheckoutRedirectUrl());

        return $paymentMethodTransfer;
    }
}
