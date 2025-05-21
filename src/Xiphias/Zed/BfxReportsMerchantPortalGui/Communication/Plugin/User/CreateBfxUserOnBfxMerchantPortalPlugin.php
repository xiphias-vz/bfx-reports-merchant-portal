<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Plugin\User;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Xiphias\Zed\SprykerBladeFxUser\Communication\Plugin\User\BfxUserHandlerPluginInterface;

/**
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\Business\BfxReportsMerchantPortalGuiFacade getFacade();
 */
class CreateBfxUserOnBfxMerchantPortalPlugin extends AbstractPlugin implements BfxUserHandlerPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    public function isApplicable(UserTransfer $userTransfer): bool
    {
        return $this->getFacade()->isUserApplicableForCreateOnBfx($userTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    public function execute(UserTransfer $userTransfer): void
    {
        $this->getFacade()->createOrUpdateUserOnBladeFx($userTransfer, $this->isMerchantUser($userTransfer));
    }

    /**
     * @param UserTransfer $userTransfer
     *
     * @return bool
     */
    protected function isMerchantUser(UserTransfer $userTransfer): bool
    {
        return $this->getFacade()->isMerchantUser($userTransfer->getIdUser());
    }
}
