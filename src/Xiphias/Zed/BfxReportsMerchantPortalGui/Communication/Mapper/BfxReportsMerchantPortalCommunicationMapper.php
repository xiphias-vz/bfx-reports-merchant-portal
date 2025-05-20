<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Mapper;

use Generated\Shared\Transfer\BladeFxParameterTransfer;
use Generated\Shared\Transfer\BladeFxParameterListTransfer;
use Generated\Shared\Transfer\BladeFxGetReportPreviewResponseTransfer;
use Symfony\Component\HttpFoundation\Request;
use Xiphias\Shared\Reports\ReportsConstants;
class BfxReportsMerchantPortalCommunicationMapper implements BfxReportsMerchantPortalCommunicationMapperInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return BladeFxParameterListTransfer
     */
    public function mapDownloadParametersToNewParameterListTransfer(Request $request): BladeFxParameterListTransfer
    {
        $reportId = (int)$request->get(ReportsConstants::REPORT_ID);
        $paramId = $request->query->get(ReportsConstants::PARAMETER_VALUE);
        $contextValue = $request->query->get(ReportsConstants::PARAMETER_NAME);
        $parameterTransfers = new BladeFxParameterListTransfer();

        $parameterTransfers->addBladeFxParameter((new BladeFxParameterTransfer())
            ->setParamName(ReportsConstants::CONTEXT_BLADE_FX_PARAMETER_NAME)
            ->setParamValue($contextValue)
            ->setReportId($reportId)
            ->setSqlDbType(''));

        $parameterTransfers->addBladeFxParameter((new BladeFxParameterTransfer())
            ->setParamName(ReportsConstants::ID_BLADE_FX_PARAMETER_NAME)
            ->setParamValue($paramId)
            ->setReportId($reportId)
            ->setSqlDbType(''));

        return $parameterTransfers;
    }
}
