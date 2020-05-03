import * as _ from 'lodash';
import {Position} from './position';

export interface Answer {
    next_question_id: number;
    id: number;
    text: string;
    viewId: string;
}

interface Element {
    id: number | null;
    position: Position;
    text: string;
    isStart: boolean;
    answers: Answer[];
}

export class Templates {
    static node: string = document.getElementById('node-template').innerHTML;
    static line: string = document.getElementById('line-template').innerHTML;
}

export class Node {

    el: Element;
    nodePrefix: string;
    viewId: string;
    answerViewIds: Map<string, number>;

    public constructor(el: Element) {
        this.el = el;
        this.nodePrefix = 'node-';
        this.viewId = _.uniqueId('node');
        this.answerViewIds = new Map();
        this.el.answers.forEach((item: Answer) => {
            const viewId = this.generateAnswerId();
            this.answerViewIds.set(viewId, item.id);
            item.viewId = viewId;
        });
    }

    public update(el: Element) {
        this.el = el;
        this.answerViewIds = new Map();
        this.el.answers.forEach((item: Answer) => {
            const viewId = this.generateAnswerId();
            this.answerViewIds.set(viewId, item.id);
            item.viewId = viewId;
        });
    }

    public generateAnswerId(): string {
        return _.uniqueId('answer');
    }

    public render(): string {
        return _.template(Templates.node)({
            id: this.getId(),
            nodeId: this.el.id,
            position: this.el.position,
            text: this.el.text,
            answers: this.el.answers,
            viewId: this.viewId,
            answersViewIds: this.answerViewIds,
            isStart: this.el.isStart
        });
    }

    getId(): string {
        return this.nodePrefix + this.el.id;
    }

    getEl(): HTMLElement {
        const id = this.getId();
        return document.getElementById(id);
    }

    private getCurrentPosition(): Position {
        const  matches = this.getEl().style.transform.match(/.*?([\-0-9\.]+).*?([\-0-9\.]+).*/);
        return {
            x: parseInt(matches[1]),
            y: parseInt(matches[2]),
        }
    }

    updatePosition(): void {
        this.el.position = this.getCurrentPosition();
    }

    updateAnswer(viewId: string, text: string): void {
        const answer = this.getAnswerById(viewId);
        answer.text = text;
    }

    updateText(text: string): void {
        this.el.text = text;
    }

    removeAnswer(viewId: string): void {
        this.el.answers = this.el.answers.filter(function (value: Answer) {
            return value.viewId !== viewId;
        });
        this.answerViewIds.delete(viewId);
    }

    getAnswerById(viewId: string): Answer {
        for (let i = 0; i < this.el.answers.length; i++) {
            if (this.el.answers[i].viewId === viewId) {
                return this.el.answers[i];
            }
        }
        return null;
    }

    toJson(): string {
        return JSON.stringify(this.el)
    }

    addNewAnswer() {
        const viewId = this.generateAnswerId();
        const answer = <Answer> {
            id: null,
            text: '',
            next_question_id: null,
            viewId: viewId
        };
        this.el.answers.push(answer);
        this.answerViewIds.set(viewId, null);
    }

    getAnswers(): Answer[] {
        return this.el.answers;
    }

    removeAnswerLink(answerViewId: string) {
        const answer = this.getAnswerById(answerViewId);
        answer.next_question_id = null;
    }

    getNodeLineId(): string {
        return 'node-line-' + this.viewId;
    }
}
