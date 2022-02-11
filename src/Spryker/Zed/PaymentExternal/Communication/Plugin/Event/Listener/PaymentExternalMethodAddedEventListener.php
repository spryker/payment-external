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
use Spryker\Zed\PaymentExternal\Business\Exception\InvalidStoreReferenceException;

/**
 * @method \Spryker\Zed\PaymentExternal\Business\PaymentExternalBusinessFactory getFactory()
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
        $storeTransfer = $this->getFactory()->getStoreReferenceService()
            ->getStoreByStoreName($transfer->getStoreReference());

        if (!$storeTransfer) {
            throw new InvalidStoreReferenceException();
        }

        $paymentMethodTransfer = (new PaymentMethodTransfer())
            ->setLabelName($transfer->getName())
            ->setGroupName($transfer->getProviderName())
            ->setCheckoutOrderTokenUrl($transfer->getCheckoutOrderTokenUrl())
            ->setCheckoutRedirectUrl($transfer->getCheckoutRedirectUrl())
            ->setStoreReference($storeTransfer);

        $this->getFacade()->enableExternalPaymentMethod($paymentMethodTransfer);
    }
}
