<?php

namespace Spryker\Zed\PaymentExternal\Business\Handler;


use Generated\Shared\Transfer\PaymentMethodDeletedTransfer;

interface PaymentMethodDeletedHandlerInterface
{
    public function handle(PaymentMethodDeletedTransfer $paymentMethodAddedTransfer): void;
}
