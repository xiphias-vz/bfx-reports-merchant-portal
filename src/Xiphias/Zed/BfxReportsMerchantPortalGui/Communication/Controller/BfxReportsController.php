<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\BladeFxParameterTransfer;
use Generated\Shared\Transfer\BladeFxReportTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Xiphias\Shared\Reports\ReportsConstants;

/**
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\BfxReportsMerchantPortalGuiCommunicationFactory getFactory();
 */
class BfxReportsController extends AbstractController
{
    /**
     * @var string
     */
    public const REPORTS_TABLE_CONFIGURATION = 'bfxReportsTableConfiguration';

    /**
     * @var string
     */
    public const DATE_FORMAT = 'Y_m_d';

    /**
     * @return array<mixed>
     */
    public function indexAction(): array
    {
        $var = $this->viewResponse([
            static::REPORTS_TABLE_CONFIGURATION => $this->getFactory()
                ->createBfxReportsMerchantPortalGuiTableConfigurationProvider()
                ->getConfiguration(),
        ]);

        return $var;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mainReportsTableDataAction(Request $request): Response
    {
        return $this->getFactory()->getGuiTableHttpDataRequestExecutor()->execute(
            $request,
            $this->getFactory()->createBfxReportsMerchantPortalGuiTableDataProvider([ReportsConstants::ATTRIBUTE => '']),
            $this->getFactory()->createBfxReportsMerchantPortalGuiTableConfigurationProvider()->getConfiguration(),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function salesReportsTableDataAction(Request $request): Response
    {
        return $this->getFactory()->getGuiTableHttpDataRequestExecutor()->execute(
            $request,
            $this->getFactory()->createBfxReportsMerchantPortalGuiTableDataProvider([ReportsConstants::ATTRIBUTE => ReportsConstants::BLADE_FX_ORDER_ATTRIBUTE]),
            $this->getFactory()->createBfxReportsMerchantPortalGuiTableConfigurationProvider()->getConfiguration(),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function reportIframeAction(Request $request): Response
    {
        $reportId = (int)$request->get(BladeFxReportTransfer::REP_ID);
        $reportParamFormTransfer = $this
            ->getFactory()
            ->getReportsFacade()
            ->getReportParamForm($reportId);

        $responseData = [
            'html' => $this->renderView('@BfxReportsMerchantPortalGui/Partials/report-iframe.twig', [
                'url' => $reportParamFormTransfer->getIframeUrl(),
            ])->getContent(),
        ];

        return new JsonResponse($responseData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reportPreviewWithParameterAction(Request $request): Response
    {
        $paramTransfer = (new BladeFxParameterTransfer())
            ->setParamName($request->query->get(BladeFxParameterTransfer::PARAM_NAME))
            ->setParamValue($request->query->get(BladeFxParameterTransfer::PARAM_VALUE))
            ->setReportId((int)$request->query->get(BladeFxReportTransfer::REP_ID))
            ->setSqlDbType('');

        $reportParamFormTransfer = $this
            ->getFactory()
            ->getReportsFacade()
            ->getReportPreviewURL($paramTransfer);

        $responseData = [
            'html' => $this->renderView('@BfxReportsMerchantPortalGui/Partials/report-iframe.twig', [
                'url' => $reportParamFormTransfer->getUrl(),
            ])->getContent(),
        ];

        return new JsonResponse($responseData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reportDownloadAction(Request $request): Response
    {
        $reportId = $this->castId($request->query->get(BladeFxReportTransfer::REP_ID));
        $format = $request->query->get('format');

        $paramName = $request->query->get(BladeFxParameterTransfer::PARAM_NAME);
        $paramValue = $request->query->get(BladeFxParameterTransfer::PARAM_VALUE);

        $paramTransfer = (new BladeFxParameterTransfer())->setReportId($reportId)->setParamName($paramName)->setParamValue($paramValue)->setSqlDbType('');
        $responseTransfer = $this->getFactory()->getReportsFacade()->getReportByIdInWantedFormat($reportId, $format, $paramTransfer);
        $fileName = $this->buildFileName($reportId, $this->getFactory()->getDateTimeService()->getCurrentDate(static::DATE_FORMAT));
        $headers = $this->buildDownloadHeaders($format, $fileName);

        return new Response(
            $responseTransfer->getReport(),
            200,
            $headers,
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function reportDownloadResponseBuilderAction(Request $request): JsonResponse
    {
        $reportId = $this->castId($request->query->get(BladeFxReportTransfer::REP_ID));
        $paramName = $request->query->get(BladeFxParameterTransfer::PARAM_NAME);
        $paramValue = $request->query->get(BladeFxParameterTransfer::PARAM_VALUE);

        $url = "/bfx-reports-merchant-portal-gui/bfx-reports/report-download?repId=${reportId}&format=pdf";
        if ($paramName && $paramValue) {
            $url .= "&paramName=${paramName}&paramValue=${paramValue}";
        }

        $zedUiFormResponseTransfer = $this
            ->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addActionRedirect($url)
            ->createResponse();

        return new JsonResponse($zedUiFormResponseTransfer->toArray());
    }

    /**
     * @param string $fileFormat
     * @param string $fileName
     *
     * @return array
     */
    protected function buildDownloadHeaders(string $fileFormat, string $fileName): array
    {
        return [
            'Content-Type' => $this->getApplicationType($fileFormat),
            'Content-Disposition' => 'attachment; filename=' . $fileName . '.' . $fileFormat,
            'Pragma' => 'Public',
        ];
    }

    /**
     * @param string $fileFormat
     *
     * @return string
     */
    protected function getApplicationType(string $fileFormat): string
    {
        return match ($fileFormat) {
            'pdf' => 'application/pdf',
            'csv' => 'application/csv',
            'pptx' => 'application/pptx',
            'docx' => 'application/docs',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'mht' => 'application/mht',
            'rtf' => 'application/rtf',
            'jpg' => 'application/jpg',
            default => 'error',
        };
    }

    /**
     * @param int $reportId
     * @param string $currentDate
     *
     * @return string
     */
    protected function buildFileName(int $reportId, string $currentDate): string
    {
        return 'report_id_' . $reportId . '_' . $currentDate;
    }
}
