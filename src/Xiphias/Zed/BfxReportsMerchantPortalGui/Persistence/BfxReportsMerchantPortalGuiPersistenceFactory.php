<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Persistence;

use Orm\Zed\Acl\Persistence\SpyAclGroupQuery;
use Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\BfxReportsMerchantPortalGuiConfig getConfig();
 */
class BfxReportsMerchantPortalGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupQuery
     */
    public function createAclGroupQuery(): SpyAclGroupQuery
    {
        return new SpyAclGroupQuery();
    }

    /**
     * @return \Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery
     */
    public function createMerchantUserQuery(): SpyMerchantUserQuery
    {
        return new SpyMerchantUserQuery();
    }
}
