<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Xiphias\Zed\SprykerBladeFxUser\Persistence\SpyAclGroupQuery;
use Xiphias\Zed\SprykerBladeFxUser\Persistence\SpyAclUserHasGroupQuery;
use Xiphias\Zed\SprykerBladeFxUser\Persistence\SpyMerchantUserQuery;

/**
 * @method \Xiphias\Zed\SprykerBladeFxUser\SprykerBladeFxUserConfig getConfig();
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
