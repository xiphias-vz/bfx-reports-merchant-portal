<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\Persistence\BfxReportsMerchantPortalGuiPersistenceFactory getFactory();
 */
class BfxReportsMerchantPortalGuiRepository extends AbstractRepository implements BfxReportsMerchantPortalGuiRepositoryInterface
{
    /**
     * @return int
     */
    public function getBladeFxGroupId(): int
    {
        return $this->findWantedGroupId($this->getFactory()->getConfig()->getBladeFxGroupName());
    }

    /**
     * @return int
     */
    public function getRootGroupId(): int
    {
        return $this->findWantedGroupId($this->getFactory()->getConfig()->getRootGroupName());
    }

    /**
     * @param int $userId
     *
     * @return int
     */
    public function getUserMerchantId(int $userId): int
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
        $isMerchantUser = $merchantUserQuery->findByFkUser($userId)->getIterator()->current() ?? false;

        return (bool)$isMerchantUser;
    }

    /**
     * @param int $userId
     *
     * @return bool
     */
    public function hasUserRootGroupInDB(int $userId): bool
    {
        $aclUserHasGroupQuery = $this->getFactory()->createAclUserHasGroupQuery();
        $rootGroupId = $this->getRootGroupId();
        $aclUserHasGroup = $aclUserHasGroupQuery
            ->filterByFkAclGroup($rootGroupId)
            ->filterByFkUser($userId)
            ->find()
            ->getIterator()
            ->current();

        return (bool)$aclUserHasGroup;
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
