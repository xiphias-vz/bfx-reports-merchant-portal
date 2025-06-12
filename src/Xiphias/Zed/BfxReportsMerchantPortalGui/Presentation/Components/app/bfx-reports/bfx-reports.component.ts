import {ChangeDetectionStrategy, Component, Input, ViewChild, viewChild, ViewEncapsulation} from "@angular/core";
import { TableConfig } from '@spryker/table';
import { CoreTableComponent } from '@spryker/table';


@Component({
    selector: 'mp-bfx-reports',
    templateUrl: './bfx-reports.component.html',
    styleUrls: ['./bfx-reports.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-bfx-reports',
    }
})
export class BfxReportsComponent {
    @Input() tableConfig: TableConfig;
    @Input() tableId?: string;
    @Input() categoryTree: any[];
    @ViewChild(CoreTableComponent) reportsTable: CoreTableComponent;

    onCategoryPick(config: TableConfig) {
        this.tableConfig = config;
    }
}
