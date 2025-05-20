<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Plugin\User;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserPostSavePluginInterface;

/**
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\Persistence\BfxReportsMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\Business\BfxReportsMerchantPortalGuiFacade getFacade();
 */
class CreateOrUpdateUserOnBfxMerchantPortalPostSavePlugin extends AbstractPlugin implements UserPostSavePluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function postSave(UserTransfer $userTransfer): UserTransfer
    {
        $this->getFacade()->executeBfxUserHandlingPlugins($userTransfer);

        return $userTransfer;
    }
}
