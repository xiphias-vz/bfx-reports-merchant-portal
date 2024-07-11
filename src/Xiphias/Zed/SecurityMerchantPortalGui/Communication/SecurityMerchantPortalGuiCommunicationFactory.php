<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Xiphias\Zed\SecurityMerchantPortalGui\Communication;

use Xiphias\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\Handler\MerchantUserAuthenticationSuccessHandler;
use Xiphias\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiDependencyProvider;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\SecurityMerchantPortalGuiCommunicationFactory as SprykerSecurityMerchantPortalGuiCommunicationFactory;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Xiphias\Zed\Reports\Business\ReportsFacadeInterface;

/**
 * @method \Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiConfig getConfig()
 */
class SecurityMerchantPortalGuiCommunicationFactory extends SprykerSecurityMerchantPortalGuiCommunicationFactory
{
    /**
     * @return \Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface
     */
    public function createMerchantUserAuthenticationSuccessHandler(): AuthenticationSuccessHandlerInterface
    {
        return new MerchantUserAuthenticationSuccessHandler();
    }

    /**
     * @return \Xiphias\Zed\Reports\Business\ReportsFacadeInterface
     */
    public function addReportsFacade(): ReportsFacadeInterface
    {
        return $this->getProvidedDependency(SecurityMerchantPortalGuiDependencyProvider::FACADE_REPORTS);
    }
}
