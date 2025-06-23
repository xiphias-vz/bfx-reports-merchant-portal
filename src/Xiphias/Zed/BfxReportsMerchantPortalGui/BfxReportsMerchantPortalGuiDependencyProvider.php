<?php


declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Xiphias\Client\ReportsApi\ReportsApiClient;
use Xiphias\Client\ReportsApi\ReportsApiClientInterface;
use Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Plugin\User\CreateBfxUserOnBfxMerchantPortalPlugin;
use Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Plugin\User\DeleteBfxUserOnBfxMerchantPortalPlugin;
use Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Plugin\User\UpdateBfxUserOnBfxMerchantPortalPlugin;

class BfxReportsMerchantPortalGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const REPORTS_FACADE = 'REPORTS_FACADE';

    /**
     * @var string
     */
    public const SPRYKER_BLADEFX_USER_FACADE = 'SPRYKER_BLADEFX_USER_FACADE';

    /**
     * @var string
     */
    public const MERCHANT_USER_FACADE = 'MERCHANT_USER_FACADE';

    /**
     * @var string
     */
    public const USER_FACADE = 'USER_FACADE';

    /**
     * @var string
     */
    public const SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR = 'gui_table_http_data_request_executor';

    /**
     * @var string
     */
    public const SERVICE_GUI_TABLE_FACTORY = 'gui_table_factory';

    /**
     * @var string
     */
    public const SERVICE_ZED_UI_FACTORY = 'SERVICE_ZED_UI_FACTORY';

    /**
     * @var string
     */
    public const BFX_USER_HANDLING_PLUGINS = 'BFX_USER_HANDLING_PLUGINS';

    /**
     * @var string
     */
    public const BLADE_FX_CLIENT = 'BLADE_FX_CLIENT';

    /**
     * @var string
     */
    public const SESSION_CLIENT = 'SESSION_CLIENT';

    /**
     * @uses \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     *
     * @var string
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addReportsFacade($container);
        $container = $this->addMerchantUserFacade($container);
        $container = $this->addGuiTableFactory($container);
        $container = $this->addGuiTableHttpDataRequestHandler($container);
        $container = $this->addZedUiFactory($container);
        $container = $this->addRequestStackService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addBladeFxClient($container);
        $container = $this->addSessionClient($container);
        $container = $this->addSprykerBladeFxUserFacade($container);
        $container = $this->addBfxUserHandlingPlugins($container);
        $container = $this->addUserFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addReportsFacade(Container $container): Container
    {
        $container->set(static::REPORTS_FACADE, function (Container $container) {
            return $container->getLocator()->reports()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSprykerBladeFxUserFacade(Container $container): Container
    {
        $container->set(static::SPRYKER_BLADEFX_USER_FACADE, function (Container $container) {
            return $container->getLocator()->sprykerBladeFxUser()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantUserFacade(Container $container): Container
    {
        $container->set(static::MERCHANT_USER_FACADE, function (Container $container) {
            return $container->getLocator()->merchantUser()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserFacade(Container $container): Container
    {
        $container->set(static::USER_FACADE, function (Container $container) {
            return $container->getLocator()->user()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGuiTableHttpDataRequestHandler(Container $container): Container
    {
        $container->set(static::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGuiTableFactory(Container $container): Container
    {
        $container->set(static::SERVICE_GUI_TABLE_FACTORY, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_GUI_TABLE_FACTORY);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addZedUiFactory(Container $container): Container
    {
        $container->set(static::SERVICE_ZED_UI_FACTORY, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_ZED_UI_FACTORY);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addBladeFxClient(Container $container): Container
    {
        $container->set(
            static::BLADE_FX_CLIENT,
            static function (): ReportsApiClientInterface {
                return new ReportsApiClient();
            },
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSessionClient(Container $container): Container
    {
        $container->set(static::SESSION_CLIENT, function (Container $container) {
            return $container->getLocator()->session()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRequestStackService(Container $container): Container
    {
        $container->set(static::SERVICE_REQUEST_STACK, function (Container $container) {
            return $container->hasApplicationService(static::SERVICE_REQUEST_STACK)
                ? $container->getApplicationService(static::SERVICE_REQUEST_STACK)
                : null;
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addBfxUserHandlingPlugins(Container $container): Container
    {
        $container->set(static::BFX_USER_HANDLING_PLUGINS, function () {
            return $this->getBfxUserHandlingPlugins();
        });

        return $container;
    }

    /**
     * @return array<int, \Xiphias\Zed\SprykerBladeFxUser\Communication\Plugin\User\BfxUserHandlerPluginInterface>
     */
    protected function getBfxUserHandlingPlugins(): array
    {
        return [
            new CreateBfxUserOnBfxMerchantPortalPlugin(),
            new UpdateBfxUserOnBfxMerchantPortalPlugin(),
            new DeleteBfxUserOnBfxMerchantPortalPlugin(),
        ];
    }
}
