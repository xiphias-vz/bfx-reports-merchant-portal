<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Plugin\Sales;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\BfxReportsMerchantPortalGuiCommunicationFactory getFactory();
 */
class BfxReportsSalesTableConfigurationViewExpanderPlugin extends AbstractPlugin implements BfxReportsSalesMerchantPortalGuiViewExpanderPluginInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array<string, mixed> $viewData
     *
     * @return array
     */
    public function expand(Request $request, array $viewData): array
    {
        $viewData['bfxReportsTableConfiguration'] = $this->getFactory()->createBfxReportsSalesOrderTabTableConfigurationProvider()->getConfiguration($request);

        return $viewData;
    }
}
