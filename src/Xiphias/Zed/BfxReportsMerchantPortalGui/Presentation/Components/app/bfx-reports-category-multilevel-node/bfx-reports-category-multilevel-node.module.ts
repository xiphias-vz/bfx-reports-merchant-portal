import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { HeadlineModule } from "@spryker/headline";

import { BfxReportsCategoryMultilevelNodeComponents } from "./bfx-reports-category-multilevel-node.components";
import {BfxReportsTableModule} from "../bfx-reports-table/bfx-reports-table.module";

@NgModule({
    imports: [CommonModule, HeadlineModule, BfxReportsTableModule],
    declarations: [BfxReportsCategoryMultilevelNodeComponents],
    exports: [BfxReportsCategoryMultilevelNodeComponents],
})
export class BfxReportsCategoryMultilevelNodeModule {}
