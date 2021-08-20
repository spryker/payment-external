<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\PaymentExternal\Dependency\Client;

interface PaymentExternalToCartClientInterface
{
    /**
     * @return void
     */
    public function clearQuote();
}
