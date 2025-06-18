import {ChangeDetectionStrategy, ChangeDetectorRef, Component, Input, ViewEncapsulation} from "@angular/core";
import {DomSanitizer, SafeResourceUrl} from "@angular/platform-browser";
import {map} from "rxjs";
import { HttpClient } from '@angular/common/http';

interface ControllerResponse {
    html: string,
    url: string,
}

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
    private _url: string;
    safeUrl: SafeResourceUrl;

    constructor(
        private http: HttpClient,
        private sanitizer: DomSanitizer,
        private cdRef: ChangeDetectorRef
    ) {
    }

    @Input()
    set url(value: string) {
        this._url = value;
        let reportIframeObservable = this.http
            .get(value)
            .pipe(map((data: ControllerResponse) => data));

        reportIframeObservable.subscribe(response => {
            this.safeUrl = this.sanitizer.bypassSecurityTrustResourceUrl(response.url);
            this.cdRef.detectChanges();
        })
    }
}
