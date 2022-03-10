<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\PaymentMethodTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PaymentExternal\Communication\PaymentExternalCommunicationFactory getFactory()
 * @method \Spryker\Zed\PaymentExternal\Business\PaymentExternalFacadeInterface getFacade()
 * @method \Spryker\Zed\PaymentExternal\PaymentExternalConfig getConfig()
 */
class PaymentExternalMethodAddedEventListener extends AbstractPlugin implements EventHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentMethodEventTransfer $transfer
     * @param string $eventName
     *
     * @return void
     */
    public function handle(TransferInterface $transfer, $eventName): void
    {
        $storeTransfer = $this->getFactory()->getStoreReferenceFacade()
            ->getStoreByStoreReference($transfer->getStoreReference());

        $paymentMethodTransfer = (new PaymentMethodTransfer())
            ->setLabelName($transfer->getName())
            ->setGroupName($transfer->getProviderName())
            ->setCheckoutOrderTokenUrl($transfer->getCheckoutOrderTokenUrl())
            ->setCheckoutRedirectUrl($transfer->getCheckoutRedirectUrl())
            ->setStore($storeTransfer);

        $this->getFacade()->enableExternalPaymentMethod($paymentMethodTransfer);
    }
}
