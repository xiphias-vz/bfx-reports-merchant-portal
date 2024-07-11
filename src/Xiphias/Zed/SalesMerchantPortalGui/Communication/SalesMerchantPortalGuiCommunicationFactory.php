<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Xiphias\Zed\SalesMerchantPortalGui\Communication;

use Spryker\Zed\SalesMerchantPortalGui\Communication\SalesMerchantPortalGuiCommunicationFactory as SprykerSalesMerchantPortalGuiCommunicationFactory;
use Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Provider\BfxReportsSalesOrderTabTableConfigurationProvider;

/**
 * @method \Spryker\Zed\SalesMerchantPortalGui\SalesMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface getRepository()
 */
class SalesMerchantPortalGuiCommunicationFactory extends SprykerSalesMerchantPortalGuiCommunicationFactory
{
    /**
     * @return \Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Provider\BfxReportsSalesOrderTabTableConfigurationProvider
     */
    public function getBladeFxReportsTableConfiguration(): BfxReportsSalesOrderTabTableConfigurationProvider
    {
        return new BfxReportsSalesOrderTabTableConfigurationProvider(
            $this->getGuiTableFactory(),
        );
    }
}
