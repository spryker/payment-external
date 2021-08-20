<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Business\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteDataMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param mixed[] $quoteFieldsAllowedForSending
     *
     * @return mixed[]
     */
    public function mapQuoteDataByAllowedFields(
        QuoteTransfer $quoteTransfer,
        array $quoteFieldsAllowedForSending
    ): array;
}
