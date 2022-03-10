<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Business\Disabler;

use Generated\Shared\Transfer\PaymentMethodDeletedTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Spryker\Zed\PaymentExternal\Business\Generator\PaymentMethodKeyGeneratorInterface;
use Spryker\Zed\PaymentExternal\Business\Mapper\PaymentMethodEventMapperInterface;
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
     * @var \Spryker\Zed\PaymentExternal\Business\Mapper\PaymentMethodEventMapperInterface
     */
    private PaymentMethodEventMapperInterface $paymentMethodEventMapper;

    /**
     * @param \Spryker\Zed\PaymentExternal\Persistence\PaymentExternalEntityManagerInterface $entityManager
     * @param \Spryker\Zed\PaymentExternal\Business\Generator\PaymentMethodKeyGeneratorInterface $paymentMethodKeyGenerator
     * @param \Spryker\Zed\PaymentExternal\Business\Mapper\PaymentMethodEventMapperInterface $paymentMethodEventMapper
     */
    public function __construct(
        PaymentExternalEntityManagerInterface $entityManager,
        PaymentMethodKeyGeneratorInterface $paymentMethodKeyGenerator,
        PaymentMethodEventMapperInterface $paymentMethodEventMapper
    ) {
        $this->entityManager = $entityManager;
        $this->paymentMethodKeyGenerator = $paymentMethodKeyGenerator;
        $this->paymentMethodEventMapper = $paymentMethodEventMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodDeletedTransfer $paymentMethodDeletedransfer
     *
     * @return void
     */
    public function disableExternalPaymentMethod(PaymentMethodDeletedTransfer $paymentMethodDeletedransfer): void
    {
        $paymentMethodTransfer = $this->paymentMethodEventMapper->mapPaymentMethodDeletedTransferToPaymentMethodTransfer(
            $paymentMethodDeletedransfer,
            new PaymentMethodTransfer(),
        );

        $paymentMethodTransfer->requireLabelName()
            ->requireGroupName();

        $paymentMethodKey = $this->paymentMethodKeyGenerator->generatePaymentMethodKey(
            $paymentMethodTransfer->getGroupNameOrFail(),
            $paymentMethodTransfer->getLabelNameOrFail(),
            $paymentMethodTransfer->getStoreOrFail()->getStoreReferenceOrFail(),
        );

        $paymentMethodTransfer->setPaymentMethodKey($paymentMethodKey);
        $this->entityManager->deletePaymentMethod($paymentMethodTransfer);
    }
}
