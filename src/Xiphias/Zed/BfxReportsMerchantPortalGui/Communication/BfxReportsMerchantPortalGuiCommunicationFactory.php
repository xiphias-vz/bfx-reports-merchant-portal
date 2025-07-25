<?php


declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication;

use Spryker\Client\Session\SessionClientInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface;
use Spryker\Shared\ZedUi\ZedUiFactoryInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Xiphias\Zed\BfxReportsMerchantPortalGui\BfxReportsMerchantPortalGuiDependencyProvider;
use Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Provider\BfxReportsMerchantPortalGuiTableConfigurationProvider;
use Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Provider\BfxReportsMerchantPortalGuiTableDataProvider;
use Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Provider\BfxReportsSalesOrderTabTableConfigurationProvider;
use Xiphias\Zed\Reports\Business\ReportsFacadeInterface;

/**
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\BfxReportsMerchantPortalGuiConfig getConfig();
 */
class BfxReportsMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Provider\BfxReportsMerchantPortalGuiTableConfigurationProvider
     */
    public function createBfxReportsMerchantPortalGuiTableConfigurationProvider(): BfxReportsMerchantPortalGuiTableConfigurationProvider
    {
        return new BfxReportsMerchantPortalGuiTableConfigurationProvider(
            $this->getGuiTableFactory(),
            $this->getMerchantUserFacade(),
        );
    }

    /**
     * @return \Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Provider\BfxReportsSalesOrderTabTableConfigurationProvider
     */
    public function createBfxReportsSalesOrderTabTableConfigurationProvider(): BfxReportsSalesOrderTabTableConfigurationProvider
    {
        return new BfxReportsSalesOrderTabTableConfigurationProvider($this->getGuiTableFactory());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $params
     *
     * @return \Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Provider\BfxReportsMerchantPortalGuiTableDataProvider
     */
    public function createBfxReportsMerchantPortalGuiTableDataProvider(Request $request, $params): BfxReportsMerchantPortalGuiTableDataProvider
    {
        return new BfxReportsMerchantPortalGuiTableDataProvider(
            $this->getReportsFacade(),
            $request,
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
     * @return \Spryker\Client\Session\SessionClientInterface;
     */
    public function getSessionClient(): SessionClientInterface
    {
        return $this->getProvidedDependency(BfxReportsMerchantPortalGuiDependencyProvider::SESSION_CLIENT);
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

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack|null
     */
    public function getRequestStackService(): ?RequestStack
    {
        return $this->getProvidedDependency(BfxReportsMerchantPortalGuiDependencyProvider::SERVICE_REQUEST_STACK);
    }
}
