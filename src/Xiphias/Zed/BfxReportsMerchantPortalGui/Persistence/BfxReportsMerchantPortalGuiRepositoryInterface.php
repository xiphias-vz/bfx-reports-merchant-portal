<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Persistence;
interface BfxReportsMerchantPortalGuiRepositoryInterface
{
    /**
     * @return int
     */
    public function getBladeFxGroupId(): int;

    /**
     * @param int $userId
     *
     * @return string
     */
    public function getUserMerchantId(int $userId): string;

    /**
     * @param int $userId
     *
     * @return bool
     */
    public function isMerchantUser(int $userId): bool;
}
