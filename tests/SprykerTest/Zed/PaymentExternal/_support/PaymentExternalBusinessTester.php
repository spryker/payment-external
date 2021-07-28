<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PaymentExternal;

use Codeception\Actor;
use Generated\Shared\DataBuilder\PaymentMethodBuilder;
use Generated\Shared\DataBuilder\PaymentProviderBuilder;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\PaymentExternal\Business\PaymentExternalFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class PaymentExternalBusinessTester extends Actor
{
    use _generated\PaymentExternalBusinessTesterActions;

    /**
     * @param mixed[] $seedData
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function getPaymentMethodTransfer(array $seedData = []): PaymentMethodTransfer
    {
        return (new PaymentMethodBuilder($seedData))->build();
    }

    /**
     * @param mixed[] $seedData
     *
     * @return \Generated\Shared\Transfer\PaymentProviderTransfer
     */
    public function getPaymentProviderTransfer(array $seedData = []): PaymentProviderTransfer
    {
        return (new PaymentProviderBuilder($seedData))->build();
    }
}
