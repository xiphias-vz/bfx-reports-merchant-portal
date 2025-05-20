<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Business;

use Generated\Shared\Transfer\UserTransfer;

interface BfxReportsMerchantPortalGuiFacadeInterface
{
    /**
     * @param UserTransfer $userTransfer
     *
     * @return UserTransfer
     */
    public function executeBfxUserHandlingPlugins(UserTransfer $userTransfer): UserTransfer;

    /**
     * @param UserTransfer $userTransfer
     *
     * @return bool
     */
    public function isUserApplicableForCreateOnBfx(UserTransfer $userTransfer): bool;

    /**
     * @param UserTransfer $userTransfer
     *
     * @return bool
     */
    public function isUserApplicableForUpdateOnBfx(UserTransfer $userTransfer): bool;

    /**
     * @param UserTransfer $userTransfer
     *
     * @return bool
     */
    public function isUserApplicableForDeleteOnBfx(UserTransfer $userTransfer): bool;

    /**
     * @param UserTransfer $userTransfer
     *
     * @return UserTransfer
     */
    public function createOrUpdateUserOnBladeFx(UserTransfer $userTransfer, bool $isMerchantUser): void;

    /**
     * @param UserTransfer $userTransfer
     * @param bool $isMerchantUser
     * @param bool $isActive
     *
     * @return UserTransfer
     */
    public function deleteUserOnBladeFx(UserTransfer $userTransfer, bool $isMerchantUser): void;
}
