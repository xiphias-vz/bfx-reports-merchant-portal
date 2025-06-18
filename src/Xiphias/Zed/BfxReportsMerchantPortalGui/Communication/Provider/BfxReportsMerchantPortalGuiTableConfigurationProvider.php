<?php


declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Provider;

use Generated\Shared\Transfer\BladeFxReportTransfer;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface;

class BfxReportsMerchantPortalGuiTableConfigurationProvider
{
    /**
     * @var \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    protected $guiTableFactory;

    /**
     * @var \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface
     */
    private MerchantUserFacadeInterface $merchantUserFacade;

    /**
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     * @param \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(
        GuiTableFactoryInterface $guiTableFactory,
        MerchantUserFacadeInterface $merchantUserFacade
    ) {
        $this->guiTableFactory = $guiTableFactory;
        $this->merchantUserFacade = $merchantUserFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(): GuiTableConfigurationTransfer
    {
        $idMerchant = $this->merchantUserFacade
            ->getCurrentMerchantUser()
            ->getIdMerchantOrFail();

        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();

        $guiTableConfigurationBuilder = $this->addColumns($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addRowActions($guiTableConfigurationBuilder, $idMerchant);

        $guiTableConfigurationBuilder
            ->setDataSourceUrl('/bfx-reports-merchant-portal-gui/bfx-reports/main-reports-table-data')
            ->setSearchPlaceholder('Search by report name')
            ->setIsPaginationEnabled(false)
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
     * @param int $idMerchant
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addRowActions(
        GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder,
        int $idMerchant
    ): GuiTableConfigurationBuilderInterface {
        $guiTableConfigurationBuilder->addRowActionDrawerUrlHtmlRenderer(
            'report-iframe',
            'Preview',
            sprintf(
                'bfx-reports/report-iframe?repId=${row.%s}',
                BladeFxReportTransfer::REP_ID,
            ),
        )->setRowClickAction('report-iframe');

        return $guiTableConfigurationBuilder;
    }
}
