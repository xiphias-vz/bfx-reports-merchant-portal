<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Xiphias\Zed\SalesMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Spryker\Zed\SalesMerchantPortalGui\Communication\Controller\DetailController as SprykerDetailController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\SalesMerchantPortalGui\Communication\SalesMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface getRepository()
 */
class DetailController extends SprykerDetailController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $idMerchantOrder = $this->castId($request->get(static::PARAM_ID_MERCHANT_ORDER));
        $merchantOrderTransfer = $this->getFactory()
            ->getMerchantSalesOrderFacade()
            ->findMerchantOrder(
                (new MerchantOrderCriteriaTransfer())
                    ->setIdMerchantOrder($idMerchantOrder)
                    ->setWithOrder(true)
                    ->setWithItems(true)
                    ->setWithUniqueProductsCount(true),
            );

        if (!$merchantOrderTransfer || !$this->isMerchantOrderBelongsCurrentMerchant($merchantOrderTransfer)) {
            throw new NotFoundHttpException(sprintf('Merchant order is not found for id %d.', $idMerchantOrder));
        }

        $responseData = [
            'html' => $this->renderView('@SalesMerchantPortalGui/Partials/merchant_order_detail.twig', [
                'merchantOrder' => $merchantOrderTransfer,
                'customerMerchantOrderNumber' => $this->getCustomerMerchantOrderNumber($merchantOrderTransfer),
                'shipmentsNumber' => $this->getShipmentsNumber($merchantOrderTransfer),
                'merchantOrderItemTableConfiguration' => $this->getFactory()
                    ->createMerchantOrderItemGuiTableConfigurationProvider()
                    ->getConfiguration($merchantOrderTransfer),
                'merchantOrderItemsIndexedByShipment' => $this->getMerchantOrderItemTransfersIndexedByIdShipment($merchantOrderTransfer),
                'bfxReportsTableConfiguration' => $this->getFactory()->getBladeFxReportsTableConfiguration()->getConfiguration($merchantOrderTransfer),
            ])->getContent(),
        ];

        $var = new JsonResponse($responseData);

        return $var;
    }
}
