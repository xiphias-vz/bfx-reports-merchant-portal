import { NgModule } from '@angular/core';
import { ButtonLinkComponent, ButtonLinkModule } from '@spryker/button';
import { ChipsComponent, ChipsModule } from '@spryker/chips';
import { WebComponentsModule } from '@spryker/web-components';
import { CardModule, CardComponent } from '@spryker/card';
import { DateRangePickerModule, DateRangePickerComponent } from '@spryker/date-picker';

import { BfxReportsComponent } from './bfx-reports/bfx-reports.component';
import { BfxReportsIframeComponent} from './bfx-reports-iframe/bfx-reports-iframe.component'
import { BfxReportsModule } from './bfx-reports/bfx-reports.module';
import { BfxReportsIframeModule } from './bfx-reports-iframe/bfx-reports-iframe.module'

@NgModule({
    imports: [
        WebComponentsModule.withComponents([
            ButtonLinkComponent,
            ChipsComponent,
            CardComponent,
            DateRangePickerComponent,
            BfxReportsComponent,
            BfxReportsIframeComponent
        ]),
        ButtonLinkModule,
        ChipsModule,
        CardModule,
        DateRangePickerModule,
        BfxReportsModule,
        BfxReportsIframeModule
    ],
    providers: [],
})
export class ComponentsModule {}
