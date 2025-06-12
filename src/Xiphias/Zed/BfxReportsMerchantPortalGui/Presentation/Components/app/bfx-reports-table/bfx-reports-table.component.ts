import {
    ChangeDetectionStrategy,
    Component,
    EventEmitter,
    Input,
    Output,
    ViewChild,
    ViewEncapsulation
} from "@angular/core";
import { TableConfig } from '@spryker/table';
import { CoreTableComponent } from '@spryker/table';
import {TablePaginationFeatureComponent} from '@spryker/table.feature.pagination';
import {BfxReportsMessengerService} from "../bfx-reports-messenger-service/bfx-reports-messenger-service";
import {combineLatest} from "rxjs";

export interface CategoryEvent {
    categoryId: number;
}

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
    @Output() categoryPicked = new EventEmitter();
    @ViewChild(CoreTableComponent) reportsTable: CoreTableComponent;
    @ViewChild(TablePaginationFeatureComponent) paginationFeature: TablePaginationFeatureComponent;


    protected FIRST_PAGE: number = 1;
    protected URL: string = '/bfx-reports-merchant-portal-gui/bfx-reports/main-reports-table-data';
    protected QUERY_CATEGORY: string = 'category=';

    constructor(
        private messengerService: BfxReportsMessengerService,
    ) {
    }

    ngAfterViewInit() {
        this.subscribeToCategoryChosenObservable();
        this.handleEmptyDataOnCategoryChangeSubscription();
    }

    emitUpdatedTableConfig(id: number) {
        this.reportsTable.config.dataSource.url = this.URL + '?' + this.QUERY_CATEGORY + id;
        this.categoryPicked.emit(this.reportsTable.config);
    }

    subscribeToCategoryChosenObservable() {
        this.messengerService.categorySelectionObservable.subscribe((event: CategoryEvent | null) => {
            if (event) this.emitUpdatedTableConfig(event.categoryId);
        });
    }

    handleEmptyDataOnCategoryChangeSubscription() {
        combineLatest([this.reportsTable.tableData$, this.paginationFeature.page$])
            .subscribe(([data, page]) => {
                if (data.length === 0 && page > 1) this.paginationFeature.updatePagination(this.FIRST_PAGE);
            });
    }
}
