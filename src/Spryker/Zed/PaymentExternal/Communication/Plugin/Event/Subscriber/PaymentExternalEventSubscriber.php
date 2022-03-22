<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PaymentExternal\Communication\Plugin\Event\Listener\PaymentExternalMethodAddedEventListener;
use Spryker\Zed\PaymentExternal\Communication\Plugin\Event\Listener\PaymentExternalMethodDeletedEventListener;
use Spryker\Zed\PaymentExternal\Dependency\PaymentExternalEvents;

/**
 * @method \Spryker\Zed\PaymentExternal\Business\PaymentExternalFacadeInterface getFacade()
 * @method \Spryker\Zed\PaymentExternal\PaymentExternalConfig getConfig()
 * @method \Spryker\Zed\PaymentExternal\Communication\PaymentExternalCommunicationFactory getFactory()
 */
class PaymentExternalEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $eventCollection->addListener(PaymentExternalEvents::PBC_PAYMENT_METHOD_ADDED, new PaymentExternalMethodAddedEventListener());
        $eventCollection->addListener(PaymentExternalEvents::PBC_PAYMENT_METHOD_DELETED, new PaymentExternalMethodDeletedEventListener());

        return $eventCollection;
    }
}
