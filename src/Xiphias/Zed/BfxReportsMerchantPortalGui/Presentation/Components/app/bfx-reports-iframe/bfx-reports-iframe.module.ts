import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { BfxReportsIframeComponent } from './bfx-reports-iframe.component';


@NgModule({
    imports: [CommonModule],
    declarations: [BfxReportsIframeComponent],
    exports: [BfxReportsIframeComponent],
})
export class BfxReportsIframeModule {}
