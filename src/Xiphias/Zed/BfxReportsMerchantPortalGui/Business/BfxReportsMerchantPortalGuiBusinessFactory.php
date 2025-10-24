<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Business;

use Spryker\Client\Session\SessionClientInterface;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Messenger\Business\MessengerFacadeInterface;
use Spryker\Zed\User\Business\UserFacadeInterface;
use Xiphias\BladeFxApi\ReportsApiClientInterface;
use Xiphias\Zed\BfxReportsMerchantPortalGui\BfxReportsMerchantPortalGuiDependencyProvider;
use Xiphias\Zed\BfxReportsMerchantPortalGui\Business\Filter\BfxReportsNavigationItemFilter;
use Xiphias\Zed\BfxReportsMerchantPortalGui\Business\Filter\BfxReportsNavigationItemFilterInterface;
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
     * @return \Xiphias\Zed\BfxReportsMerchantPortalGui\Business\Handler\BfxReportsMerchantPortalUserHandlerInterface
     */
    public function createBfxReportsMerchantPortalUserHandler(): BfxReportsMerchantPortalUserHandlerInterface
    {
        return new BfxReportsMerchantPortalUserHandler(
            $this->getSessionClient(),
            $this->getBladeFxClient(),
            $this->getConfig(),
            $this->getRepository(),
            $this->getMessengerFacade(),
            $this->getEventFacade(),
            $this->getBfxUserHandlingPlugins(),
        );
    }

    /**
     * @return \Xiphias\Zed\BfxReportsMerchantPortalGui\Business\Filter\BfxReportsNavigationItemFilterInterface
     */
    public function createBfxReportsNavigationItemFilter(): BfxReportsNavigationItemFilterInterface
    {
        return new BfxReportsNavigationItemFilter(
            $this->getUserFacade(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Xiphias\BladeFxApi\ReportsApiClientInterface
     */
    protected function getBladeFxClient(): ReportsApiClientInterface
    {
        return $this->getProvidedDependency(BfxReportsMerchantPortalGuiDependencyProvider::BLADE_FX_CLIENT);
    }

    /**
     * @return \Spryker\Zed\User\Business\UserFacadeInterface
     */
    protected function getUserFacade(): UserFacadeInterface
    {
        return $this->getProvidedDependency(BfxReportsMerchantPortalGuiDependencyProvider::USER_FACADE);
    }

    /**
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    protected function getSessionClient(): SessionClientInterface
    {
        return $this->getProvidedDependency(BfxReportsMerchantPortalGuiDependencyProvider::SESSION_CLIENT);
    }

    /**
     * @return \Spryker\Zed\Messenger\Business\MessengerFacadeInterface
     */
    protected function getMessengerFacade(): MessengerFacadeInterface
    {
        return $this->getProvidedDependency(SprykerBladeFxUserDependencyProvider::MESSENGER_FACADE);
    }

    /**
     * @return \Spryker\Zed\Event\Business\EventFacadeInterface
     */
    protected function getEventFacade(): EventFacadeInterface
    {
        return $this->getProvidedDependency(SprykerBladeFxUserDependencyProvider::EVENT_FACADE);
    }

    /**
     * @return array<\Xiphias\Zed\SprykerBladeFxUser\Communication\Plugin\User\BfxUserHandlerPluginInterface>
     */
    public function getBfxUserHandlingPlugins(): array
    {
        return $this->getProvidedDependency(BfxReportsMerchantPortalGuiDependencyProvider::BFX_USER_HANDLING_PLUGINS);
    }

    /**
     * @return \Xiphias\Zed\SprykerBladeFxUser\Business\SprykerBladeFxUserFacadeInterface
     */
    public function getSprykerBladeFxUserFacade(): SprykerBladeFxUserFacadeInterface
    {
        return $this->getProvidedDependency(BfxReportsMerchantPortalGuiDependencyProvider::SPRYKER_BLADEFX_USER_FACADE);
    }
}
