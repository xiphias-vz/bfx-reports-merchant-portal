<?php

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Plugin\Sales;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\BfxReportsMerchantPortalGuiCommunicationFactory getFactory();
 */
class BfxReportsExistingLoginMerchantPortalViewExpanderPlugin extends AbstractPlugin implements BfxReportsSalesMerchantPortalGuiViewExpanderPluginInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array<string, mixed> $viewData
     *
     * @return array
     */
    public function expand(Request $request, array $viewData): array
    {
        $viewData['isLoggedInBladeFx'] = $this->getFactory()->getSessionClient()->has($this->getFactory()->getConfig()->getBfxTokenSessionKey());

        return $viewData;
    }
}
