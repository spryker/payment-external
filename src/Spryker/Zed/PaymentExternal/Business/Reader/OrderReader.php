<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Business\Reader;

use Generated\Shared\Transfer\OrderFilterTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToSalesFacadeInterface;
use Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException;

class OrderReader implements OrderReaderInterface
{
    /**
     * @var \Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToSalesFacadeInterface $salesFacade
     */
    public function __construct(PaymentExternalToSalesFacadeInterface $salesFacade)
    {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderFilterTransfer $orderFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getGuestOrder(OrderFilterTransfer $orderFilterTransfer): OrderTransfer
    {
        $orderFilterTransfer->requireOrderReference();

        try {
            $orderTransfer = $this->salesFacade->getOrder($orderFilterTransfer);
        } catch (InvalidSalesOrderException $e) {
            return new OrderTransfer();
        }

        if ($orderTransfer->getCustomerReference() !== null) {
            return new OrderTransfer();
        }

        return $orderTransfer;
    }
}
