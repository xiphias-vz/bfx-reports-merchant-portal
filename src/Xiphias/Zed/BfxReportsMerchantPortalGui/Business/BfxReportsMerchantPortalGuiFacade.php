<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Business;

use Generated\Shared\Transfer\NavigationItemCollectionTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\Business\BfxReportsMerchantPortalGuiBusinessFactory getFactory();
 */
class BfxReportsMerchantPortalGuiFacade extends AbstractFacade implements BfxReportsMerchantPortalGuiFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function executeBfxUserHandlingPlugins(UserTransfer $userTransfer): UserTransfer
    {
        return $this->getFactory()->createBfxReportsMerchantPortalUserHandler()->executeBfxUserHandlingPlugins($userTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    public function isUserApplicableForCreateOnBfx(UserTransfer $userTransfer): bool
    {
        return $this->getFactory()->getSprykerBladeFxUserFacade()->isUserApplicableForCreateOnBfx($userTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    public function isUserApplicableForUpdateOnBfx(UserTransfer $userTransfer): bool
    {
        return $this->getFactory()->getSprykerBladeFxUserFacade()->isUserApplicableForUpdateOnBfx($userTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    public function isUserApplicableForDeleteOnBfx(UserTransfer $userTransfer): bool
    {
        return $this->getFactory()->getSprykerBladeFxUserFacade()->isUserApplicableForDeleteOnBfx($userTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param bool $isMerchantUser
     * @param bool $isActive
     * @param bool $isUpdate
     *
     * @return void
     */
    public function createOrUpdateUserOnBladeFx(UserTransfer $userTransfer, string $bfxRole, bool $isActive = true, bool $isUpdate = false): void
    {
        $this->getFactory()->createBfxReportsMerchantPortalUserHandler()->createOrUpdateUserOnBladeFx($userTransfer, $bfxRole, $isActive, $isUpdate);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param string $bfxRole
     *
     * @return void
     */
    public function deleteUserOnBladeFx(UserTransfer $userTransfer, string $bfxRole): void
    {
        $this->getFactory()->createBfxReportsMerchantPortalUserHandler()->deleteUserOnBladeFx($userTransfer, $bfxRole);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationItemCollectionTransfer $navigationItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationItemCollectionTransfer
     */
    public function filterBfxPackages(NavigationItemCollectionTransfer $navigationItemCollectionTransfer): NavigationItemCollectionTransfer
    {
        return $this->getFactory()->createBfxReportsNavigationItemFilter()->filterBfxPackages($navigationItemCollectionTransfer);
    }

    /**
     * @param int $userId
     *
     * @return bool
     */
    public function hasUserBfxGroup(int $userId): bool
    {
        return $this->getFactory()->getSprykerBladeFxUserFacade()->hasUserBfxGroup($userId);
    }

    /**
     * @param int $userId
     *
     * @return bool
     */
    public function isMerchantUser(int $userId): bool
    {
        return $this->getFactory()->createBfxReportsMerchantPortalUserHandler()->isMerchantUser($userId);
    }

    /**
     * @param array<int> $aclGroupIds
     *
     * @return bool
     */
    public function isAdmin(array $aclGroupIds): bool
    {
        return $this->getFactory()->createBfxReportsMerchantPortalUserHandler()->isAdmin($aclGroupIds);
    }
}
