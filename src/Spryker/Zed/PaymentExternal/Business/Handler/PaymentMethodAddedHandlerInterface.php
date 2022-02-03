<?php

namespace Spryker\Zed\PaymentExternal\Business\Handler;


use Generated\Shared\Transfer\PaymentMethodAddedTransfer;

interface PaymentMethodAddedHandlerInterface
{

    public function handle(PaymentMethodAddedTransfer $paymentMethodAddedTransfer): void;
}
