import {ChangeDetectionStrategy, Component, EventEmitter, Input, Output, ViewEncapsulation} from "@angular/core";

@Component({
    selector: 'mp-bfx-reports-category-multilevel-node',
    templateUrl: './bfx-reports-category-multilevel-node.component.html',
    styleUrls: ['./bfx-reports-category-multilevel-node.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-bfx-reports',
    }
})
export class BfxReportsCategoryMultilevelNodeComponents {
    @Input() nodes: any[];
    @Output() nodeClicked = new EventEmitter();

    onClick(categoryId: number) {
        this.nodeClicked.emit(categoryId);
    }
}
