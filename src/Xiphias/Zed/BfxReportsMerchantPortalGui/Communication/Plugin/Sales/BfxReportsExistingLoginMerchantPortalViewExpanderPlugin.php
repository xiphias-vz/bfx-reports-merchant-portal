<?php

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Plugin\Sales;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

class BfxReportsExistingLoginMerchantPortalViewExpanderPlugin extends AbstractPlugin implements BfxReportsSalesMerchantPortalGuiViewExpanderPluginInterface
{
    /**
     * @param Request $request
     * @param array $viewData
     *
     * @return array
     */
    public function expand(Request $request, array $viewData): array
    {
        $viewData['isLoggedInBladeFx'] = $this->getFactory()->getSessionClient()->has($this->getFactory()->getConfig()->getBfxTokenSessionKey());

        return $viewData;
    }
}
