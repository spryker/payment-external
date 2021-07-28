<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Business\Expander;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QueryWhereConditionTransfer;
use Orm\Zed\Payment\Persistence\Map\SpyPaymentMethodTableMap;
use Propel\Runtime\ActiveQuery\Criteria;

class PaymentExternalMethodQueryExpander implements PaymentExternalMethodQueryExpanderInterface
{
    /**
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function buildNotDeletedPaymentMethodTableQueryCriteria(): QueryCriteriaTransfer
    {
        $whereConditionTransfer = (new QueryWhereConditionTransfer())
            ->setColumn(SpyPaymentMethodTableMap::COL_IS_DELETED)
            ->setValue('0')
            ->setComparison(Criteria::EQUAL);

        return (new QueryCriteriaTransfer())
            ->setConditionOperator(Criteria::LOGICAL_AND)
            ->addWhereCondition($whereConditionTransfer);
    }
}
