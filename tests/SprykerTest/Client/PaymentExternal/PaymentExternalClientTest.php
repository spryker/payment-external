<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\PaymentExternal;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\PaymentExternalTokenRequestTransfer;
use GuzzleHttp\Psr7\Response as GuzzleHttpResponse;
use Psr\Http\Message\StreamInterface;
use Spryker\Client\PaymentExternal\Dependency\External\PaymentExternalToHttpClientAdapterInterface;
use Spryker\Client\PaymentExternal\PaymentExternalDependencyProvider;
use Symfony\Component\HttpFoundation\Response as SymfonyHttpResponse;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group PaymentExternal
 * @group PaymentExternalClientTest
 * Add your own group annotations below this line
 */
class PaymentExternalClientTest extends Test
{
    /**
     * @var \SprykerTest\Client\PaymentExternal\PaymentExternalClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGeneratePaymentExternalTokenReturnsCorrectResponseIfRequestSuccessful(): void
    {
        // Arrange
        $httpClientMock = $this->getHttpClientMock();
        $responseMock = $this->getResponseMock('successful_request.json', SymfonyHttpResponse::HTTP_OK);
        $httpClientMock->method('request')->willReturn($responseMock);

        // Act
        $paymentExternalTokenResponseTransfer = $this->tester->getClient()
            ->generatePaymentExternalToken($this->getPaymentExternalTokenRequestTransfer());

        // Assert
        $this->assertTrue($paymentExternalTokenResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testGeneratePaymentExternalTokenReturnsCorrectResponseIfRequestUnsuccessful(): void
    {
        // Arrange
        $httpClientMock = $this->getHttpClientMock();
        $responseMock = $this->getResponseMock('unsuccessful_request.json', SymfonyHttpResponse::HTTP_OK);
        $httpClientMock->method('request')->willReturn($responseMock);

        // Act
        $paymentExternalTokenResponseTransfer = $this->tester->getClient()
            ->generatePaymentExternalToken($this->getPaymentExternalTokenRequestTransfer());

        // Assert
        $this->assertFalse($paymentExternalTokenResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testGeneratePaymentExternalTokenReturnsCorrectResponseIfRequestFailed(): void
    {
        // Arrange
        $httpClientMock = $this->getHttpClientMock();
        $responseMock = $this->getResponseMock('successful_request.json', SymfonyHttpResponse::HTTP_BAD_REQUEST);
        $httpClientMock->method('request')->willReturn($responseMock);

        // Act
        $paymentExternalTokenResponseTransfer = $this->tester->getClient()
            ->generatePaymentExternalToken($this->getPaymentExternalTokenRequestTransfer());

        // Assert
        $this->assertFalse($paymentExternalTokenResponseTransfer->getIsSuccessful());
    }

    /**
     * @return \Spryker\Client\PaymentExternal\Dependency\External\PaymentExternalToHttpClientAdapterInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getHttpClientMock(): PaymentExternalToHttpClientAdapterInterface
    {
        $httpClientMock = $this->createMock(PaymentExternalToHttpClientAdapterInterface::class);

        $this->tester->setDependency(
            PaymentExternalDependencyProvider::CLIENT_HTTP,
            $httpClientMock
        );

        return $httpClientMock;
    }

    /**
     * @param string $responseFileName
     * @param int $responseCode
     *
     * @return \GuzzleHttp\Psr7\Response|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getResponseMock(string $responseFileName, int $responseCode): GuzzleHttpResponse
    {
        $responseBody = $this->getFixture($responseFileName);
        $responseMock = $this->createMock(GuzzleHttpResponse::class);
        $streamMock = $this->createMock(StreamInterface::class);

        $streamMock->method('getContents')
            ->willReturn($responseBody);
        $responseMock->method('getBody')
            ->willReturn($streamMock);
        $responseMock->method('getStatusCode')
            ->willReturn($responseCode);

        return $responseMock;
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentExternalTokenRequestTransfer
     */
    protected function getPaymentExternalTokenRequestTransfer(): PaymentExternalTokenRequestTransfer
    {
        return (new PaymentExternalTokenRequestTransfer())
            ->setRequestUrl('url-value');
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    protected function getFixture(string $fileName): string
    {
        return file_get_contents(codecept_data_dir('Fixtures/' . $fileName));
    }
}
