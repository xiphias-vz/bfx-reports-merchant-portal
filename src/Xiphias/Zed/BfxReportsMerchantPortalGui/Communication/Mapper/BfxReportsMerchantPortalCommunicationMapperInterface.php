<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Mapper;

use Generated\Shared\Transfer\BladeFxParameterTransfer;
use Generated\Shared\Transfer\BladeFxParameterListTransfer;
use Generated\Shared\Transfer\BladeFxGetReportPreviewResponseTransfer;
use Symfony\Component\HttpFoundation\Request;
use Xiphias\Shared\Reports\ReportsConstants;


interface BfxReportsMerchantPortalCommunicationMapperInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return BladeFxParameterListTransfer
     */
    public function mapDownloadParametersToNewParameterListTransfer(Request $request): BladeFxParameterListTransfer;
}
