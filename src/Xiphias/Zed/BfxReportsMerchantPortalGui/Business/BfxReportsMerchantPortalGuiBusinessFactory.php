<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Business;

use Spryker\Client\Session\SessionClientInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Xiphias\Client\ReportsApi\ReportsApiClientInterface;
use Xiphias\Zed\BfxReportsMerchantPortalGui\BfxReportsMerchantPortalGuiDependencyProvider;
use Xiphias\Zed\BfxReportsMerchantPortalGui\Business\Handler\BfxReportsMerchantPortalUserHandler;
use Xiphias\Zed\BfxReportsMerchantPortalGui\Business\Handler\BfxReportsMerchantPortalUserHandlerInterface;
use Xiphias\Zed\SprykerBladeFxUser\Business\SprykerBladeFxUserFacadeInterface;
use Xiphias\Zed\SprykerBladeFxUser\SprykerBladeFxUserDependencyProvider;

/**
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\Persistence\BfxReportsMerchantPortalGuiRepositoryInterface getRepository();
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\BfxReportsMerchantPortalGuiConfig getConfig();
 */
class BfxReportsMerchantPortalGuiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return BfxReportsMerchantPortalUserHandlerInterface
     */
    public function createBfxReportsMerchantPortalUserHandler(): BfxReportsMerchantPortalUserHandlerInterface
    {
        return new BfxReportsMerchantPortalUserHandler(
            $this->getSessionClient(),
            $this->getBladeFxClient(),
            $this->getConfig(),
            $this->getRepository(),
            $this->getBfxUserHandlingPlugins()
        );
    }

    /**
     * @return \Xiphias\Client\ReportsApi\ReportsApiClientInterface
     */
    protected function getBladeFxClient(): ReportsApiClientInterface
    {
        return $this->getProvidedDependency(BfxReportsMerchantPortalGuiDependencyProvider::BLADE_FX_CLIENT);
    }

    /**
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    protected function getSessionClient(): SessionClientInterface
    {
        return $this->getProvidedDependency(BfxReportsMerchantPortalGuiDependencyProvider::SESSION_CLIENT);
    }

    /**
     * @return array<Xiphias\Zed\SprykerBladeFxUser\Communication\Plugin\User\BfxUserHandlerPluginInterface>
     */
    public function getBfxUserHandlingPlugins(): array
    {
        return $this->getProvidedDependency(BfxReportsMerchantPortalGuiDependencyProvider::BFX_USER_HANDLING_PLUGINS);
    }

    /**
     * @return SprykerBladeFxUserFacadeInterface
     */
    public function getSprykerBladeFxUserFacade(): SprykerBladeFxUserFacadeInterface
    {
        return $this->getProvidedDependency(BfxReportsMerchantPortalGuiDependencyProvider::SPRYKER_BLADEFX_USER_FACADE);
    }
}
