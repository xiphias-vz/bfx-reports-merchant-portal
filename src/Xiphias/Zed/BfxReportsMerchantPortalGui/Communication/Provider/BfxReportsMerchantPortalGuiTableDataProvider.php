<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Provider;

use Generated\Shared\Transfer\BladeFxCriteriaTransfer;
use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Symfony\Component\HttpFoundation\Request;
use Xiphias\BladeFxApi\DTO\BladeFxReportTransfer;
use Xiphias\Shared\Reports\ReportsConstants;
use Xiphias\Zed\Reports\Business\ReportsFacadeInterface;

class BfxReportsMerchantPortalGuiTableDataProvider extends AbstractGuiTableDataProvider implements GuiTableDataProviderInterface
{
    /**
     * @param \Xiphias\Zed\Reports\Business\ReportsFacadeInterface $facade
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $params
     */
    public function __construct(
        protected ReportsFacadeInterface $facade,
        protected Request $request,
        protected array $params = []
    ) {
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\BladeFxCriteriaTransfer $bladeFxCriteriaTransfer */
        $bladeFxCriteriaTransfer = $criteriaTransfer;
        $guiTableDataResponseTransfer = new GuiTableDataResponseTransfer();

        $reportList = $this
            ->facade
            ->processGetReportsRequest($this->request, $this->params[ReportsConstants::ATTRIBUTE]);

        $pageSize = $bladeFxCriteriaTransfer->getPageSize();
        $startingIndex = ($bladeFxCriteriaTransfer->getPage() - 1) * $pageSize;

        if ($bladeFxCriteriaTransfer->getSearchTerm()) {
            $reportList = $this->search($reportList, $bladeFxCriteriaTransfer->getSearchTerm());
        }

        $reportTotal = count($reportList);
        $reportList = array_slice($reportList, $startingIndex, $bladeFxCriteriaTransfer->getPageSize());
        /**
         * @var \Xiphias\BladeFxApi\DTO\BladeFxReportTransfer $reportListItem
         */
        foreach ($reportList as $reportListItem) {
            $responseData = [
                BladeFxReportTransfer::IS_FAVORITE => $reportListItem->getIsFavorite(),
                BladeFxReportTransfer::REP_ID => $reportListItem->getRepId(),
                BladeFxReportTransfer::REP_NAME => $reportListItem->getRepName(),
                BladeFxReportTransfer::REP_DESC => $reportListItem->getRepDesc(),
                BladeFxReportTransfer::CAT_NAME => $reportListItem->getCatName(),
                BladeFxReportTransfer::IS_ACTIVE => $reportListItem->getIsActive(),
                BladeFxReportTransfer::IS_DRILLDOWN => $reportListItem->getIsDrillDown(),
            ];

            $guiTableDataResponseTransfer->addRow((new GuiTableRowDataResponseTransfer())->setResponseData($responseData));
        }

        return $guiTableDataResponseTransfer
            ->setTotal($reportTotal)
            ->setPageSize($bladeFxCriteriaTransfer->getPageSize())
            ->setPage($bladeFxCriteriaTransfer->getPage());
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer
    {
        return (new BladeFxCriteriaTransfer());
    }

    /**
     * @param array $rows
     * @param string $searchTerm
     *
     * @return array
     */
    protected function search(array $rows, string $searchTerm): array
    {
        $searchRows = [];
        foreach ($rows as $row) {
            /** @var \Xiphias\BladeFxApi\DTO\BladeFxReportTransfer $row */
            if (str_contains(strtolower($row->getRepName()), strtolower($searchTerm))) {
                $searchRows[] = $row;
            }
        }

        return $searchRows;
    }
}
