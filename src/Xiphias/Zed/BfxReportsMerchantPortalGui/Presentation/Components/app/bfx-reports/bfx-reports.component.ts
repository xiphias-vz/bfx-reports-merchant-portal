import {ChangeDetectionStrategy, Component, Input, ViewEncapsulation} from "@angular/core";
import { TableConfig } from '@spryker/table';

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
}
