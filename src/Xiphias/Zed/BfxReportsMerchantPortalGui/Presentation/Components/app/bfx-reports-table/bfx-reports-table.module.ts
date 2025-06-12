import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { TableModule } from "@spryker/table";
import { TablePaginationFeatureModule } from '@spryker/table.feature.pagination';


import { BfxReportsTableComponent } from './bfx-reports-table.component';

@NgModule({
    imports: [CommonModule, TablePaginationFeatureModule, TableModule],
    declarations: [BfxReportsTableComponent],
    exports: [BfxReportsTableComponent],
})

export class BfxReportsTableModule {}
