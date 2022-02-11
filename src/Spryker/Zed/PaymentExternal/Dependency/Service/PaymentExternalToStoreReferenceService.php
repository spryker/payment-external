<?php

namespace Spryker\Zed\PaymentExternal\Dependency\Service;

use Generated\Shared\Transfer\StoreTransfer;

class PaymentExternalToStoreReferenceService implements PaymentExternalToStoreReferenceServiceInterface
{
    /**
     * @var \Spryker\Service\StoreReference\StoreReferenceService
     */
    protected $storeReferenceService;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer
     */
    public function __construct($storeReferenceService)
    {
        $this->storeReferenceService = $storeReferenceService;
    }

    /**
     * @param string $storeReference
     * @return StoreTransfer
     */
    public function getStoreByStoreReference(string $storeReference): StoreTransfer
    {
        return $this->storeReferenceService->getStoreByStoreReference($storeReference);
    }
}
