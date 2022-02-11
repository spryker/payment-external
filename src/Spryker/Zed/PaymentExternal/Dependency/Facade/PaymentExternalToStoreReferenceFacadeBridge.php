<?php

namespace Spryker\Zed\PaymentExternal\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

class PaymentExternalToStoreReferenceFacadeBridge implements PaymentExternalToStoreReferenceFacadeInterface
{
    /**
     * @var \Spryker\Zed\StoreReference\Business\StoreReferenceFacadeInterface
     */
    protected $storeReferenceFacade;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer
     */
    public function __construct($storeReferenceFacade)
    {
        $this->storeReferenceFacade = $storeReferenceFacade;
    }

    /**
     * @param string $storeReference
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByStoreReference(string $storeReference): StoreTransfer
    {
        return $this->storeReferenceFacade->getStoreByStoreReference($storeReference);
    }
}
