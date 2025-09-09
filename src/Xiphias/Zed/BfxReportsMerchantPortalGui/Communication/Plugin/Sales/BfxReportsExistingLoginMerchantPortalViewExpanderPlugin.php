<?php

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Plugin\Sales;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

class BfxReportsExistingLoginMerchantPortalViewExpanderPlugin extends AbstractPlugin implements BfxReportsSalesMerchantPortalGuiViewExpanderPluginInterface
{
    /**
     * @param Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     * @param array $viewData
     *
     * @return array<string, string>
     */
    public function expand(MerchantOrderTransfer $merchantOrderTransfer, array $viewData): array
    {
        $viewData['isLoggedInBladeFx'] = $this->getFactory()->getSessionClient()->has($this->getFactory()->getConfig()->getBfxTokenSessionKey());

        return $viewData;
    }
}
