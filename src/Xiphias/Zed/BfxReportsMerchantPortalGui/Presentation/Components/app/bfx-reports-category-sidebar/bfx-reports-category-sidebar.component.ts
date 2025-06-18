import {ChangeDetectionStrategy, Component, Input, ViewEncapsulation} from "@angular/core";
import {BfxReportsMessengerService} from "../bfx-reports-messenger-service/bfx-reports-messenger-service";

@Component({
    selector: 'mp-bfx-reports-category-sidebar',
    templateUrl: './bfx-reports-category-sidebar.component.html',
    styleUrls: ['./bfx-reports-category-sidebar.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-bfx-reports',
    }
})
export class BfxReportsCategorySidebarComponent {
    @Input() categoryTree: string;
    @Input() tableId: string;

    protected categoryTreeArray: any[];
    protected categories: any[] = [];
    protected activeCategory: number;
    protected url: string = '/bfx-reports-merchant-portal-gui/bfx-reports/main-reports-table-data';

    CATEGORY_LIST_ITEM_CLASS: string = 'category-sidebar__list-item';
    CATEGORY_LIST_ITEM_ACTIVE_CLASS: string = 'category-sidebar__list-item--active';

    constructor(
        private messengerService: BfxReportsMessengerService,
    ) {
    }

    ngOnInit() {
       this.categoryTreeArray = JSON.parse(this.categoryTree);
    }

    ngAfterViewInit() {
        const categories = document.querySelectorAll(`.${this.getCategoryListItemClass}`)

        categories.forEach(categoryElement => {
            let categoryId = parseInt(this.getCategoryId(categoryElement));
            let categoryActive = this.getCategoryActiveState(categoryElement);

            if(categoryActive === "1") {
                this.activeCategory = categoryId;
            }

            this.categories[categoryId] = categoryElement;
        })
    }

    handleNodeClick(categoryId: number) {
        this.changeActiveCategory(categoryId)
    }

    changeActiveCategory(id: number) {
        if(this.isActiveCategory(id)) return;
        this.toggleActiveCategoryUI(this.getActiveCategory(), id);
        this.setActiveCategory(id);
        this.switchCategory(id);
    }

    switchCategory(id: number) {
        this.messengerService.categorySelectionSubject.next({categoryId: id});
    }

    isActiveCategory(id) {
        return id === this.getActiveCategory();
    }

    toggleActiveCategoryUI(oldCategoryId, newCategoryId) {
        this.categories[oldCategoryId].classList.toggle(this.getCategoryListItemActiveClass)
        this.categories[newCategoryId].classList.toggle(this.getCategoryListItemActiveClass)
    }

    setActiveCategory(id) {
        this.activeCategory = id;
    }

    getCategoryId(categoryElement) {
        return categoryElement.dataset.id;
    }

    getCategoryActiveState(categoryElement) {
        return categoryElement.dataset.active;
    }

    getActiveCategory() {
        return this.activeCategory;
    }

    get getCategoryListItemClass(){
        return this.CATEGORY_LIST_ITEM_CLASS;
    }
    get getCategoryListItemActiveClass(){
        return this.CATEGORY_LIST_ITEM_ACTIVE_CLASS;
    }
}
