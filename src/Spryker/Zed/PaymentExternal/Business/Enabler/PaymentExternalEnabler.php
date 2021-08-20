<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Business\Enabler;

use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Spryker\Zed\PaymentExternal\Business\Generator\PaymentMethodKeyGeneratorInterface;
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
     * @param \Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToPaymentFacadeInterface $paymentFacade
     * @param \Spryker\Zed\PaymentExternal\Business\Generator\PaymentMethodKeyGeneratorInterface $paymentMethodKeyGenerator
     */
    public function __construct(
        PaymentExternalToPaymentFacadeInterface $paymentFacade,
        PaymentMethodKeyGeneratorInterface $paymentMethodKeyGenerator
    ) {
        $this->paymentFacade = $paymentFacade;
        $this->paymentMethodKeyGenerator = $paymentMethodKeyGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function enableExternalPaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): PaymentMethodTransfer
    {
        $paymentMethodTransfer->requireLabelName()
            ->requireGroupName()
            ->requireCheckoutOrderTokenUrl()
            ->requireCheckoutRedirectUrl();

        $paymentMethodKey = $this->paymentMethodKeyGenerator->generatePaymentMethodKey(
            $paymentMethodTransfer->getGroupNameOrFail(),
            $paymentMethodTransfer->getLabelNameOrFail()
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
                ->setIsDeleted(false);

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
