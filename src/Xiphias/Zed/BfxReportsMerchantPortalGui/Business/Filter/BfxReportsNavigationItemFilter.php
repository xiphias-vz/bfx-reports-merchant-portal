<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\NavigationItemCollectionTransfer;
use Spryker\Zed\User\Business\UserFacadeInterface;
use Xiphias\Zed\BfxReportsMerchantPortalGui\Persistence\BfxReportsMerchantPortalGuiRepositoryInterface;

class BfxReportsNavigationItemFilter implements BfxReportsNavigationItemFilterInterface
{
    /**
     * @param \Spryker\Zed\User\Business\UserFacadeInterface $userFacade
     * @param \Xiphias\Zed\BfxReportsMerchantPortalGui\Persistence\BfxReportsMerchantPortalGuiRepositoryInterface $repository
     */
    public function __construct(
        private UserFacadeInterface $userFacade,
        private BfxReportsMerchantPortalGuiRepositoryInterface $repository
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationItemCollectionTransfer $navigationItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationItemCollectionTransfer
     */
    public function filterBfxPackages(NavigationItemCollectionTransfer $navigationItemCollectionTransfer): NavigationItemCollectionTransfer
    {
        if (!$this->userFacade->hasCurrentUser()) {
            return new NavigationItemCollectionTransfer();
        }

        $userTransfer = $this->userFacade->getCurrentUser();
        $isMerchantUser = $this->repository->isMerchantUser($userTransfer->getIdUser());
        $navigationItemTransfers = $navigationItemCollectionTransfer->getNavigationItems()->getArrayCopy();

        if (isset($navigationItemTransfers['reports:index:index']) && $isMerchantUser) {
            unset($navigationItemTransfers['reports:index:index']);
        }

        if (isset($navigationItemTransfers['bfx-reports-merchant-portal-gui:bfx-reports:index'])) {
            unset($navigationItemTransfers['bfx-reports-merchant-portal-gui:bfx-reports:index']);
        }

        return $navigationItemCollectionTransfer->setNavigationItems(
            new ArrayObject($navigationItemTransfers),
        );
    }
}
