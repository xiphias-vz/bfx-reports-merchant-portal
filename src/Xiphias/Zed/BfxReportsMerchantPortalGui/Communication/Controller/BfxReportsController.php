<?php


declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\BladeFxAuthenticationRequestTransfer;
use Generated\Shared\Transfer\BladeFxCategoryTransfer;
use Generated\Shared\Transfer\BladeFxParameterTransfer;
use Generated\Shared\Transfer\BladeFxReportTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Xiphias\Client\ReportsApi\ReportsApiClient;
use Xiphias\Shared\Reports\ReportsConstants;
use Xiphias\Zed\Reports\Business\ReportsFacade;
use Xiphias\Zed\Reports\Communication\Plugins\Authentication\BladeFxSessionHandlerPostAuthenticationPlugin;
use Xiphias\Zed\Reports\Communication\ReportsCommunicationFactory;
use Xiphias\Zed\Reports\ReportsConfig;

/**
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\BfxReportsMerchantPortalGuiCommunicationFactory getFactory();
 */
class BfxReportsController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<mixed>
     */
    public function indexAction(Request $request): array
    {
        $categories = $this->getFactory()->getReportsFacade()->getCategories();
        $categories = array_map(function (BladeFxCategoryTransfer $category) {
            return $category->toArray(true, true);
        }, $categories);
        $categoryTree = $this->getFactory()->getReportsFacade()->assembleCategoryTree($categories);

        return $this->viewResponse([
            'categoryTree' => array_values($categoryTree),
            'bfxReportsTableConfiguration' => $this->getFactory()
                ->createBfxReportsMerchantPortalGuiTableConfigurationProvider()
                ->getConfiguration(),
        ]);
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
            $this->getFactory()->createBfxReportsMerchantPortalGuiTableDataProvider($request, $this->buildParams()),
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
            $this->getFactory()->createBfxReportsMerchantPortalGuiTableDataProvider($request, $this->buildParams()),
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
        $paramTransfer = $this->getFactory()->getReportsFacade()->mapPreviewParametersToNewParameterTransfer($request);

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
        $reportId = $this->castId($request->query->get(BladeFxReportTransfer::REP_ID));

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
        $reportName = $request->query->get(BladeFxReportTransfer::REP_NAME);
        $paramName = $request->query->get(BladeFxParameterTransfer::PARAM_NAME);
        $paramValue = $request->query->get(BladeFxParameterTransfer::PARAM_VALUE);

        $url = "/bfx-reports-merchant-portal-gui/bfx-reports/report-download?repId={$reportId}&repName={$reportName}&format=pdf";
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
     * @return array<mixed>
     */
    protected function getCategoryTree(): array
    {
        $categories = $this->getFactory()->getReportsFacade()->getCategories();
        $categories = array_map(function (BladeFxCategoryTransfer $category) {
            return $category->toArray(true, true);
        }, $categories);

        $categoryTree = $this->getFactory()->getReportsFacade()->assembleCategoryTree($categories);

        return array_values($categoryTree);
    }

    /**
     * @param string $attribute
     *
     * @return array<string, string>
     */
    protected function buildParams(string $attribute = ''): array
    {
      return [ReportsConstants::ATTRIBUTE => ''];
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
