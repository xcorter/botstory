import * as _ from 'lodash';

interface Position {
    x: number;
    y: number;
}

interface Answer {
    next_question_id: number;
    id: number;
    text: string;
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

    public constructor(el: Element ) {
        this.el = el;
        this.nodePrefix = 'node-';
    }

    public render(): string {
        return _.template(Templates.node)({
            id: this.getId(),
            position: this.el.position,
            text: this.el.text,
            answers: this.el.answers
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

    updateAnswer(id: number): void {
        const options = <HTMLElement>this.getEl().getElementsByClassName('options')[0];
        const answer = this.getAnswerById(id);
        for (let i = 0; i < options.childNodes.length; i++) {
            const option = <HTMLElement>options.childNodes.item(i);
            const optionAnswerId = parseInt(option.dataset.id);
            if (answer.id == optionAnswerId) {
                answer.text = option.innerText;
            }
        }
    }

    getAnswerById(id: number): Answer {
        for (let i = 0; i < this.el.answers.length; i++) {
            if (this.el.answers[0].id === id) {
                return this.el.answers[0];
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
        this.el.answers.push({
            id: null,
            text: '',
            next_question_id: null
        });
    }
}
