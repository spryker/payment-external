<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Dependency\Service;

interface PaymentExternalToUtilTextServiceInterface
{
    /**
     * @param string $value
     *
     * @return string
     */
    public function generateSlug($value): string;
}
