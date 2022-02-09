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
use Spryker\Zed\PaymentExternal\Business\Exception\IncorrectDataException;

/**
 * @method \Spryker\Zed\PaymentExternal\Business\PaymentExternalFacadeInterface getFacade()
 * @method \Spryker\Zed\PaymentExternal\PaymentExternalConfig getConfig()
 */
class PaymentExternalMethodDeletedEventListener extends AbstractPlugin implements EventHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentMethodEventTransfer $transfer
     * @param string $eventName
     *
     * @return void
     */
    public function handle(TransferInterface $transfer, $eventName): void
    {
        if(!$transfer->getStoreReference()){
            throw new IncorrectDataException();
        }

        $storeTransfer = $this->getFactory()->getStoreFacade()->findStoreByStoreReference($transfer->getStoreReference());

        if(!$storeTransfer){
            throw new IncorrectDataException();
        }

        $paymentMethodTransfer = (new PaymentMethodTransfer())
            ->setLabelName($transfer->getName())
            ->setGroupName($transfer->getProviderName())
            ->setStoreReference($storeTransfer);

        $this->getFacade()->disableExternalPaymentMethod($paymentMethodTransfer);
    }
}
