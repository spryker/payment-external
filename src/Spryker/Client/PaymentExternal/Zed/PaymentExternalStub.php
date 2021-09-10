<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PaymentExternal\Zed;

use Generated\Shared\Transfer\OrderCancelRequestTransfer;
use Generated\Shared\Transfer\OrderCancelResponseTransfer;
use Generated\Shared\Transfer\OrderFilterTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Client\PaymentExternal\Dependency\Client\PaymentExternalToZedRequestClientInterface;

class PaymentExternalStub implements PaymentExternalStubInterface
{
    /**
     * @var \Spryker\Client\PaymentExternal\Dependency\Client\PaymentExternalToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\PaymentExternal\Dependency\Client\PaymentExternalToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(PaymentExternalToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderCancelRequestTransfer $orderCancelRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCancelResponseTransfer
     */
    public function cancelOrder(OrderCancelRequestTransfer $orderCancelRequestTransfer): OrderCancelResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\OrderCancelResponseTransfer $orderCancelResponseTransfer */
        $orderCancelResponseTransfer = $this->zedRequestClient->call('/payment-external/gateway/cancel-order', $orderCancelRequestTransfer);

        return $orderCancelResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderFilterTransfer $orderFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getGuestOrder(OrderFilterTransfer $orderFilterTransfer): OrderTransfer
    {
        /** @var \Generated\Shared\Transfer\OrderTransfer $orderTransfer */
        $orderTransfer = $this->zedRequestClient->call('/payment-external/gateway/get-guest-order', $orderFilterTransfer);

        return $orderTransfer;
    }
}
