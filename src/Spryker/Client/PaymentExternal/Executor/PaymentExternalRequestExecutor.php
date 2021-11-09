<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PaymentExternal\Executor;

use Generated\Shared\Transfer\PaymentExternalTokenRequestTransfer;
use Generated\Shared\Transfer\PaymentExternalTokenResponseTransfer;
use GuzzleHttp\RequestOptions;
use Spryker\Client\PaymentExternal\Dependency\External\PaymentExternalToHttpClientAdapterInterface;
use Spryker\Client\PaymentExternal\Dependency\Service\PaymentExternalToUtilEncodingServiceInterface;
use Spryker\Client\PaymentExternal\Http\Exception\PaymentExternalHttpRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentExternalRequestExecutor implements PaymentExternalRequestExecutorInterface
{
    /**
     * @var string
     */
    protected const MESSAGE_ERROR_TOKEN_GENERATION = 'Something went wrong with your payment.';

    /**
     * @var \Spryker\Client\PaymentExternal\Dependency\Service\PaymentExternalToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Client\PaymentExternal\Dependency\External\PaymentExternalToHttpClientAdapterInterface
     */
    protected $httpClient;

    /**
     * @param \Spryker\Client\PaymentExternal\Dependency\Service\PaymentExternalToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Client\PaymentExternal\Dependency\External\PaymentExternalToHttpClientAdapterInterface $httpClient
     */
    public function __construct(
        PaymentExternalToUtilEncodingServiceInterface $utilEncodingService,
        PaymentExternalToHttpClientAdapterInterface $httpClient
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->httpClient = $httpClient;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentExternalTokenRequestTransfer $paymentExternalTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentExternalTokenResponseTransfer
     */
    public function generatePaymentExternalToken(
        PaymentExternalTokenRequestTransfer $paymentExternalTokenRequestTransfer
    ): PaymentExternalTokenResponseTransfer {
        try {
            $response = $this->httpClient->request(
                Request::METHOD_POST,
                $paymentExternalTokenRequestTransfer->getRequestUrlOrFail(),
                [
                    RequestOptions::FORM_PARAMS => $paymentExternalTokenRequestTransfer->getPostData(),
                ],
            );
        } catch (PaymentExternalHttpRequestException $e) {
            return (new PaymentExternalTokenResponseTransfer())
                ->setIsSuccessful(false)
                ->setMessage(static::MESSAGE_ERROR_TOKEN_GENERATION);
        }

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return (new PaymentExternalTokenResponseTransfer())
                ->setIsSuccessful(false)
                ->setMessage(static::MESSAGE_ERROR_TOKEN_GENERATION);
        }

        $responseData = $this->utilEncodingService->decodeJson($response->getBody()->getContents(), true);

        return (new PaymentExternalTokenResponseTransfer())->fromArray($responseData, true);
    }
}
