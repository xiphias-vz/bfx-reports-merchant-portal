<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\Plugin\User;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserPostSavePluginInterface;

/**
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\Business\BfxReportsMerchantPortalGuiFacade getFacade();
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\Communication\BfxReportsMerchantPortalGuiCommunicationFactory getFactory();
 * @method \Xiphias\Zed\BfxReportsMerchantPortalGui\Persistence\BfxReportsMerchantPortalGuiRepositoryInterface getRepository()
 */
class DeleteUserOnBfxMerchantPortalPostSavePlugin extends AbstractPlugin implements UserPostSavePluginInterface
{
    /**
     * @var string
     */
    protected const HTTP_METHOD_DELETE = 'DELETE';

    /**
     * @param UserTransfer $userTransfer
     *
     * @return UserTransfer
     */
    public function postSave(UserTransfer $userTransfer): UserTransfer
    {
        if ($userTransfer->getStatus() === 'deleted' && $this->getFacade()->hasUserBfxGroup($userTransfer->getIdUser())) {
            $isMerchantUser = $this->getFacade()->isMerchantUser($userTransfer->getIdUser());
            $this->getFacade()->deleteUserOnBladeFx($userTransfer, $isMerchantUser);
        }

        return $userTransfer;
    }
}
