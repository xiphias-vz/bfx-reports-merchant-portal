import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from "@angular/core";

@Component({
    selector: 'mp-bfx-reports-iframe',
    templateUrl: './bfx-reports-iframe.component.html',
    styleUrls: ['./bfx-reports-iframe.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-bfx-reports-iframe',
    }
})
export class BfxReportsIframeComponent {
    @Input() url: string;
}
