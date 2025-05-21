<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Business\Handler;

use Generated\Shared\Transfer\BladeFxCreateOrUpdateUserCustomFieldsTransfer;
use Generated\Shared\Transfer\BladeFxCreateOrUpdateUserRequestTransfer;
use Generated\Shared\Transfer\BladeFxTokenTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Client\Session\SessionClientInterface;
use Xiphias\Client\ReportsApi\ReportsApiClientInterface;
use Xiphias\Shared\Reports\ReportsConstants;
use Xiphias\Zed\BfxReportsMerchantPortalGui\BfxReportsMerchantPortalGuiConfig;
use Xiphias\Zed\BfxReportsMerchantPortalGui\Persistence\BfxReportsMerchantPortalGuiRepositoryInterface;
use Xiphias\Zed\SprykerBladeFxUser\Communication\Plugin\User\BfxUserHandlerPluginInterface;

class BfxReportsMerchantPortalUserHandler implements BfxReportsMerchantPortalUserHandlerInterface
{
    /**
     * @param array<BfxUserHandlerPluginInterface> $bfxUserHandlerPlugins
     */
    public function __construct(
        protected SessionClientInterface $sessionClient,
        protected ReportsApiClientInterface $reportsApiClient,
        protected BfxReportsMerchantPortalGuiConfig $config,
        protected BfxReportsMerchantPortalGuiRepositoryInterface $repository,
        protected array $bfxUserHandlerPlugins
    ) {}

    /**
     * @param UserTransfer $userTransfer
     *
     * @return UserTransfer
     */
    public function executeBfxUserHandlingPlugins(UserTransfer $userTransfer): UserTransfer
    {
        foreach ($this->bfxUserHandlerPlugins as $bfxUserHandlerPlugin) {
            if ($bfxUserHandlerPlugin->isApplicable($userTransfer)) {
                $bfxUserHandlerPlugin->execute($userTransfer);
            }
        }

        return $userTransfer;
    }

    /**
     * @param int $userId
     *
     * @return bool
     */
    public function isMerchantUser(int $userId): bool
    {
        return $this->repository->isMerchantUser($userId);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param bool $isActive
     * @param bool $isItFromBO
     *
     * @return void
     */
    public function createOrUpdateUserOnBladeFx(UserTransfer $userTransfer, bool $isActive = true, bool $isMerchantUser = false): void
    {
        $requestTransfer = $this->generateAuthenticatedCreateOrUpdateUserOnBladeFxRequestTransfer($userTransfer, $isActive, $isMerchantUser);

        try {
//            $this->reportsApiClient->sendCreateOrUpdateUserOnBfxRequest($requestTransfer);
        } catch (Exception $exception) {
            return;
        }
    }

    /**
     * @param UserTransfer $userTransfer
     * @param bool $isMerchantUser
     * @param bool $isActive
     */
    public function deleteUserOnBladeFx(UserTransfer $userTransfer, bool $isMerchantUser, bool $isActive = false): void
    {
        $this->createOrUpdateUserOnBladeFx($userTransfer, false);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param bool $isActive
     * @param bool $isItFromBO
     *
     * @return \Generated\Shared\Transfer\BladeFxCreateOrUpdateUserRequestTransfer
     */
    public function generateAuthenticatedCreateOrUpdateUserOnBladeFxRequestTransfer(
        UserTransfer $userTransfer,
        bool $isActive = true,
        bool $isMerchantUser = false,
    ): BladeFxCreateOrUpdateUserRequestTransfer
    {
        $bladeFxCreateOrUpdateUserRequestTransfer = (new BladeFxCreateOrUpdateUserRequestTransfer())
            ->setToken((new BladeFxTokenTransfer())->setToken($this->getToken()))
            ->setEmail($userTransfer->getUsername())
            ->setFirstName($userTransfer->getFirstName())
            ->setLastName($userTransfer->getLastName())
            ->setPassword($userTransfer->getPassword())
            ->setRoleName($isMerchantUser ? ReportsConstants::SPRYKER_MP_ROLE : ReportsConstants::SRYKER_BO_ROLE)
            ->setCompanyId($this->getUserIdCompany())
            ->setLanguageId($this->getUserIdLanguage())
            ->setIsActive($isActive)
            ->addCustomFields((new BladeFxCreateOrUpdateUserCustomFieldsTransfer())
                ->setFieldName($this->config->getSprykerUserIdKey())
                ->setFieldValue((string)($userTransfer->getIdUser())));

        return $this->appendMerchantIdToRequest($bladeFxCreateOrUpdateUserRequestTransfer, $userTransfer->getIdUser(), $isMerchantUser);
    }

    /**
     * @param \Generated\Shared\Transfer\BladeFxCreateOrUpdateUserRequestTransfer $bladeFxCreateOrUpdateUserRequestTransfer
     * @param int $userId
     * @param bool $isMerchantUser
     *
     * @return \Generated\Shared\Transfer\BladeFxCreateOrUpdateUserRequestTransfer
     */
    protected function appendMerchantIdToRequest(
        BladeFxCreateOrUpdateUserRequestTransfer $bladeFxCreateOrUpdateUserRequestTransfer,
        int $userId,
        bool $isMerchantUser,
    ): BladeFxCreateOrUpdateUserRequestTransfer {
        if ($isMerchantUser) {
            return $bladeFxCreateOrUpdateUserRequestTransfer
                ->addCustomFields((new BladeFxCreateOrUpdateUserCustomFieldsTransfer())
                    ->setFieldName($this->config->getMerchantIdKey())
                    ->setFieldValue($this->repository->getUserMerchantId($userId)));
        }

        return $bladeFxCreateOrUpdateUserRequestTransfer;
    }

    /**
     * @return string|null
     */
    protected function getToken(): string|null
    {
        return $this->sessionClient->has($this->config->getBfxTokenSessionKey()) ? $this->sessionClient->get($this->config->getBfxTokenSessionKey()) : null;
    }

    /**
     * @return int|null
     */
    protected function getUserIdCompany(): int|null
    {
        return $this->sessionClient->has($this->config->getBfxUserCompanyIdSessionKey()) ? $this->sessionClient->get($this->config->getBfxUserCompanyIdSessionKey()) : null;
    }

    /**
     * @return int|null
     */
    protected function getUserIdLanguage(): int|null
    {
        return $this->sessionClient->has($this->config->getBfxUserLanguageIdSessionKey()) ? $this->sessionClient->get($this->config->getBfxUserLanguageIdSessionKey()) : null;
    }
}
