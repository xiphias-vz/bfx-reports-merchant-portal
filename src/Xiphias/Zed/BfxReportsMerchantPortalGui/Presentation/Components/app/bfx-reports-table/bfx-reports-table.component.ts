import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from "@angular/core";
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-bfx-reports-table',
    templateUrl: './bfx-reports-table.component.html',
    styleUrls: ['./bfx-reports-table.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class BfxReportsTableComponent {
    @Input() config: TableConfig;
    @Input() tableId?: string;
}
