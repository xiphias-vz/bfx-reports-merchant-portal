import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { TableModule } from "@spryker/table";

import { BfxReportsTableComponent } from './bfx-reports-table.component';

@NgModule({
    imports: [CommonModule, TableModule],
    declarations: [BfxReportsTableComponent],
    exports: [BfxReportsTableComponent],
})
export class BfxReportsTableModule {}
