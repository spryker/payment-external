<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PaymentExternal\Dependency\External;

use Psr\Http\Message\ResponseInterface;

interface PaymentExternalToHttpClientAdapterInterface
{
    /**
     * @param string $method
     * @param string $uri
     * @param array<mixed> $options
     *
     * @throws \Spryker\Client\PaymentExternal\Http\Exception\PaymentExternalHttpRequestException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request(string $method, string $uri, array $options = []): ResponseInterface;
}
