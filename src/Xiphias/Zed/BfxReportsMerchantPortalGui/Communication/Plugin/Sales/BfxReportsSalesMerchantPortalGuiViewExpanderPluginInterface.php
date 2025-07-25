<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Plugin\Sales;

use Generated\Shared\Transfer\MerchantOrderTransfer;

interface BfxReportsSalesMerchantPortalGuiViewExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     * @param array $viewData
     *
     * @return array
     */
    public function expand(MerchantOrderTransfer $merchantOrderTransfer, array $viewData): array;
}
