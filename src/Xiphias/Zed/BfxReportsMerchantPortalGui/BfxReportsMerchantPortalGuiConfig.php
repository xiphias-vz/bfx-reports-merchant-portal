<?php


declare(strict_types=1);

namespace Xiphias\Zed\BfxReportsMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class BfxReportsMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const SPRYKER_ORDER_DETAIL_MP_ATTRIBUTE = 'spryker_order_detail_MP';

    /**
     * @return string
     */
    public function getBladeFxGroupName(): string
    {
        return BfxReportsMerchantPortalGuiConstants::BLADE_FX_GROUP_NAME;
    }

    /**
     * @return string
     */
    public function getSprykerOrderDetailAttribute(): string
    {
        return static::SPRYKER_ORDER_DETAIL_MP_ATTRIBUTE;
    }

    /**
     * @return string
     */
    public function getSprykerUserIdKey(): string
    {
        return BfxReportsMerchantPortalGuiConstants::SPRYKER_USER_ID_KEY;
    }

    /**
     * @return string
     */
    public function getMerchantIdKey(): string
    {
        return BfxReportsMerchantPortalGuiConstants::MERCHANT_ID_KEY;
    }

    /**
     * @return string
     */
    public function getBfxTokenSessionKey(): string
    {
        return BfxReportsMerchantPortalGuiConstants::BFX_TOKEN_SESSION_KEY;
    }

    /**
     * @return string
     */
    public function getBfxUserCompanyIdSessionKey(): string
    {
        return BfxReportsMerchantPortalGuiConstants::BFX_USER_COMPANY_ID_SESSION_KEY;
    }

    /**
     * @return string
     */
    public function getBfxUserLanguageIdSessionKey(): string
    {
        return BfxReportsMerchantPortalGuiConstants::BFX_USER_LANGUAGE_ID_SESSION_KEY;
    }
}
