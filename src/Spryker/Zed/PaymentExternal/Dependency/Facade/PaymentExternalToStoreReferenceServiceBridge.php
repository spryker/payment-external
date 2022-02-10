<?php

namespace Spryker\Zed\PaymentExternal\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

class PaymentExternalToStoreReferenceServiceBridge implements PaymentExternalToStoreReferenceServiceBridgeInterface
{
    /**
     * @var \Spryker\Service\StoreReference\StoreReferenceService
     */
    protected $storeFacade;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer
     */
    public function __construct($storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param string $storeReference
     * @return StoreTransfer
     */
    public function getCurrentStore(string $storeReference): StoreTransfer
    {
        return $this->storeFacade->findStoreByStoreReference($storeReference);
    }
}
