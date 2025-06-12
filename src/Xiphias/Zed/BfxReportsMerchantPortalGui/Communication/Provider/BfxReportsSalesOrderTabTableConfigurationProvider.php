<?php


declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Provider;

use Generated\Shared\Transfer\BladeFxReportTransfer;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Xiphias\Shared\Reports\ReportsConstants;

class BfxReportsSalesOrderTabTableConfigurationProvider
{
    /**
     * @var \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    protected $guiTableFactory;

    /**
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     */
    public function __construct(GuiTableFactoryInterface $guiTableFactory)
    {
        $this->guiTableFactory = $guiTableFactory;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(MerchantOrderTransfer $merchantOrderTransfer): GuiTableConfigurationTransfer
    {
        $idOrder = $merchantOrderTransfer->getIdOrder();
        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();

        $guiTableConfigurationBuilder = $this->addColumns($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addRowActions($guiTableConfigurationBuilder, $idOrder);

        $guiTableConfigurationBuilder
            ->setDataSourceUrl('/bfx-reports-merchant-portal-gui/bfx-reports/sales-reports-table-data')
            ->setSearchPlaceholder('Search by report name')
            ->setDefaultPageSize(25);

        return $guiTableConfigurationBuilder->createConfiguration();
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addColumns(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder
            ->addColumnText(BladeFxReportTransfer::REP_ID, 'Report ID', false, false)
            ->addColumnText(BladeFxReportTransfer::REP_NAME, 'Report name', false, false)
            ->addColumnText(BladeFxReportTransfer::REP_DESC, 'Description', false, false)
            ->addColumnText(BladeFxReportTransfer::CAT_NAME, 'Category', false, false);

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     * @param int $idOrder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addRowActions(
        GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder,
        int $idOrder
    ): GuiTableConfigurationBuilderInterface {
        $guiTableConfigurationBuilder->addRowActionHttp(
            'download-pdf',
            'Download as PDF',
            sprintf(
                '/bfx-reports-merchant-portal-gui/bfx-reports/report-download-response-builder?repId=${row.%s}&repName=${row.%s}&format=pdf&paramName=' . ReportsConstants::BLADE_FX_ORDER_PARAM_NAME . '&paramValue=%s',
                BladeFxReportTransfer::REP_ID,
                BladeFxReportTransfer::REP_NAME,
                $idOrder,
            ),
        );

        $guiTableConfigurationBuilder->addRowActionDrawerUrlHtmlRenderer(
            'report-preview',
            'Preview',
            sprintf(
                '/bfx-reports-merchant-portal-gui/bfx-reports/report-preview-with-parameter?repId=${row.%s}&paramName=' . ReportsConstants::BLADE_FX_ORDER_PARAM_NAME . '&paramValue=%s',
                BladeFxReportTransfer::REP_ID,
                $idOrder,
            ),
        )->setRowClickAction('report-preview');

        return $guiTableConfigurationBuilder;
    }
}
