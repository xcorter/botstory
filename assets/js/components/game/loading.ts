import {EventDispatcher} from "../core/event";
import {LOADING_RUN, LOADING_STOP} from "../core/event/const";

const THROBBER_RUNNING = 'throbber-running';

export class Loading {
    private eventDispatcher: EventDispatcher;
    private throbber: HTMLElement;

    constructor(eventDispatcher: EventDispatcher) {
        this.eventDispatcher = eventDispatcher;
        this.throbber = document.querySelector('.throbber');

        this.eventDispatcher.addListener(LOADING_RUN, () => this.showLoader());
        this.eventDispatcher.addListener(LOADING_STOP, () => this.stopLoader());
    }

    private showLoader() {
        this.throbber.classList.add(THROBBER_RUNNING);
    }

    private stopLoader() {
        this.throbber.classList.remove(THROBBER_RUNNING);
    }
}