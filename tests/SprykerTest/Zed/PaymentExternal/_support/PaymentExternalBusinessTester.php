<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PaymentExternal;

use Codeception\Actor;
use Codeception\Util\Stub;
use Generated\Shared\DataBuilder\OrderFilterBuilder;
use Generated\Shared\DataBuilder\PaymentMethodBuilder;
use Generated\Shared\DataBuilder\PaymentProviderBuilder;
use Generated\Shared\DataBuilder\StoreBuilder;
use Generated\Shared\Transfer\OrderFilterTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToStoreReferenceFacadeBridge;
use Spryker\Zed\PaymentExternal\PaymentExternalDependencyProvider;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\PaymentExternal\Business\PaymentExternalFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class PaymentExternalBusinessTester extends Actor
{
    use _generated\PaymentExternalBusinessTesterActions;

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function getPaymentMethodTransfer(array $seedData = []): PaymentMethodTransfer
    {
        return (new PaymentMethodBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return /Generated/Shared/Transfer/StoreTransfer
     */
    public function getStoreTransfer(array $seedData = []): StoreTransfer
    {
        return (new StoreBuilder($seedData))->build();
    }

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\PaymentProviderTransfer
     */
    public function getPaymentProviderTransfer(array $seedData = []): PaymentProviderTransfer
    {
        return (new PaymentProviderBuilder($seedData))->build();
    }

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\OrderFilterTransfer
     */
    public function getOrderFilterTransfer(array $seedData = []): OrderFilterTransfer
    {
        return (new OrderFilterBuilder($seedData))->build();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function haveGuestOrderEntity(): SpySalesOrder
    {
        $orderEntity = $this->haveSalesOrderEntity();
        $orderEntity->setCustomerReference(null);
        $orderEntity->save();

        return $orderEntity;
    }

    /**
     * @return void
     */
    public function mockStoreReferenceFacade(): void
    {
        $storeTransfer = (new StoreTransfer())
            ->setName('DE')
            ->setStoreReference('dev-DE');

        $storeReferenceFacadeMock = Stub::make(
            PaymentExternalToStoreReferenceFacadeBridge::class,
            [
                'getStoreByStoreReference' => $storeTransfer,
                'getStoreByStoreName' => $storeTransfer,
            ],
        );

        $this->setDependency(PaymentExternalDependencyProvider::FACADE_STORE_REFERENCE, $storeReferenceFacadeMock);
    }
}
