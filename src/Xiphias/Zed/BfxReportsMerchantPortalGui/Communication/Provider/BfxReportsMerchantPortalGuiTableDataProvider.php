<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Pyz\Zed\BfxReportsMerchantPortalGui\Communication\Provider;

use Generated\Shared\Transfer\BladeFxCriteriaTransfer;
use Generated\Shared\Transfer\BladeFxReportTransfer;
use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Xiphias\Shared\Reports\ReportsConstants;
use Xiphias\Zed\Reports\Business\ReportsFacadeInterface;

class BfxReportsMerchantPortalGuiTableDataProvider extends AbstractGuiTableDataProvider implements GuiTableDataProviderInterface
{
    /**
     * @param \Xiphias\Zed\Reports\Business\ReportsFacadeInterface $facade
     * @param array $params
     */
    public function __construct(
        protected ReportsFacadeInterface $facade,
        protected array $params = [],
    ) {
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer
    {
        $guiTableDataResponseTransfer = new GuiTableDataResponseTransfer();
        $reportList = $this
            ->facade
            ->getAllReports($this->params[ReportsConstants::ATTRIBUTE])
            ->getReportsList()
            ->getArrayCopy();
        /** @var \Generated\Shared\Transfer\BladeFxCriteriaTransfer $criteriaTransfer */
        if ($criteriaTransfer->getSearchTerm()) {
            $reportList = $this->search($reportList, $criteriaTransfer->getSearchTerm());
        }
        /**
         * @var \Generated\Shared\Transfer\BladeFxReportTransfer $reportListItem
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
            ->setTotal(count($reportList))
            ->setPageSize($criteriaTransfer->getPageSize())
            ->setPage($criteriaTransfer->getPage());
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
            /** @var \Generated\Shared\Transfer\BladeFxReportTransfer $row */
            if (str_contains($row->getRepName(), $searchTerm)) {
                $searchRows[] = $row;
            }
        }

        return $searchRows;
    }
}
