<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Business\Enabler;

use Generated\Shared\Transfer\PaymentMethodAddedTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Spryker\Zed\PaymentExternal\Business\Generator\PaymentMethodKeyGeneratorInterface;
use Spryker\Zed\PaymentExternal\Business\Mapper\PaymentMethodEventMapperInterface;
use Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToPaymentFacadeInterface;

class PaymentExternalEnabler implements PaymentExternalEnablerInterface
{
    /**
     * @var \Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToPaymentFacadeInterface
     */
    protected $paymentFacade;

    /**
     * @var \Spryker\Zed\PaymentExternal\Business\Generator\PaymentMethodKeyGeneratorInterface
     */
    protected $paymentMethodKeyGenerator;

    /**
     * @var \Spryker\Zed\PaymentExternal\Business\Mapper\PaymentMethodEventMapperInterface
     */
    protected PaymentMethodEventMapperInterface $paymentMethodEventMapper;

    /**
     * @param \Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToPaymentFacadeInterface $paymentFacade
     * @param \Spryker\Zed\PaymentExternal\Business\Generator\PaymentMethodKeyGeneratorInterface $paymentMethodKeyGenerator
     * @param \Spryker\Zed\PaymentExternal\Business\Mapper\PaymentMethodEventMapperInterface $paymentMethodEventMapper
     */
    public function __construct(
        PaymentExternalToPaymentFacadeInterface $paymentFacade,
        PaymentMethodKeyGeneratorInterface $paymentMethodKeyGenerator,
        PaymentMethodEventMapperInterface $paymentMethodEventMapper
    ) {
        $this->paymentFacade = $paymentFacade;
        $this->paymentMethodKeyGenerator = $paymentMethodKeyGenerator;
        $this->paymentMethodEventMapper = $paymentMethodEventMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodAddedTransfer $paymentMethodAddedTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function enableExternalPaymentMethod(PaymentMethodAddedTransfer $paymentMethodAddedTransfer): PaymentMethodTransfer
    {
        $paymentMethodTransfer = $this->paymentMethodEventMapper->mapPaymentMethodAddedTransferToPaymentMethodTransfer(
            $paymentMethodAddedTransfer,
            new PaymentMethodTransfer(),
        );

        $paymentMethodTransfer->requireLabelName()
            ->requireGroupName()
            ->requireCheckoutOrderTokenUrl()
            ->requireCheckoutRedirectUrl();

        $paymentMethodKey = $this->paymentMethodKeyGenerator->generatePaymentMethodKey(
            $paymentMethodTransfer->getGroupNameOrFail(),
            $paymentMethodTransfer->getLabelNameOrFail(),
            $paymentMethodAddedTransfer->getMessageAttributesOrFail()->getStoreReferenceOrFail(),
        );

        $paymentProviderTransfer = $this->findOrCreatePaymentProvider($paymentMethodTransfer->getGroupNameOrFail());

        $paymentMethodTransfer
            ->setName($paymentMethodTransfer->getLabelName())
            ->setIdPaymentProvider($paymentProviderTransfer->getIdPaymentProvider())
            ->setPaymentMethodKey($paymentMethodKey)
            ->setIsExternal(true)
            ->setIsActive(false);

        $existingPaymentMethodTransfer = $this->paymentFacade->findPaymentMethod($paymentMethodTransfer);
        if ($existingPaymentMethodTransfer) {
            $existingPaymentMethodTransfer->fromArray($paymentMethodTransfer->modifiedToArray())
                ->setIsDeleted(false)
                ->setIsActive(false);

            $paymentMethodResponseTransfer = $this->paymentFacade->updatePaymentMethod($existingPaymentMethodTransfer);

            return $paymentMethodResponseTransfer->getPaymentMethodOrFail();
        }

        $paymentMethodResponseTransfer = $this->paymentFacade->createPaymentMethod($paymentMethodTransfer);

        return $paymentMethodResponseTransfer->getPaymentMethodOrFail();
    }

    /**
     * @param string $paymentProviderName
     *
     * @return \Generated\Shared\Transfer\PaymentProviderTransfer
     */
    protected function findOrCreatePaymentProvider(string $paymentProviderName): PaymentProviderTransfer
    {
        $paymentProviderTransfer = (new PaymentProviderTransfer())
            ->setPaymentProviderKey($paymentProviderName);

        $foundPaymentProviderTransfer = $this->paymentFacade->findPaymentProvider($paymentProviderTransfer);

        if ($foundPaymentProviderTransfer) {
            return $foundPaymentProviderTransfer;
        }

        $paymentProviderTransfer->setName($paymentProviderName);

        $paymentProviderResponseTransfer = $this->paymentFacade->createPaymentProvider($paymentProviderTransfer);

        return $paymentProviderResponseTransfer->getPaymentProvider() ?? $paymentProviderTransfer;
    }
}
