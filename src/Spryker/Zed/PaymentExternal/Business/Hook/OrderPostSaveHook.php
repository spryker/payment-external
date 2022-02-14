<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Business\Hook;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PaymentExternalTokenRequestTransfer;
use Generated\Shared\Transfer\PaymentExternalTokenResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Client\PaymentExternal\PaymentExternalClientInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\PaymentExternal\Business\Mapper\QuoteDataMapperInterface;
use Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToLocaleFacadeInterface;
use Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToPaymentFacadeInterface;
use Spryker\Zed\PaymentExternal\PaymentExternalConfig;

class OrderPostSaveHook implements OrderPostSaveHookInterface
{
    /**
     * @var string
     */
    protected const ERROR_CODE_PAYMENT_FAILED = 'payment failed';

    /**
     * @var \Spryker\Zed\PaymentExternal\Business\Mapper\QuoteDataMapperInterface
     */
    protected $quoteDataMapper;

    /**
     * @var \Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToPaymentFacadeInterface
     */
    protected $paymentFacade;

    /**
     * @var \Spryker\Client\PaymentExternal\PaymentExternalClientInterface
     */
    protected $paymentExternalClient;

    /**
     * @var \Spryker\Zed\PaymentExternal\PaymentExternalConfig
     */
    protected $paymentExternalConfig;

