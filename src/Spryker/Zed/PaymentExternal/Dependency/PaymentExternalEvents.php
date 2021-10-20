<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Dependency;

interface PaymentExternalEvents
{
    /**
     * Specification:
     * - Represents creation of PBC payment method.
     *
     * @api
     *
     * @var string
     */
    public const PBC_PAYMENT_METHOD_ADDED = 'PaymentMethod.Added';

    /**
     * Specification:
     * - Represents deletion of PBC payment method.
     *
     * @api
     *
     * @var string
     */
    public const PBC_PAYMENT_METHOD_DELETED = 'PaymentMethod.Deleted';
}
