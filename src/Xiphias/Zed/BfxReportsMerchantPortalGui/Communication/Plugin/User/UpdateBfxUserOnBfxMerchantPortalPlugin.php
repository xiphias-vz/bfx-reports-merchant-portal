<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Plugin\User;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Xiphias\Zed\SprykerBladeFxUser\Communication\Plugin\User\BfxUserHandlerPluginInterface;

/**
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\Business\BfxReportsMerchantPortalGuiFacade getFacade();
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\Business\BfxReportsMerchantPortalGuiBusinessFactory getFactory();
 */
class UpdateBfxUserOnBfxMerchantPortalPlugin extends AbstractPlugin implements BfxUserHandlerPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    public function isApplicable(UserTransfer $userTransfer): bool
    {
        return $this->getFacade()->isUserApplicableForUpdateOnBfx($userTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    public function execute(UserTransfer $userTransfer): void
    {
        $this->getFacade()->createOrUpdateUserOnBladeFx($userTransfer, $this->getBladeFxAppRole($userTransfer), true, true);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return string
     */
    protected function getBladeFxAppRole(UserTransfer $userTransfer): string
    {
        return $this->getFacade()->getBladeFxAppRole($userTransfer);
    }
}
