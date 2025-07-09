<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Business\Handler;

use Exception;
use Generated\Shared\Transfer\BladeFxCreateOrUpdateUserCustomFieldsTransfer;
use Generated\Shared\Transfer\BladeFxCreateOrUpdateUserRequestTransfer;
use Generated\Shared\Transfer\BladeFxCreateOrUpdateUserResponseTransfer;
use Generated\Shared\Transfer\BladeFxTokenTransfer;
use Generated\Shared\Transfer\BladeFxUpdatePasswordRequestTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Client\Session\SessionClientInterface;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use Spryker\Zed\Messenger\Business\MessengerFacadeInterface;
use Xiphias\Client\ReportsApi\ReportsApiClientInterface;
use Xiphias\Shared\Reports\ReportsConstants;
use Xiphias\Zed\BfxReportsMerchantPortalGui\BfxReportsMerchantPortalGuiConfig;
use Xiphias\Zed\BfxReportsMerchantPortalGui\Persistence\BfxReportsMerchantPortalGuiRepositoryInterface;

class BfxReportsMerchantPortalUserHandler implements BfxReportsMerchantPortalUserHandlerInterface
{
    /**
     * @param \Spryker\Client\Session\SessionClientInterface $sessionClient
     * @param \Xiphias\Client\ReportsApi\ReportsApiClientInterface $reportsApiClient
     * @param \Xiphias\Zed\BfxReportsMerchantPortalGui\BfxReportsMerchantPortalGuiConfig $config
     * @param \Xiphias\Zed\BfxReportsMerchantPortalGui\Persistence\BfxReportsMerchantPortalGuiRepositoryInterface $repository
     * @param array<\Xiphias\Zed\SprykerBladeFxUser\Communication\Plugin\User\BfxUserHandlerPluginInterface> $bfxUserHandlerPlugins
     */
    public function __construct(
        protected SessionClientInterface $sessionClient,
        protected ReportsApiClientInterface $reportsApiClient,
        protected BfxReportsMerchantPortalGuiConfig $config,
        protected BfxReportsMerchantPortalGuiRepositoryInterface $repository,
        protected MessengerFacadeInterface $messengerFacade,
        protected EventFacadeInterface $eventFacade,
        protected array $bfxUserHandlerPlugins
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
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
     * @param bool $isMerchantUser
     *
     * @return void
     */
    public function createOrUpdateUserOnBladeFx(UserTransfer $userTransfer, bool $isActive = true, bool $isMerchantUser = false): void
    {
        $requestTransfer = $this->generateAuthenticatedCreateOrUpdateUserOnBladeFxRequestTransfer($userTransfer, $isActive, $isMerchantUser);

        try {
            $responseTransfer = $this->reportsApiClient->sendCreateOrUpdateUserOnBfxRequest($requestTransfer);

            if ($isActive) {
                if ($responseTransfer->getSuccess()) {
                    $passwordUpdateRequestTransfer = $this->generateAuthenticatedUpdatePasswordOnBladeFxRequest($userTransfer, $responseTransfer);
                    $this->reportsApiClient->sendUpdatePasswordOnBladeFxRequest($passwordUpdateRequestTransfer);

                    return;
                }

                if ($responseTransfer->getLicenceIssue()) {
                    $this->addErrorMessage(ReportsConstants::USER_CREATE_FAILED_USER_CAP_ERROR);
                    $this->eventFacade->trigger(ReportsConstants::EVENT_USER_POST_SAVE_LICENSE_ISSUE, $userTransfer);
                }
            }

            if ($responseTransfer->getErrorMessage()) {
                $this->addErrorMessage($responseTransfer->getErrorMessage());
            }
        } catch (Exception $exception) {
            return;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param bool $isMerchantUser
     * @param bool $isActive
     *
     * @return void
     */
    public function deleteUserOnBladeFx(UserTransfer $userTransfer, bool $isMerchantUser, bool $isActive = false): void
    {
        $this->createOrUpdateUserOnBladeFx($userTransfer, false);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param bool $isActive
     * @param bool $isMerchantUser
     *
     * @return \Generated\Shared\Transfer\BladeFxCreateOrUpdateUserRequestTransfer
     */
    public function generateAuthenticatedCreateOrUpdateUserOnBladeFxRequestTransfer(
        UserTransfer $userTransfer,
        bool $isActive = true,
        bool $isMerchantUser = false
    ): BladeFxCreateOrUpdateUserRequestTransfer {
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
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\BladeFxCreateOrUpdateUserResponseTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\BladeFxUpdatePasswordRequestTransfer
     */
    public function generateAuthenticatedUpdatePasswordOnBladeFxRequest(
        UserTransfer $userTransfer,
        BladeFxCreateOrUpdateUserResponseTransfer $responseTransfer
    ): BladeFxUpdatePasswordRequestTransfer {
        return (new BladeFxUpdatePasswordRequestTransfer())
            ->setToken((new BladeFxTokenTransfer())->setToken($this->getToken()))
            ->setBladeFxUserId($responseTransfer->getId())
            ->setPassword($userTransfer->getPassword());
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
        bool $isMerchantUser
    ): BladeFxCreateOrUpdateUserRequestTransfer {
        if ($isMerchantUser) {
            return $bladeFxCreateOrUpdateUserRequestTransfer
                ->addCustomFields((new BladeFxCreateOrUpdateUserCustomFieldsTransfer())
                    ->setFieldName($this->config->getMerchantIdKey())
                    ->setFieldValue((string)$this->repository->getUserMerchantId($userId)));
        }

        return $bladeFxCreateOrUpdateUserRequestTransfer;
    }

    /**
     * @param string $message
     *
     * @return void
     */
    protected function addErrorMessage(string $message): void
    {
        $this->messengerFacade->addErrorMessage((new MessageTransfer())->setValue($message));
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
