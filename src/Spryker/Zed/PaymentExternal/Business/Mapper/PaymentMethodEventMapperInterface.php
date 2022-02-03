<?php


namespace Spryker\Zed\PaymentExternal\Business\Mapper;


use Generated\Shared\Transfer\PaymentMethodAddedTransfer;
use Generated\Shared\Transfer\PaymentMethodDeletedTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;

interface PaymentMethodEventMapperInterface
{

    public function mapPaymentMethodAddedTransferToPaymentMethodTransfer(
        PaymentMethodAddedTransfer $paymentMethodAddedTransfer,
        PaymentMethodTransfer $paymentMethodTransfer
    ): PaymentMethodTransfer;

    public function mapPaymentMethodDeletedTransferToPaymentMethodTransfer(
        PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer,
        PaymentMethodTransfer $paymentMethodTransfer
    ): PaymentMethodTransfer;
}
