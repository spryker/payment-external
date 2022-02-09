<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

interface PaymentExternalToStoreFacadeBridgeInterface
{
    /**
     * @param string $storeReference
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore(string $storeReference): StoreTransfer;
}
