<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Plugin\Sales;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\BfxReportsMerchantPortalGuiCommunicationFactory getFactory();
 */
class BfxReportsSalesTableConfigurationViewExpanderPlugin extends AbstractPlugin implements BfxReportsSalesMerchantPortalGuiViewExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     * @param array $viewData
     *
     * @return array
     */
    public function expand(MerchantOrderTransfer $merchantOrderTransfer, array $viewData): array
    {
        $viewData['bfxReportsTableConfiguration'] = $this->getFactory()->createBfxReportsSalesOrderTabTableConfigurationProvider()->getConfiguration($merchantOrderTransfer);

        return $viewData;
    }
}
