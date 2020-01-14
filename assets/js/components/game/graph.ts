import GraphConfig from './graphConfig'
import {dia, shapes} from 'jointjs';
import logger from '../core/logger/error';
import Runner from '../core/helper/singleRun'
import Modal from '../core/modal/index';
import LinkView = dia.LinkView;
import CellView = dia.CellView;
import Rectangle = shapes.standard.Rectangle;

class GameGraph {

    graphUrl: string;
    graph: any;
    tree: any;
    linkFromCidToQuestionId: any;
    paper: any;

    constructor(targetElement: any) {
        this.graphUrl = targetElement.dataset.url;
        this.graph = new dia.Graph;
        this.tree = {};
        this.linkFromCidToQuestionId = {};
        this.paper = new dia.Paper({
            el: targetElement,
            model: this.graph,
            width: 600,
            height: 800,
            gridSize: 10,
            drawGrid: true,
            background: {
                color: 'rgba(0, 255, 0, 0.3)'
            }
        });
        this.graph.on('change:position', (cell: Rectangle) => this.onMoveEvent(cell));
        this.paper.on('element:pointerdblclick', (cellView: CellView) => this.showQuestionModal(cellView));
        this.paper.on('link:pointerdblclick', (linkView: LinkView) => this.showAnswerModal(linkView));
    }

    createRect() {
        let rect = new shapes.standard.Rectangle();
        rect.resize(100, 40);
        rect.attr({
            body: GraphConfig.bodyStyle,
            label: {
                ...GraphConfig.labelStyle
            }
        });
        return rect;
    }

    showButtons(data: any) {
        for (let i = 0; i < data.length; i++) {
            let el = data[i];
            let rect = this.createRect();
            rect.attr({
                label: {
                    text: el.text
                }
            });
            rect.position(el.position.x, el.position.y);
            rect.addTo(this.graph);
            this.tree[el.id] = {
                rect: rect,
                el: el,
                cid: rect.id,
                text: el.text
            };
            this.linkFromCidToQuestionId[rect.id] = el.id;
        }
        return this.tree;
    }

    createLink(source: any, target: any) {
        let link = new shapes.standard.Link();
        link.source(source);
        link.target(target);
        return link;
    }

    showLinks(tree: any) {
        for (let key in tree) {
            let source = tree[key].rect;

            for (let answerKey in tree[key].el.answers) {
                let answer = tree[key].el.answers[answerKey];
                let target = tree[answer.id].rect;
                let link = this.createLink(source, target);
                link.appendLabel({
                    attrs: {
                        text: {
                            text: answer.text
                        }
                    }
                });
                link.addTo(this.graph);
            }
        }
    }

    onMoveEvent(cell: Rectangle) {
        const questionId = this.linkFromCidToQuestionId[cell.id];
        this.save(questionId, {
            locationX: cell.position().x,
            locationY: cell.position().y,
        });
    }

    save(questionId: any, data: any) {
        Runner.run(questionId, () => {
            fetch('/admin/game/question/' + questionId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify(data)
            });
        }, 1000);
    }

    showQuestionModal(cellView: CellView) {
        const cell = cellView.model;
        const modalEl = document.getElementsByClassName('question-modal')[0] as HTMLElement;
        const questionId = this.linkFromCidToQuestionId[cell.id];
        (<HTMLInputElement>document.getElementById('modal-question-text')).value = this.tree[questionId].text;
        Modal.show(modalEl);
        document.getElementById('modal-question-id').innerText = questionId;
        modalEl.getElementsByClassName('action-save')[0].addEventListener('click', this.saveQuestion.bind(this));
    }

    saveQuestion(event: any) {
        event.target.removeEventListener('click', this.saveQuestion);
        const questionId = document.getElementById('modal-question-id').innerText;
        const text = (<HTMLInputElement>document.getElementById('modal-question-text')).value;
        fetch('/admin/game/question/' + questionId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify({
                text: text
            })
        }).then(() => {
            this.tree[questionId].rect.attr({
                label: {
                    text: text
                }
            });
            const modalEl = document.getElementsByClassName('question-modal')[0] as HTMLElement;
            Modal.close(modalEl)
        });
    }

    showAnswerModal(linkView: LinkView) {
        const cell = linkView.model;
        const modalEl = document.getElementsByClassName('answer-modal')[0] as HTMLElement;
        const questionId = this.linkFromCidToQuestionId[cell.id];

        Modal.show(modalEl);


        // const modalEl = document.getElementsByClassName('question-modal')[0];

        // document.getElementById('modal-question-text').value = this.tree[questionId].text;
        // Modal.show(modalEl);
        // document.getElementById('modal-question-id').innerText = questionId;
        // modalEl.getElementsByClassName('action-save')[0].addEventListener('click', this.saveQuestion.bind(this));
    }

    showGraph() {
        fetch(this.graphUrl)
            .then(res => res.json())
            .then((data) => this.showButtons(data))
            .then((data) => this.showLinks(data))
            .catch(logger.error);
    }
}

export default GameGraph;