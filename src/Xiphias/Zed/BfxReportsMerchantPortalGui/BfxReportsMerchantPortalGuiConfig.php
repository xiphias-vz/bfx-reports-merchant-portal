<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Pyz\Zed\BfxReportsMerchantPortalGui;

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
    public function getSprykerOrderDetailAttribute(): string
    {
        return static::SPRYKER_ORDER_DETAIL_MP_ATTRIBUTE;
    }
}
