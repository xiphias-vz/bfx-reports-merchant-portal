<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Plugin\User;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Xiphias\Shared\Reports\ReportsConstants;
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
     * @param UserTransfer $userTransfer
     *
     * @return string
     */
    protected function getBladeFxAppRole(UserTransfer $userTransfer): string
    {
        if ($this->isMerchantUser($userTransfer)) {
            if (!$this->isAdmin($userTransfer)) {
                return ReportsConstants::SPRYKER_MP_ROLE;
            }
        }

        return ReportsConstants::SPRKYER_BO_ROLE;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    protected function isMerchantUser(UserTransfer $userTransfer): bool
    {
        return $this->getFacade()->isMerchantUser($userTransfer->getIdUser());
    }

    /**
     * @param UserTransfer $userTransfer
     *
     * @return bool
     */
    protected function isAdmin(UserTransfer $userTransfer): bool
    {
        return $this->getFacade()->isAdmin($userTransfer->getGroup());
    }
}
