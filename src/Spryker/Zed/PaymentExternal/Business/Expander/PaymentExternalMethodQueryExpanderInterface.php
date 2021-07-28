<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Business\Expander;

use Generated\Shared\Transfer\QueryCriteriaTransfer;

interface PaymentExternalMethodQueryExpanderInterface
{
    /**
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function buildNotDeletedPaymentMethodTableQueryCriteria(): QueryCriteriaTransfer;
}