    /**
     * @param \Spryker\Zed\PaymentExternal\Business\Mapper\QuoteDataMapperInterface $quoteDataMapper
     * @param \Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToPaymentFacadeInterface $paymentFacade
     * @param \Spryker\Client\PaymentExternal\PaymentExternalClientInterface $paymentExternalClient
     * @param \Spryker\Zed\PaymentExternal\PaymentExternalConfig $paymentExternalConfig
     */
    public function __construct(
        QuoteDataMapperInterface $quoteDataMapper,
        PaymentExternalToLocaleFacadeInterface $localeFacade,
        PaymentExternalToPaymentFacadeInterface $paymentFacade,
        PaymentExternalClientInterface $paymentExternalClient,
        PaymentExternalConfig $paymentExternalConfig
    ) {
        $this->quoteDataMapper = $quoteDataMapper;
        $this->localeFacade = $localeFacade;
        $this->paymentFacade = $paymentFacade;
        $this->paymentExternalClient = $paymentExternalClient;
        $this->paymentExternalConfig = $paymentExternalConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function executeOrderPostSaveHook(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): void {
        $paymentSelectionKey = $this->getPaymentSelectionKey($quoteTransfer->getPaymentOrFail());

        if ($paymentSelectionKey !== PaymentTransfer::EXTERNAL_PAYMENTS) {
            return;
        }

        $paymentMethodKey = $this->getPaymentMethodKey($quoteTransfer->getPaymentOrFail());
        $paymentMethodTransfer = $this->paymentFacade->findPaymentMethod(
            (new PaymentMethodTransfer())->setPaymentMethodKey($paymentMethodKey),
        );

        if (!$paymentMethodTransfer) {
            return;
        }

        $paymentExternalTokenResponseTransfer = $this->requestPaymentExternalToken(
            $paymentMethodTransfer,
            $quoteTransfer,
            $checkoutResponseTransfer->getSaveOrderOrFail(),
        );

        $this->processPaymentExternalTokenResponse(
            $paymentExternalTokenResponseTransfer,
            $checkoutResponseTransfer,
            $paymentMethodTransfer,
        );
    }

    /**
     * Returns only the first matching string for the pattern `[a-zA-Z0-9_]+`.
     *
     * @example 'externalPayments[paymentKey]' becomes 'externalPayments'
     *
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return string
     */
    protected function getPaymentSelectionKey(PaymentTransfer $paymentTransfer): string
    {
        preg_match('/^([\w]+)/', $paymentTransfer->getPaymentSelectionOrFail(), $matches);

        if (!isset($matches[0])) {
            return $paymentTransfer->getPaymentSelectionOrFail();
        }

        return $matches[0];
    }

    /**
     * Returns only the first matching string for the provided pattern in square brackets.
     * Returns the specified value if there is no match.
     *
     * @example 'externalPayments[paymentKey]' becomes 'paymentKey'
     *
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return string
     */
    protected function getPaymentMethodKey(PaymentTransfer $paymentTransfer): string
    {
        preg_match('/\[([a-zA-Z0-9_-]+)\]/', $paymentTransfer->getPaymentSelectionOrFail(), $matches);

        if (!isset($matches[1])) {
            return $paymentTransfer->getPaymentSelectionOrFail();
        }

        return $matches[1];
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentExternalTokenResponseTransfer
     */
    protected function requestPaymentExternalToken(
        PaymentMethodTransfer $paymentMethodTransfer,
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): PaymentExternalTokenResponseTransfer {
        $localeTransfer = $this->localeFacade->getCurrentLocale();
        $quoteTransfer->setOrderReference($saveOrderTransfer->getOrderReference());
        $quoteTransfer->getCustomerOrFail()->setLocale($localeTransfer);

        $language = $this->getCurrentLanguage($localeTransfer);
        $postData = [
            'orderData' => $this->quoteDataMapper->mapQuoteDataByAllowedFields(
                $quoteTransfer,
                $this->paymentExternalConfig->getQuoteFieldsAllowedForSending(),
            ),
            'redirectSuccessUrl' => $this->generatePaymentRedirectUrl(
                $language,
                $this->paymentExternalConfig->getSuccessRoute(),
            ),
            'redirectCancelUrl' => $this->generatePaymentRedirectUrl(
                $language,
                $this->paymentExternalConfig->getCancelRoute(),
                ['orderReference' => $quoteTransfer->getOrderReference()],
            ),
            'checkoutSummaryPageUrl' => $this->generatePaymentRedirectUrl(
                $language,
                $this->paymentExternalConfig->getCheckoutSummaryPageRoute(),
            ),
            'store' => $paymentMethodTransfer->getStore(),
        ];

        $paymentExternalTokenRequestTransfer = (new PaymentExternalTokenRequestTransfer())
            ->setRequestUrl($paymentMethodTransfer->getCheckoutOrderTokenUrl())
            ->setPostData($postData);

        return $this->paymentExternalClient->generatePaymentExternalToken($paymentExternalTokenRequestTransfer);
    }

    /**
     * @param string $language
     * @param string $urlPath
     * @param array<mixed> $queryParts
     *
     * @return string
     */
    protected function generatePaymentRedirectUrl(string $language, string $urlPath, array $queryParts = []): string
    {
        $url = sprintf(
            '%s/%s/%s',
            $this->paymentExternalConfig->getBaseUrlYves(),
            $language,
            $urlPath,
        );

        return Url::generate($url, $queryParts)->build();
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    protected function getCurrentLanguage(LocaleTransfer $localeTransfer): string
    {
        $splitLocale = explode('_', $localeTransfer->getLocaleNameOrFail());

        return $splitLocale[0];
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentExternalTokenResponseTransfer $paymentExternalTokenResponseTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return void
     */
    protected function processPaymentExternalTokenResponse(
        PaymentExternalTokenResponseTransfer $paymentExternalTokenResponseTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer,
        PaymentMethodTransfer $paymentMethodTransfer
    ): void {
        if (!$paymentExternalTokenResponseTransfer->getIsSuccessful()) {
            $checkoutErrorTransfer = (new CheckoutErrorTransfer())
                ->setErrorCode(static::ERROR_CODE_PAYMENT_FAILED)
                ->setMessage($paymentExternalTokenResponseTransfer->getMessage());
            $checkoutResponseTransfer->setIsSuccess(false)
                ->addError($checkoutErrorTransfer);

            return;
        }

        $redirectUrl = Url::generate(
            $paymentMethodTransfer->getCheckoutRedirectUrlOrFail(),
            ['token' => $paymentExternalTokenResponseTransfer->getToken()],
        )->build();

        $checkoutResponseTransfer
            ->setIsExternalRedirect(true)
            ->setRedirectUrl($redirectUrl);
    }
}
