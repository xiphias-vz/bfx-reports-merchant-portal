<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Persistence;
/**
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\Persistence\BfxReportsMerchantPortalGuiPersistenceFactory getFactory();
 */
class BfxReportsMerchantPortalGuiRepository implements BfxReportsMerchantPortalGuiRepositoryInterface
{

    /**
     * @return int
     */
    public function getBladeFxGroupId(): int
    {
        return $this->findWantedGroupId($this->getFactory()->getConfig()->getBladeFxGroupName());

    }

    /**
     * @param int $userId
     *
     * @return string
     */
    public function getUserMerchantId(int $userId): string
    {
        $merchantUserQuery = $this->getFactory()->createMerchantUserQuery();
        $userMerchantId = $merchantUserQuery->findByFkUser($userId)->getIterator()->current();

        return $userMerchantId->getFkMerchant();
    }

    /**
     * @param int $userId
     *
     * @return bool
     */
    public function isMerchantUser(int $userId): bool
    {
        $merchantUserQuery = $this->getFactory()->createMerchantUserQuery();
        $ifUserHasMerchant = $merchantUserQuery->findByFkUser($userId)->getIterator()->current() ?? false;

        if ($ifUserHasMerchant) {
            return true;
        }

        return false;
    }

    /**
     * @param string $groupName
     *
     * @return int|bool
     */
    protected function findWantedGroupId(string $groupName): int|bool
    {
        $aclGroupQuery = $this->getFactory()->createAclGroupQuery();
        $reportsEntityId = $aclGroupQuery->findByName($groupName)->getIterator()->current() ?? false;

        if ($reportsEntityId) {
            return $reportsEntityId->getIdAclGroup();
        }

        return $reportsEntityId;
    }
}
