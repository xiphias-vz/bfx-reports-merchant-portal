import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { HeadlineModule } from "@spryker/headline";

import { BfxReportsComponent } from "./bfx-reports.component";
import { BfxReportsTableModule } from "../bfx-reports-table/bfx-reports-table.module";

@NgModule({
    imports: [CommonModule, HeadlineModule, BfxReportsTableModule],
    declarations: [BfxReportsComponent],
    exports: [BfxReportsComponent],
})
export class BfxReportsModule {}
