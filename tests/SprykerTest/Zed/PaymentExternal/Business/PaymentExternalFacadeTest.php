<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PaymentExternal\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CheckoutResponseBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderFilterTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentExternalTokenRequestTransfer;
use Generated\Shared\Transfer\PaymentExternalTokenResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodAddedTransfer;
use Generated\Shared\Transfer\PaymentMethodDeletedTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Payment\Persistence\Map\SpyPaymentMethodTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Client\PaymentExternal\PaymentExternalClientInterface;
use Spryker\Zed\PaymentExternal\PaymentExternalDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PaymentExternal
 * @group Business
 * @group Facade
 * @group PaymentExternalFacadeTest
 * Add your own group annotations below this line
 */
class PaymentExternalFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const TOKEN = 'token-value';

    /**
     * @var string
     */
    protected const CHECKOUT_ORDER_TOKEN_URL = 'checkout-order-token-url';

    /**
     * @var string
     */
    protected const CHECKOUT_REDIRECT_URL = 'checkout-redirect-url';

    /**
     * @var \SprykerTest\Zed\PaymentExternal\PaymentExternalBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testEnableExternalPaymentMethodReturnsSavedPaymentMethodTransferWithCorrectData(): void
    {
        // Arrange
        $paymentMethodAddedTransfer = $this->tester->getPaymentMethodAddedTransfer([
            PaymentMethodAddedTransfer::NAME => 'name-1',
            PaymentMethodAddedTransfer::PROVIDER_NAME => 'provider-name-1',
            PaymentMethodAddedTransfer::CHECKOUT_ORDER_TOKEN_URL => 'token-url',
            PaymentMethodAddedTransfer::CHECKOUT_REDIRECT_URL => 'redirect-url',
        ]);

        // Act
        $createdPaymentMethodTransfer = $this->tester->getFacade()
            ->enableExternalPaymentMethod($paymentMethodAddedTransfer);

        $createdPaymentMethodAddedTransfer = $this->tester->mapPaymentMethodTransferToPaymentMethodAddedTransfer(
            $createdPaymentMethodTransfer,
            new PaymentMethodAddedTransfer(),
        );

        // Assert
        $this->assertNotNull($createdPaymentMethodTransfer->getIdPaymentMethod());
        $this->assertNotNull($createdPaymentMethodTransfer->getIdPaymentProvider());
        $this->assertFalse($createdPaymentMethodTransfer->getIsDeleted());
        $this->assertSame($paymentMethodAddedTransfer->getName(), $createdPaymentMethodAddedTransfer->getName());
        $this->assertSame($paymentMethodAddedTransfer->getProviderName(), $createdPaymentMethodAddedTransfer->getProviderName());
        $this->assertSame($paymentMethodAddedTransfer->getCheckoutOrderTokenUrl(), $createdPaymentMethodAddedTransfer->getCheckoutOrderTokenUrl());
        $this->assertSame($paymentMethodAddedTransfer->getCheckoutRedirectUrl(), $createdPaymentMethodAddedTransfer->getCheckoutRedirectUrl());
    }

    /**
     * @return void
     */
    public function testDisableExternalPaymentMethodSetsPaymentMethodIsDeletedFlagToTrueWithCorrectData(): void
    {
        // Arrange
        $paymentMethodAddedTransfer = $this->tester->getPaymentMethodAddedTransfer([
            PaymentMethodAddedTransfer::NAME => 'name-2',
            PaymentMethodAddedTransfer::PROVIDER_NAME => 'provider-name-2',
            PaymentMethodAddedTransfer::CHECKOUT_ORDER_TOKEN_URL => 'token-url',
            PaymentMethodAddedTransfer::CHECKOUT_REDIRECT_URL => 'redirect-url',
        ]);

        // Act
        $paymentMethodTransfer = $this->tester->getFacade()
            ->enableExternalPaymentMethod($paymentMethodAddedTransfer);

        $paymentMethodDeletedTransfer = $this->tester->mapPaymentMethodTransferToPaymentMethodDeletedTransfer(
            $paymentMethodTransfer,
            new PaymentMethodDeletedTransfer(),
        );

        $this->tester->getFacade()->disableExternalPaymentMethod($paymentMethodDeletedTransfer);
        $filterPaymentMethodTransfer = (new PaymentMethodTransfer())
            ->setIdPaymentMethod($paymentMethodTransfer->getIdPaymentMethod());
        $updatedPaymentMethodTransfer = $this->tester->findPaymentMethod($filterPaymentMethodTransfer);

        // Assert
        $this->assertSame($paymentMethodTransfer->getIdPaymentMethod(), $updatedPaymentMethodTransfer->getIdPaymentMethod());
        $this->assertTrue($updatedPaymentMethodTransfer->getIsDeleted());
    }

    /**
     * @return void
     */
    public function testFilterPaymentMethodsRemovesPaymentMethodWithIsDeletedFlagSetToTrue(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $paymentMethodsTransfer = (new PaymentMethodsTransfer())
            ->addMethod($this->tester->havePaymentMethod([
                PaymentMethodTransfer::IS_DELETED => false,
                PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            ]))
            ->addMethod($this->tester->havePaymentMethod([
                PaymentMethodTransfer::IS_DELETED => true,
                PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            ]))
            ->addMethod($this->tester->havePaymentMethod([
                PaymentMethodTransfer::IS_DELETED => true,
                PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            ]));

        // Act
        $this->assertCount(3, $paymentMethodsTransfer->getMethods());
        $paymentMethodsTransfer = $this->tester->getFacade()->filterPaymentMethods($paymentMethodsTransfer);

        // Assert
        $this->assertCount(1, $paymentMethodsTransfer->getMethods());
    }

    /**
     * @return void
     */
    public function testBuildNotDeletedPaymentMethodTableQueryCriteriaReturnsCorrectData(): void
    {
        $queryCriteriaTransfer = $this->tester->getFacade()->buildNotDeletedPaymentMethodTableQueryCriteria();

        $this->assertCount(1, $queryCriteriaTransfer->getWhereConditions());
        $this->assertSame(SpyPaymentMethodTableMap::COL_IS_DELETED, $queryCriteriaTransfer->getWhereConditions()[0]->getColumn());
        $this->assertSame(Criteria::EQUAL, $queryCriteriaTransfer->getWhereConditions()[0]->getComparison());
        $this->assertSame('0', $queryCriteriaTransfer->getWhereConditions()[0]->getValue());
        $this->assertSame(Criteria::LOGICAL_AND, $queryCriteriaTransfer->getConditionOperator());
    }

    /**
     * @return void
     */
    public function testExecuteOrderPostSaveHookReceivesTokenAndUsingItAddsRedirectUrlWithCorrectData(): void
    {
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $paymentMethodTransfer = $this->tester->havePaymentMethod([
            PaymentMethodTransfer::IS_DELETED => false,
            PaymentMethodTransfer::IS_EXTERNAL => true,
            PaymentMethodTransfer::CHECKOUT_ORDER_TOKEN_URL => static::CHECKOUT_ORDER_TOKEN_URL,
            PaymentMethodTransfer::CHECKOUT_REDIRECT_URL => static::CHECKOUT_REDIRECT_URL,
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);

        $paymentTransfer = (new PaymentTransfer())->setPaymentSelection(
            sprintf('%s[%s]', PaymentTransfer::EXTERNAL_PAYMENTS, $paymentMethodTransfer->getPaymentMethodKey()),
        );

        $quoteTransfer = $this->buildQuoteTransfer();
        $quoteTransfer->setPayment($paymentTransfer);
        $checkoutResponseTransfer = $this->buildCheckoutResponseTransfer();

        $paymentExternalClientMock = $this->getPaymentExternalClientMock();
        $paymentExternalClientMock->expects($this->once())
            ->method('generatePaymentExternalToken')
            ->with($this->callback(function (PaymentExternalTokenRequestTransfer $paymentExternalTokenRequestTransfer) {
                return $paymentExternalTokenRequestTransfer->getRequestUrl() === static::CHECKOUT_ORDER_TOKEN_URL;
            }))
            ->willReturn(
                (new PaymentExternalTokenResponseTransfer())
                    ->setIsSuccessful(true)
                    ->setToken(static::TOKEN),
            );

        $this->tester->getFacade()->executeOrderPostSaveHook($quoteTransfer, $checkoutResponseTransfer);

        $this->assertTrue($checkoutResponseTransfer->getIsExternalRedirect());
        $this->assertStringContainsString(static::CHECKOUT_REDIRECT_URL, $checkoutResponseTransfer->getRedirectUrl());
        $this->assertStringContainsString(static::TOKEN, $checkoutResponseTransfer->getRedirectUrl());
    }

    /**
     * @return void
     */
    public function testExecuteOrderPostSaveHookDoesNothingWithIncorrectData(): void
    {
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $paymentMethodTransfer = $this->tester->havePaymentMethod([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);

        $initialQuoteTransfer = $this->buildQuoteTransfer();
        $initialQuoteTransfer->setPayment(
            (new PaymentTransfer())->setPaymentSelection($paymentMethodTransfer->getPaymentMethodKey()),
        );
        $initialCheckoutResponseTransfer = $this->buildCheckoutResponseTransfer();

        $quoteTransfer = clone $initialQuoteTransfer;
        $checkoutResponseTransfer = clone $initialCheckoutResponseTransfer;
        $this->tester->getFacade()->executeOrderPostSaveHook($quoteTransfer, $checkoutResponseTransfer);

        $this->assertEquals($initialQuoteTransfer->toArray(), $quoteTransfer->toArray());
        $this->assertEquals($initialCheckoutResponseTransfer->toArray(), $checkoutResponseTransfer->toArray());
    }

    /**
     * @return void
     */
    public function testGetGuestOrderReturnsFoundOrderTransferWithCorrectData(): void
    {
        // Arrange
        $orderEntity = $this->tester->haveGuestOrderEntity();

        $orderFilterTransfer = $this->tester->getOrderFilterTransfer([
            OrderFilterTransfer::ORDER_REFERENCE => $orderEntity->getOrderReference(),
        ]);

        // Act
        $foundOrderTransfer = $this->tester->getFacade()->getGuestOrder($orderFilterTransfer);

        // Assert
        $this->assertInstanceOf(OrderTransfer::class, $foundOrderTransfer);
        $this->assertEquals($orderEntity->getIdSalesOrder(), $foundOrderTransfer->getIdSalesOrder());
        $this->assertEquals($orderEntity->getOrderReference(), $foundOrderTransfer->getOrderReference());
    }

    /**
     * @return void
     */
    public function testGetGuestOrderReturnsEmptyTransferWithNonEmptyCustomerReference(): void
    {
        // Arrange
        $orderEntity = $this->tester->haveSalesOrderEntity();
        $orderFilterTransfer = $this->tester->getOrderFilterTransfer([
            OrderFilterTransfer::ORDER_REFERENCE => $orderEntity->getOrderReference(),
        ]);

        // Act
        $foundOrderTransfer = $this->tester->getFacade()->getGuestOrder($orderFilterTransfer);

        // Assert
        $this->assertInstanceOf(OrderTransfer::class, $foundOrderTransfer);
        $this->assertNull($foundOrderTransfer->getIdSalesOrder());
    }

    /**
     * @return void
     */
    public function testGetGuestOrderReturnsEmptyTransferWithIncorrectOrderReference(): void
    {
        // Arrange
        $orderFilterTransfer = $this->tester->getOrderFilterTransfer([
            OrderFilterTransfer::ORDER_REFERENCE => 'wrong order reference',
        ]);

        // Act
        $foundOrderTransfer = $this->tester->getFacade()->getGuestOrder($orderFilterTransfer);

        // Assert
        $this->assertInstanceOf(OrderTransfer::class, $foundOrderTransfer);
        $this->assertNull($foundOrderTransfer->getIdSalesOrder());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\PaymentExternal\PaymentExternalClientInterface
     */
    protected function getPaymentExternalClientMock(): PaymentExternalClientInterface
    {
        $paymentExternalClient = $this->getMockBuilder(PaymentExternalClientInterface::class)->getMock();
        $this->tester->setDependency(PaymentExternalDependencyProvider::CLIENT_PAYMENT_EXTERNAL, $paymentExternalClient);

        return $paymentExternalClient;
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function buildCheckoutResponseTransfer(): CheckoutResponseTransfer
    {
        return (new CheckoutResponseBuilder())
            ->withSaveOrder()
            ->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function buildQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteBuilder())
            ->withItem()
            ->withStore()
            ->withCustomer()
            ->withTotals()
            ->withCurrency()
            ->withBillingAddress()
            ->build();
    }
}
