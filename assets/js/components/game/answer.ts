import {Answer} from "./node";

export class AnswerHelper {
    static getAnswerLineId(answer: Answer): string {
        return 'line-' + answer.viewId;
    }

    static getAnswerLineIdByHTML(answer: HTMLElement): string {
        return 'line-' + answer.dataset.viewId;
    }
}