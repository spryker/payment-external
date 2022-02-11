<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Business\Disabler;

use Generated\Shared\Transfer\PaymentMethodTransfer;
use Spryker\Zed\PaymentExternal\Business\Generator\PaymentMethodKeyGeneratorInterface;
use Spryker\Zed\PaymentExternal\Persistence\PaymentExternalEntityManagerInterface;

class PaymentExternalDisabler implements PaymentExternalDisablerInterface
{
    /**
     * @var \Spryker\Zed\PaymentExternal\Persistence\PaymentExternalEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\PaymentExternal\Business\Generator\PaymentMethodKeyGeneratorInterface
     */
    protected $paymentMethodKeyGenerator;

    /**
     * @param \Spryker\Zed\PaymentExternal\Persistence\PaymentExternalEntityManagerInterface $entityManager
     * @param \Spryker\Zed\PaymentExternal\Business\Generator\PaymentMethodKeyGeneratorInterface $paymentMethodKeyGenerator
     */
    public function __construct(
        PaymentExternalEntityManagerInterface $entityManager,
        PaymentMethodKeyGeneratorInterface $paymentMethodKeyGenerator
    ) {
        $this->entityManager = $entityManager;
        $this->paymentMethodKeyGenerator = $paymentMethodKeyGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return void
     */
    public function disableExternalPaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): void
    {
        $paymentMethodTransfer->requireLabelName()
            ->requireGroupName();

        $paymentMethodKey = $this->paymentMethodKeyGenerator->generatePaymentMethodKey(
            $paymentMethodTransfer->getGroupNameOrFail(),
            $paymentMethodTransfer->getLabelNameOrFail(),
            $paymentMethodTransfer->getStoreReferenceOrFail(),
        );

        $paymentMethodTransfer->setPaymentMethodKey($paymentMethodKey);
        $this->entityManager->deletePaymentMethod($paymentMethodTransfer);
    }
}
