import { NgModule } from '@angular/core';
import { WebComponentsModule } from '@spryker/web-components';
import { ButtonAjaxComponent, ButtonAjaxModule } from '@spryker/button';
import { ChipsComponent, ChipsModule } from '@spryker/chips';
import { CardModule, CardComponent } from '@spryker/card';
import { TabsModule, TabsComponent, TabComponent } from '@spryker/tabs';

import { BfxReportsTableComponent} from "../../../../BfxReportsMerchantPortalGui/Presentation/Components/app/bfx-reports-table/bfx-reports-table.component";
import { BfxReportsTableModule} from "../../../../BfxReportsMerchantPortalGui/Presentation/Components/app/bfx-reports-table/bfx-reports-table.module";

import { SalesOrdersComponent } from "mp-sales-orders/src/Spryker/Zed/SalesMerchantPortalGui/Presentation/Components/app/sales-orders/sales-orders.component";
import { SalesOrdersModule } from "mp-sales-orders/src/Spryker/Zed/SalesMerchantPortalGui/Presentation/Components/app/sales-orders/sales-orders.module";
import { ManageOrderComponent } from "mp-sales-orders/src/Spryker/Zed/SalesMerchantPortalGui/Presentation/Components/app/manage-order/manage-order.component";
import { ManageOrderModule} from "mp-sales-orders/src/Spryker/Zed/SalesMerchantPortalGui/Presentation/Components/app/manage-order/manage-order.module";
import { ManageOrderStatsBlockComponent} from "mp-sales-orders/src/Spryker/Zed/SalesMerchantPortalGui/Presentation/Components/app/manage-order/manage-order-stats-block/manage-order-stats-block.component";
import { ManageOrderTotalsComponent } from "mp-sales-orders/src/Spryker/Zed/SalesMerchantPortalGui/Presentation/Components/app/manage-order/manage-order-totals/manage-order-totals.component";
import { OrderItemsTableModule } from "mp-sales-orders/src/Spryker/Zed/SalesMerchantPortalGui/Presentation/Components/app/order-items-table/order-items-table.module";
import { OrderItemsTableComponent } from "mp-sales-orders/src/Spryker/Zed/SalesMerchantPortalGui/Presentation/Components/app/order-items-table/order-items-table.component";
import { ManageOrderCollapsibleTotalsComponent } from "mp-sales-orders/src/Spryker/Zed/SalesMerchantPortalGui/Presentation/Components/app/manage-order/manage-order-collapsible-totals/manage-order-collapsible-totals.component";

@NgModule({
    imports: [
        WebComponentsModule.withComponents([
            TabsComponent,
            SalesOrdersComponent,
            ManageOrderComponent,
            ManageOrderStatsBlockComponent,
            ButtonAjaxComponent,
            ChipsComponent,
            CardComponent,
            TabComponent,
            ManageOrderTotalsComponent,
            OrderItemsTableComponent,
            ManageOrderCollapsibleTotalsComponent,
            BfxReportsTableComponent
        ]),
        SalesOrdersModule,
        ButtonAjaxModule,
        ChipsModule,
        CardModule,
        TabsModule,
        ManageOrderModule,
        OrderItemsTableModule,
        BfxReportsTableModule,
    ],
})
export class ComponentsModule {}
