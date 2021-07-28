<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Communication\Plugin\PaymentGui;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PaymentGuiExtension\Dependency\Plugin\PaymentMethodTableQueryExpanderPluginInterface;

/**
 * @method \Spryker\Zed\PaymentExternal\Business\PaymentExternalFacadeInterface getFacade()
 * @method \Spryker\Zed\PaymentExternal\PaymentExternalConfig getConfig()
 */
class NotDeletedPaymentMethodTableQueryExpanderPlugin extends AbstractPlugin implements PaymentMethodTableQueryExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function buildQueryCriteria(): QueryCriteriaTransfer
    {
        return $this->getFacade()->buildNotDeletedPaymentMethodTableQueryCriteria();
    }
}
