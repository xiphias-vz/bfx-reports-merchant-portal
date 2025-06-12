import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { HeadlineModule } from "@spryker/headline";

import { BfxReportsCategorySidebarComponent } from "./bfx-reports-category-sidebar.component";
import {
    BfxReportsCategoryMultilevelNodeModule
} from "../bfx-reports-category-multilevel-node/bfx-reports-category-multilevel-node.module";

@NgModule({
    imports: [CommonModule, HeadlineModule, BfxReportsCategoryMultilevelNodeModule],
    declarations: [BfxReportsCategorySidebarComponent],
    exports: [BfxReportsCategorySidebarComponent],
})
export class BfxReportsCategorySidebarModule {}
