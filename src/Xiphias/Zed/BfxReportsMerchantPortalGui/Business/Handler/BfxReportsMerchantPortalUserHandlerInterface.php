<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Business\Handler;

use Generated\Shared\Transfer\UserTransfer;

interface BfxReportsMerchantPortalUserHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function executeBfxUserHandlingPlugins(UserTransfer $userTransfer): UserTransfer;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param bool $isActive
     * @param bool $isMerchantUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function createOrUpdateUserOnBladeFx(UserTransfer $userTransfer, bool $isActive = true, bool $isMerchantUser = false): void;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param bool $isMerchantUser
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function deleteUserOnBladeFx(UserTransfer $userTransfer, bool $isMerchantUser, bool $isActive = false): void;
}
