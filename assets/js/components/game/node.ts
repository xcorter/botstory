import * as _ from 'lodash';

interface Position {
    x: number;
    y: number;
}

interface Answer {
    next_question_id: number;
    id: number;
    text: string;
    viewId: string;
}

interface Element {
    id: number;
    position: Position;
    text: string;
    answers: Answer[];
}

class Templates {
    static node: string = document.getElementById('node-template').innerHTML;
}

export class Node {

    el: Element;
    nodePrefix: string;
    viewId: string;
    answerViewIds: Map<string, number>;

    public constructor(el: Element ) {
        this.el = el;
        this.nodePrefix = 'node-';
        this.viewId = _.uniqueId('node');
        this.answerViewIds = new Map();
        this.el.answers.forEach((item: Answer) => {
            const viewId = this.generateAnswerId();
            this.answerViewIds.set(viewId, item.id);
            item.viewId = viewId;
        })

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
            answersViewIds: this.answerViewIds
        });
    }

    getId(): string {
        return this.nodePrefix + this.el.id;
    }

    getEl(): HTMLElement {
        const id = this.getId();
        return document.getElementById(id);
    }

    getCurrentPosition(): Position {
        const  coords = this.getEl().getBoundingClientRect();
        return {
            x:  coords.x,
            y:  coords.y,
        }
    }

    updatePosition(): void {
        this.el.position = this.getCurrentPosition();
    }

    updateAnswer(viewId: string, text: string): void {
        const answer = this.getAnswerById(viewId);
        answer.text = text;
    }

    getAnswerById(viewId: string): Answer {
        for (let i = 0; i < this.el.answers.length; i++) {
            if (this.el.answers[i].viewId === viewId) {
                return this.el.answers[i];
            }
        }
        return null;
    }

    getCoords(): Position {   // кроме IE8-
        const box = this.getEl().getBoundingClientRect();
        return {
            y: box.top + pageYOffset,
            x: box.left + pageXOffset
        };
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
}
