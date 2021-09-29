<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class PaymentExternalConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Shared\Application\ApplicationConstants::BASE_URL_YVES
     *
     * @var string
     */
    protected const BASE_URL_YVES = 'APPLICATION:BASE_URL_YVES';

    /**
     * @api
     *
     * @return string
     */
    public function getSuccessRoute(): string
    {
        return '/payment/order-success';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCancelRoute(): string
    {
        return '/payment/order-cancel';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getBaseUrlYves(): string
    {
        return $this->get(static::BASE_URL_YVES);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTenantUuid(): string
    {
        return getenv('TENANT_UUID') ?: '';
    }

    /**
     * @api
     *
     * @example
     * [
     *     QuoteTransfer::ORDER_REFERENCE => 'orderReference',
     *     QuoteTransfer::ITEMS => [
     *         ItemTransfer::NAME => 'itemName',
     *         ItemTransfer::ABSTRACT_SKU => 'abstractSku',
     *     ],
     * ]
     *
     * @return mixed[]
     */
    public function getQuoteFieldsAllowedForSending(): array
    {
        return [
            QuoteTransfer::ORDER_REFERENCE => 'orderReference',
            QuoteTransfer::STORE => [
                StoreTransfer::NAME => 'storeName',
            ],
            QuoteTransfer::CUSTOMER => [
                CustomerTransfer::LOCALE => [
                    LocaleTransfer::LOCALE_NAME => 'localeName',
                ],
            ],
            QuoteTransfer::BILLING_ADDRESS => [
                AddressTransfer::ISO2_CODE => 'countryCode',
                AddressTransfer::FIRST_NAME => 'customerFirstName',
                AddressTransfer::LAST_NAME => 'customerLastName',
            ],
            QuoteTransfer::CURRENCY => [
                CurrencyTransfer::CODE => 'currencyCode',
            ],
            QuoteTransfer::PAYMENT => [
                PaymentTransfer::AMOUNT => 'grandTotal',
                PaymentTransfer::PAYMENT_METHOD => 'paymentMethod',
            ],
            QuoteTransfer::ITEMS => [
                ItemTransfer::ID_SALES_ORDER_ITEM => 'idSalesOrderItem',
                ItemTransfer::NAME => 'name',
            ],
        ];
    }
}
