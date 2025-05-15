<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Pyz\Zed\BfxReportsMerchantPortalGui\Communication;

use Pyz\Zed\BfxReportsMerchantPortalGui\BfxReportsMerchantPortalGuiDependencyProvider;
use Pyz\Zed\BfxReportsMerchantPortalGui\Communication\Provider\BfxReportsMerchantPortalGuiTableConfigurationProvider;
use Pyz\Zed\BfxReportsMerchantPortalGui\Communication\Provider\BfxReportsMerchantPortalGuiTableDataProvider;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface;
use Spryker\Shared\ZedUi\ZedUiFactoryInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface;
use Xiphias\Zed\Reports\Business\ReportsFacadeInterface;

/**
 * @method \Pyz\Zed\BfxReportsMerchantPortalGui\BfxReportsMerchantPortalGuiConfig getConfig();
 */
class BfxReportsMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Pyz\Zed\BfxReportsMerchantPortalGui\Communication\Provider\BfxReportsMerchantPortalGuiTableConfigurationProvider
     */
    public function createBfxReportsMerchantPortalGuiTableConfigurationProvider(): BfxReportsMerchantPortalGuiTableConfigurationProvider
    {
        return new BfxReportsMerchantPortalGuiTableConfigurationProvider(
            $this->getGuiTableFactory(),
            $this->getMerchantUserFacade(),
        );
    }

    /**
     * @param array $params
     *
     * @return \Pyz\Zed\BfxReportsMerchantPortalGui\Communication\Provider\BfxReportsMerchantPortalGuiTableDataProvider
     */
    public function createBfxReportsMerchantPortalGuiTableDataProvider(array $params): BfxReportsMerchantPortalGuiTableDataProvider
    {
        return new BfxReportsMerchantPortalGuiTableDataProvider(
            $this->getReportsFacade(),
            $params,
        );
    }

    /**
     * @return \Xiphias\Zed\Reports\Business\ReportsFacadeInterface
     */
    public function getReportsFacade(): ReportsFacadeInterface
    {
        return $this->getProvidedDependency(BfxReportsMerchantPortalGuiDependencyProvider::REPORTS_FACADE);
    }

    /**
     * @return \Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface
     */
    public function getGuiTableHttpDataRequestExecutor(): GuiTableDataRequestExecutorInterface
    {
        return $this->getProvidedDependency(BfxReportsMerchantPortalGuiDependencyProvider::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR);
    }

    /**
     * @return \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    public function getGuiTableFactory(): GuiTableFactoryInterface
    {
        return $this->getProvidedDependency(BfxReportsMerchantPortalGuiDependencyProvider::SERVICE_GUI_TABLE_FACTORY);
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): MerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(BfxReportsMerchantPortalGuiDependencyProvider::MERCHANT_USER_FACADE);
    }

    /**
     * @return \Spryker\Shared\ZedUi\ZedUiFactoryInterface
     */
    public function getZedUiFactory(): ZedUiFactoryInterface
    {
        return $this->getProvidedDependency(BfxReportsMerchantPortalGuiDependencyProvider::SERVICE_ZED_UI_FACTORY);
    }
}
