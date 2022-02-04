<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Spryker\Zed\PaymentExternal\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\PaymentMethodDeletedTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

/**
 * @method \Spryker\Zed\PaymentExternal\Business\PaymentExternalFacadeInterface getFacade()
 */
class PaymentExternalPaymentMethodDeletedMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer
     *
     * @return void
     */
    public function onPaymentMethodDeleted(PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer): void
    {
        $this->getFacade()->disableExternalPaymentMethod($paymentMethodDeletedTransfer);
    }

    /**
     * Return an array where the key is the class name to be handled and the value is the callable that handles the message.
     *
     * @return array<string, callable>
     */
    public function handles(): iterable
    {
        yield \Generated\Shared\Transfer\PaymentMethodDeletedTransfer::class => [$this, 'onPaymentMethodDeleted'];
    }
}
