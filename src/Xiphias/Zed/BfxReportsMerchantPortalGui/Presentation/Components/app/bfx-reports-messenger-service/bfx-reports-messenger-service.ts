import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';

@Injectable({
    providedIn: 'root',
})
export class BfxReportsMessengerService {
    categorySelectionSubject = new BehaviorSubject<object>(null);
    categorySelectionObservable = this.categorySelectionSubject.asObservable();
}
