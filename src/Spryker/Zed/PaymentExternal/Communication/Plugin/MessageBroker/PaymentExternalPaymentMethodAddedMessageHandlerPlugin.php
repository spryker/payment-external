<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\PaymentMethodAddedTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

/**
 * @method \Spryker\Zed\PaymentExternal\Business\PaymentExternalFacadeInterface getFacade()
 * @method \Spryker\Zed\PaymentExternal\PaymentExternalConfig getConfig()
 */
class PaymentExternalPaymentMethodAddedMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodAddedTransfer $paymentMethodAddedTransfer
     *
     * @return void
     */
    public function onPaymentMethodAdded(PaymentMethodAddedTransfer $paymentMethodAddedTransfer): void
    {
        $this->getFacade()->enableExternalPaymentMethod($paymentMethodAddedTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * Return an array where the key is the class name to be handled and the value is the callable that handles the message.
     *
     * @api
     *
     * @return array<string, callable>
     */
    public function handles(): iterable
    {
        yield PaymentMethodAddedTransfer::class => [$this, 'onPaymentMethodAdded'];
    }
}
