import {
    ChangeDetectionStrategy,
    Component,
    EventEmitter, Injector,
    Input,
    Output,
    ViewChild,
    ViewEncapsulation
} from "@angular/core";
import {
    TableConfig,
    CoreTableComponent,
    TableDataRow} from '@spryker/table';
import {TablePaginationFeatureComponent} from '@spryker/table.feature.pagination';
import {BfxReportsMessengerService} from "../bfx-reports-messenger-service/bfx-reports-messenger-service";
import {combineLatest} from "rxjs";
import { DrawerActionHandlerService, DrawerActionConfigComponent } from '@spryker/actions.drawer'
import {BfxReportsIframeComponent} from "../bfx-reports-iframe/bfx-reports-iframe.component";

interface CategoryEvent {
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
    @Input() isTab?: boolean;
    @Output() categoryPicked = new EventEmitter();
    @ViewChild(CoreTableComponent) reportsTable: CoreTableComponent;
    @ViewChild(TablePaginationFeatureComponent) paginationFeature: TablePaginationFeatureComponent;

    protected FIRST_PAGE: number = 1;
    protected URL: string = '/bfx-reports-merchant-portal-gui/bfx-reports/main-reports-table-data';
    protected QUERY_CATEGORY: string = 'category=';

    constructor(
        private messengerService: BfxReportsMessengerService,
        private injector: Injector,
        private drawerActionHandlerService: DrawerActionHandlerService
    ) {
    }

    ngAfterViewInit() {
        if (!this.isTab) {
            this.subscribeToCategoryChosenObservable();
            this.handleEmptyDataOnCategoryChangeSubscription();
            this.handleRowClick();
        }
    }

    subscribeToCategoryChosenObservable() {
        this.messengerService.categorySelectionObservable.subscribe((event: CategoryEvent | null) => {
            if (event) this.emitUpdatedTableConfig(event.categoryId);
        });
    }

    emitUpdatedTableConfig(id: number) {
        this.reportsTable.config.dataSource.url = this.URL + '?' + this.QUERY_CATEGORY + id;
        this.categoryPicked.emit(this.reportsTable.config);
    }

    handleEmptyDataOnCategoryChangeSubscription() {
        combineLatest([this.reportsTable.tableData$, this.paginationFeature.page$])
            .subscribe(([data, page]) => {
                if (data.length === 0 && page > 1) this.paginationFeature.updatePagination(this.FIRST_PAGE);
            });
    }

    handleRowClick() {
        this.reportsTable.findFeatureByName('rowActions')
            .subscribe((feature) => {
            if (feature) {
                this.reportsTable.rowClickHandler = (row: TableDataRow, event) => {
                        const drawerConfig: DrawerActionConfigComponent = {
                            type: 'drawer',
                            id: 'report-iframe',
                            title: 'Report Iframe',
                            component: BfxReportsIframeComponent,
                            options: {
                                inputs: {
                                    url: '\\bfx-reports-merchant-portal-gui\\bfx-reports\\report-iframe' + '?repId=' + row.repId
                                },
                            },
                        };

                        const context = { items: [row] }
                        const drawerReferenceObservable = this.drawerActionHandlerService.handleAction(this.injector, drawerConfig, context)
                        drawerReferenceObservable.subscribe((drawerReference) => {
                            drawerReference.refreshDrawer();
                        });
                }
            }
        });
    }
}
