<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\BladeFxAuthenticationRequestTransfer;
use Generated\Shared\Transfer\BladeFxParameterTransfer;
use Generated\Shared\Transfer\BladeFxReportTransfer;
use Spryker\Client\Session\SessionClient;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Xiphias\Client\ReportsApi\ReportsApiClient;
use Xiphias\Shared\Reports\ReportsConstants;
use Xiphias\Zed\Reports\Communication\Plugins\Authentication\BladeFxSessionHandlerPostAuthenticationPlugin;
use Xiphias\Zed\Reports\ReportsConfig;

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
     * @return array<mixed>
     */
    public function indexAction(): array
    {
        $this->temp();

        return $this->viewResponse([
            static::REPORTS_TABLE_CONFIGURATION => $this->getFactory()
                ->createBfxReportsMerchantPortalGuiTableConfigurationProvider()
                ->getConfiguration(),
        ]);
    }

    public function temp(): void
    {
        $tr = (new BladeFxAuthenticationRequestTransfer())
            ->setUsername((new ReportsConfig())->getDefaultUsername())
            ->setPassword((new ReportsConfig())->getDefaultPassword())
            ->setLicenceExp((new ReportsConfig())->getDefaultLicenceExp());

        $val = (new ReportsApiClient())->sendAuthenticateUserRequest($tr);
        (new BladeFxSessionHandlerPostAuthenticationPlugin())->execute($val);
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
            $this->getFactory()->createBfxReportsMerchantPortalGuiTableDataProvider([ReportsConstants::ATTRIBUTE => '']),
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
                'url' => $this->getFactory()->getReportsFacade()->assemblePreviewUrl($reportParamFormTransfer),
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
        $format = $request->query->get('format');
        $reportName = $request->query->get(BladeFxReportTransfer::REP_NAME);
        $reportId = $this->castId($request->query->get(ReportsConstants::REPORT_ID));

        $paramTransfer = $this->getFactory()
            ->getReportsFacade()
            ->mapDownloadParametersToNewParameterListTransfer($request);

        $responseTransfer = $this->getFactory()->getReportsFacade()
            ->getReportByIdInWantedFormat($reportId, $format, $paramTransfer);

        $headers = $this->getFactory()->getReportsFacade()->buildDownloadHeaders($format, $reportId, $reportName);

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

        $url = "/bfx-reports-merchant-portal-gui/bfx-reports/report-download?repId={$reportId}&format=pdf";
        if ($paramName && $paramValue) {
            $url .= "&paramName={$paramName}&paramValue={$paramValue}";
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
     *
     * @return array
     */
    protected function buildDownloadHeaders(string $fileFormat): array
    {
        return [
            'Content-Type' => $this->getApplicationType($fileFormat),
            'Content-Disposition' => 'attachment; filename=' . 'filename.' . $fileFormat,
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
}
