<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Xiphias\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\Handler;

use Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\Handler\MerchantUserAuthenticationSuccessHandler as SprykerMerchantUserAuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @method \Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiConfig getConfig()
 * @method \Pyz\Zed\SecurityMerchantPortalGui\Communication\SecurityMerchantPortalGuiCommunicationFactory getFactory()
 */
class MerchantUserAuthenticationSuccessHandler extends SprykerMerchantUserAuthenticationSuccessHandler
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        /** @var \Spryker\Zed\SecurityMerchantPortalGui\Communication\Security\MerchantUserInterface $user */
        $user = $token->getUser();
        $this->getFactory()->getMerchantUserFacade()->authorizeMerchantUser($user->getMerchantUserTransfer());
        $this->getFactory()->addReportsFacade()->authenticateBladeFxUserOnMerchantPortal($request, $user->getMerchantUserTransfer());

        return $this->createRedirectResponse($request);
    }
}
