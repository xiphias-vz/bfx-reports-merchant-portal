<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Business;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\Business\BfxReportsMerchantPortalGuiBusinessFactory getFactory();
 */
class BfxReportsMerchantPortalGuiFacade extends AbstractFacade implements BfxReportsMerchantPortalGuiFacadeInterface
{
    /**
     * @param UserTransfer $userTransfer
     *
     * @return UserTransfer
     */
    public function executeBfxUserHandlingPlugins(UserTransfer $userTransfer): UserTransfer
    {
        return $this->getFactory()->createBfxReportsMerchantPortalPluginHandler()->executeBfxUserHandlingPlugins($userTransfer);
    }

    /**
     * @param UserTransfer $userTransfer
     *
     * @return bool
     */
    public function isUserApplicableForCreateOnBfx(UserTransfer $userTransfer): bool
    {
        return $this->getFactory()->getSprykerBladeFxUserFacade()->isUserApplicableForCreateOnBfx($userTransfer);
    }

    /**
     * @param UserTransfer $userTransfer
     *
     * @return bool
     */
    public function isUserApplicableForUpdateOnBfx(UserTransfer $userTransfer): bool
    {
        return $this->getFactory()->getSprykerBladeFxUserFacade()->isUserApplicableForUpdateOnBfx($userTransfer);
    }

    /**
     * @param UserTransfer $userTransfer
     *
     * @return bool
     */
    public function isUserApplicableForDeleteOnBfx(UserTransfer $userTransfer): bool
    {
        return $this->getFactory()->getSprykerBladeFxUserFacade()->isUserApplicableForDeleteOnBfx($userTransfer);
    }

    /**
     * @param UserTransfer $userTransfer
     * @param bool $isMerchantUser
     * @param bool $isActive
     *
     * @return UserTransfer
     */
    public function createOrUpdateUserOnBladeFx(UserTransfer $userTransfer, bool $isMerchantUser, bool $isActive = true): void
    {
        $this->getFactory()->createBfxReportsMerchantPortalUserHandler()->createOrUpdateUserOnBfx($userTransfer, $isActive, $isMerchantUser);
    }

    /**
     * @param UserTransfer $userTransfer
     * @param bool $isMerchantUser
     * @param bool $isActive
     *
     * @return UserTransfer
     */
    public function deleteUserOnBladeFx(UserTransfer $userTransfer, bool $isMerchantUser, bool $isActive = false): void
    {
        $this->getFactory()->createBfxReportsMerchantPortalUserHandler()->deleteUserOnBladeFx($userTransfer, $isActive, $isMerchantUser);
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
        return $this->getFactory()->getRepository()->isMerchantUser($userId);
    }
}
