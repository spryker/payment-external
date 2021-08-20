<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\PaymentExternal\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\PaymentExternal\PaymentExternalFactory getFactory()
 */
class OrderSuccessController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request): View
    {
        $this->getFactory()->getCustomerClient()->markCustomerAsDirty();
        $this->getFactory()->getCartClient()->clearQuote();

        return $this->view([], [], '@PaymentExternal/views/order-success/index.twig');
    }
}
