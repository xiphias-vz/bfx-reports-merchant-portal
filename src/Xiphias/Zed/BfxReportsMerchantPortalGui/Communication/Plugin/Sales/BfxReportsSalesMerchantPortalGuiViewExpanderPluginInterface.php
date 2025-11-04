<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Plugin\Sales;

use Symfony\Component\HttpFoundation\Request;

interface BfxReportsSalesMerchantPortalGuiViewExpanderPluginInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $viewData
     *
     * @return array
     */
    public function expand(Request $request, array $viewData): array;
}
