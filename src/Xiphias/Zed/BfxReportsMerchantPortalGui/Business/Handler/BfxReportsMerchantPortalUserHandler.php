<?php

declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui\Business\Handler;

use Exception;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Client\Session\SessionClientInterface;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use Spryker\Zed\Messenger\Business\MessengerFacadeInterface;
use Xiphias\BladeFxApi\BladeFxApiClientInterface;
use Xiphias\BladeFxApi\DTO\BladeFxCreateOrUpdateUserCustomFieldsTransfer;
use Xiphias\BladeFxApi\DTO\BladeFxCreateOrUpdateUserRequestTransfer;
use Xiphias\BladeFxApi\DTO\BladeFxCreateOrUpdateUserResponseTransfer;
use Xiphias\BladeFxApi\DTO\BladeFxUpdatePasswordRequestTransfer;
use Xiphias\Shared\Reports\ReportsConstants;
use Xiphias\Zed\BfxReportsMerchantPortalGui\BfxReportsMerchantPortalGuiConfig;
use Xiphias\Zed\BfxReportsMerchantPortalGui\Persistence\BfxReportsMerchantPortalGuiRepositoryInterface;

class BfxReportsMerchantPortalUserHandler implements BfxReportsMerchantPortalUserHandlerInterface
{
    /**
     * @param \Spryker\Client\Session\SessionClientInterface $sessionClient
     * @param \Xiphias\BladeFxApi\BladeFxApiClientInterface $reportsApiClient
     * @param \Xiphias\Zed\BfxReportsMerchantPortalGui\BfxReportsMerchantPortalGuiConfig $config
     * @param \Xiphias\Zed\BfxReportsMerchantPortalGui\Persistence\BfxReportsMerchantPortalGuiRepositoryInterface $repository
     * @param \Spryker\Zed\Messenger\Business\MessengerFacadeInterface $messengerFacade
     * @param \Spryker\Zed\Event\Business\EventFacadeInterface $eventFacade
     * @param array<\Xiphias\Zed\SprykerBladeFxUser\Communication\Plugin\User\BfxUserHandlerPluginInterface> $bfxUserHandlerPlugins
     */
    public function __construct(
        protected SessionClientInterface $sessionClient,
        protected BladeFxApiClientInterface $reportsApiClient,
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
     * @param array<int> $aclGroupIds
     *
     * @return bool
     */
    public function isAdmin(array $aclGroupIds): bool
    {
        $rootGroupId = $this->repository->getRootGroupId();

        return in_array($rootGroupId, $aclGroupIds);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return string
     */
    public function getBladeFxAppRole(UserTransfer $userTransfer): string
    {
        if ($this->isMerchantUser($userTransfer->getIdUser())) {
            if (!$this->isAdmin($userTransfer->getGroup())) {
                return ReportsConstants::SPRYKER_MP_ROLE;
            }
        }

        return ReportsConstants::SPRYKER_BO_ROLE;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    public function hasRootGroupStatusChanged(UserTransfer $userTransfer): bool
    {
        $rootGroupId = $this->repository->getRootGroupId();
        $aclGroupIds = $userTransfer->getGroup();
        $isUserCurrentlyAdmin = in_array($rootGroupId, $aclGroupIds);
        $wasUserAlreadyAdmin = $this->repository->hasUserRootGroupInDB($userTransfer->getIdUser());

        return $isUserCurrentlyAdmin !== $wasUserAlreadyAdmin;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param string $bfxRole
     * @param bool $isActive
     * @param bool $isUpdate
     *
     * @return void
     */
    public function createOrUpdateUserOnBladeFx(UserTransfer $userTransfer, string $bfxRole, bool $isActive, bool $isUpdate): void
    {
        $fromSpryker = true;
        $shouldRemoveRole = false;
        if ($isUpdate) {
            $hasRootGroupStatusChanged = $this->hasRootGroupStatusChanged($userTransfer);
            if ($hasRootGroupStatusChanged) {
                $shouldRemoveRole = true;
            } else {
                $bfxRole = '';
            }
        }

        $requestTransfer = $this->generateAuthenticatedCreateOrUpdateUserOnBladeFxRequestTransfer($userTransfer, $bfxRole, $isActive);

        try {
            $responseTransfer = $this->reportsApiClient->sendCreateOrUpdateUserOnBfxRequest($requestTransfer, $fromSpryker);
            if ($isActive) {
                if ($responseTransfer->getSuccess()) {
                    if ($shouldRemoveRole) {
                        $roleToRemove = $bfxRole === ReportsConstants::SPRYKER_BO_ROLE
                            ? ReportsConstants::SPRYKER_MP_ROLE
                            : ReportsConstants::SPRYKER_BO_ROLE;

                        $requestTransfer->setRoleName($roleToRemove);
                        $this->reportsApiClient->sendCreateOrUpdateUserOnBfxRequest($requestTransfer, $fromSpryker);
                    }
                    $passwordUpdateRequestTransfer = $this->generateAuthenticatedUpdatePasswordOnBladeFxRequest($userTransfer, $responseTransfer);
                    $this->reportsApiClient->sendUpdatePasswordOnBladeFxRequest($passwordUpdateRequestTransfer, $fromSpryker);

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
     * @param string $bfxRole
     *
     * @return void
     */
    public function deleteUserOnBladeFx(UserTransfer $userTransfer, string $bfxRole): void
    {
        $this->createOrUpdateUserOnBladeFx($userTransfer, $bfxRole, false, false);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param string $bfxRole
     * @param bool $isActive
     *
     * @return \Xiphias\BladeFxApi\DTO\BladeFxCreateOrUpdateUserRequestTransfer
     */
    public function generateAuthenticatedCreateOrUpdateUserOnBladeFxRequestTransfer(
        UserTransfer $userTransfer,
        string $bfxRole,
        bool $isActive = true
    ): BladeFxCreateOrUpdateUserRequestTransfer {
        $bladeFxCreateOrUpdateUserRequestTransfer = (new BladeFxCreateOrUpdateUserRequestTransfer())
            ->setAccessToken($this->getToken())
            ->setEmail($userTransfer->getUsername())
            ->setFirstName($userTransfer->getFirstName())
            ->setLastName($userTransfer->getLastName())
            ->setPassword($userTransfer->getPassword())
            ->setRoleName($bfxRole)
            ->setCompanyId($this->getUserIdCompany())
            ->setLanguageId($this->getUserIdLanguage())
            ->setIsActive($isActive)
            ->addCustomFields((new BladeFxCreateOrUpdateUserCustomFieldsTransfer())
                ->setFieldName($this->config->getSprykerUserIdKey())
                ->setFieldValue((string)($userTransfer->getIdUser())));

        return $this->appendMerchantIdToRequest($bladeFxCreateOrUpdateUserRequestTransfer, $userTransfer->getIdUser(), $bfxRole);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Xiphias\BladeFxApi\DTO\BladeFxCreateOrUpdateUserResponseTransfer $responseTransfer
     *
     * @return \Xiphias\BladeFxApi\DTO\BladeFxUpdatePasswordRequestTransfer
     */
    public function generateAuthenticatedUpdatePasswordOnBladeFxRequest(
        UserTransfer $userTransfer,
        BladeFxCreateOrUpdateUserResponseTransfer $responseTransfer
    ): BladeFxUpdatePasswordRequestTransfer {
        return (new BladeFxUpdatePasswordRequestTransfer())
            ->setAccessToken($this->getToken())
            ->setBladeFxUserId($responseTransfer->getId())
            ->setPassword($userTransfer->getPassword());
    }

    /**
     * @param \Xiphias\BladeFxApi\DTO\BladeFxCreateOrUpdateUserRequestTransfer $bladeFxCreateOrUpdateUserRequestTransfer
     * @param int $userId
     * @param string $bfxRole
     *
     * @return \Xiphias\BladeFxApi\DTO\BladeFxCreateOrUpdateUserRequestTransfer
     */
    protected function appendMerchantIdToRequest(
        BladeFxCreateOrUpdateUserRequestTransfer $bladeFxCreateOrUpdateUserRequestTransfer,
        int $userId,
        string $bfxRole
    ): BladeFxCreateOrUpdateUserRequestTransfer {
        if ($bfxRole === ReportsConstants::SPRYKER_MP_ROLE) {
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
