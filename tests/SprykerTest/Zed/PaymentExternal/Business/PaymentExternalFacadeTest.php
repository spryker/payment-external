<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PaymentExternal\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Orm\Zed\Payment\Persistence\Map\SpyPaymentMethodTableMap;
use Propel\Runtime\ActiveQuery\Criteria;

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
     * @var \SprykerTest\Zed\PaymentExternal\PaymentExternalBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddPaymentMethodReturnsSavedPaymentMethodTransferWithCorrectData(): void
    {
        // Arrange
        $paymentMethodTransfer = $this->tester->getPaymentMethodTransfer([
            PaymentMethodTransfer::LABEL_NAME => 'label-name-1',
            PaymentMethodTransfer::GROUP_NAME => 'group-name-1',
            PaymentMethodTransfer::CHECKOUT_ORDER_TOKEN_URL => 'token-url',
            PaymentMethodTransfer::CHECKOUT_REDIRECT_URL => 'redirect-url',
        ]);

        // Act
        $paymentMethodTransfer = $this->tester->getFacade()
            ->addPaymentMethod($paymentMethodTransfer);

        // Assert
        $this->assertNotNull($paymentMethodTransfer->getIdPaymentMethod());
        $this->assertNotNull($paymentMethodTransfer->getIdPaymentProvider());
        $this->assertFalse($paymentMethodTransfer->getIsDeleted());
    }

    /**
     * @return void
     */
    public function testDeletePaymentMethodSetsPaymentMethodIsDeletedFlagToTrueWithCorrectData(): void
    {
        // Arrange
        $paymentMethodTransfer = $this->tester->getPaymentMethodTransfer([
            PaymentMethodTransfer::LABEL_NAME => 'label-name-2',
            PaymentMethodTransfer::GROUP_NAME => 'group-name-2',
            PaymentMethodTransfer::CHECKOUT_ORDER_TOKEN_URL => 'token-url',
            PaymentMethodTransfer::CHECKOUT_REDIRECT_URL => 'redirect-url',
        ]);

        // Act
        $paymentMethodTransfer = $this->tester->getFacade()
            ->addPaymentMethod($paymentMethodTransfer);

        $this->tester->getFacade()->deletePaymentMethod($paymentMethodTransfer);
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
}
