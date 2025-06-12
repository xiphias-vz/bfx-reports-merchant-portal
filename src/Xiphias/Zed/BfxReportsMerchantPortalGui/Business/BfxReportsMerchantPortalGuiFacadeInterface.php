<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Business;

use Generated\Shared\Transfer\UserTransfer;

interface BfxReportsMerchantPortalGuiFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function executeBfxUserHandlingPlugins(UserTransfer $userTransfer): UserTransfer;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    public function isUserApplicableForCreateOnBfx(UserTransfer $userTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    public function isUserApplicableForUpdateOnBfx(UserTransfer $userTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    public function isUserApplicableForDeleteOnBfx(UserTransfer $userTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param bool $isMerchantUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function createOrUpdateUserOnBladeFx(UserTransfer $userTransfer, bool $isMerchantUser): void;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param bool $isMerchantUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function deleteUserOnBladeFx(UserTransfer $userTransfer, bool $isMerchantUser): void;
}
