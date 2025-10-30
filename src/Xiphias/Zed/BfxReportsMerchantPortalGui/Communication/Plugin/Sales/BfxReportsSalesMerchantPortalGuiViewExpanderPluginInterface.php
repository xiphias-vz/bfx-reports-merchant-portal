<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Plugin\Sales;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Symfony\Component\HttpFoundation\Request;

interface BfxReportsSalesMerchantPortalGuiViewExpanderPluginInterface
{
    /**
     * @param Request $request
     * @param array $viewData
     *
     * @return array
     */
    public function expand(Request $request, array $viewData): array;
}
