<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\PaymentExternal\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;

interface PaymentExternalToCustomerClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function getCustomer(): ?CustomerTransfer;

    /**
     * @return void
     */
    public function markCustomerAsDirty(): void;
}
