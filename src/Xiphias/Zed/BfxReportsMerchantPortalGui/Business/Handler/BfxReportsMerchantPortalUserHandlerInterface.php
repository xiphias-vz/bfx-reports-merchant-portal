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
     * @param string $bfxRole
     * @param bool $isActive
     * @param bool $isUpdate
     *
     * @return void
     */
    public function createOrUpdateUserOnBladeFx(UserTransfer $userTransfer, string $bfxRole, bool $isActive, bool $isUpdate): void;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param string $bfxRole
     *
     * @return void
     */
    public function deleteUserOnBladeFx(UserTransfer $userTransfer, string $bfxRole): void;

    /**
     * @param int $userId
     *
     * @return bool
     */
    public function isMerchantUser(int $userId): bool;

    /**
     * @param array<int> $aclGroupIds
     *
     * @return bool
     */
    public function isAdmin(array $aclGroupIds): bool;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return string
     */
    public function getBladeFxAppRole(UserTransfer $userTransfer): string;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    public function hasRootGroupStatusChanged(UserTransfer $userTransfer): bool;
}
