import GraphConfig from './graphConfig'
import {dia, shapes} from 'jointjs';
import logger from '../core/logger/error';
import Runner from '../core/helper/singleRun'
import Modal from '../core/modal/index';
import QuestionRepository from '../repository/questionRepository'
import AnswerRepository from '../repository/answerRepository'
import LinkView = dia.LinkView;
import CellView = dia.CellView;
import Rectangle = shapes.standard.Rectangle;
import {Tree} from "./tree";
import {Node} from "./node";

class GameGraph {

    graphUrl: string;
    graph: any;
    tree: Tree;
    paper: any;

    constructor(targetElement: any) {
        this.graphUrl = targetElement.dataset.url;
        this.graph = new dia.Graph;
        this.tree = new Tree();
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

    showButtons(data: any): Tree {
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
            const node = new Node(rect, el, rect.id, el.text);
            this.tree.addNode(el.id, node);
        }
        return this.tree;
    }

    createLink(source: any, target: any) {
        let link = new shapes.standard.Link();
        link.source(source);
        link.target(target);
        return link;
    }

    showLinks(tree: Tree) {
        for (let key in tree.nodes) {
            const node = tree.getNode(key);
            let source = node.rect;

            for (let answerKey in node.el.answers) {
                let answer = node.el.answers[answerKey];
                let target = tree.getNode(answer.id).rect;
                let link = this.createLink(source, target);
                link.appendLabel({
                    attrs: {
                        text: {
                            text: answer.text
                        }
                    }
                });
                answer.cid = link.id;
                link.addTo(this.graph);
            }
        }
    }

    onMoveEvent(cell: Rectangle) {
        const questionId = this.tree.getNodeByCellId(<string>cell.id).el.id;
        this.save(questionId, {
            locationX: cell.position().x,
            locationY: cell.position().y,
        });
    }

    save(questionId: any, data: any) {
        Runner.run(questionId, () => {
            QuestionRepository.save(questionId, data);
        }, 1000);
    }

    showQuestionModal(cellView: CellView) {
        const cell = cellView.model;
        const modalEl = document.getElementsByClassName('question-modal')[0] as HTMLElement;
        const questionId = this.tree.getNodeByCellId(<string>cell.id).el.id;
        (<HTMLInputElement>document.getElementById('modal-question-text')).value = this.tree.getNode(questionId).text;
        Modal.show(modalEl);
        document.getElementById('modal-question-id').innerText = questionId;
        modalEl.getElementsByClassName('action-save')[0].addEventListener('click', this.saveQuestion.bind(this));
    }

    saveQuestion(event: any) {
        event.target.removeEventListener('click', this.saveQuestion);
        const questionId = document.getElementById('modal-question-id').innerText;
        const text = (<HTMLInputElement>document.getElementById('modal-question-text')).value;
        const data = {
            text
        };
        QuestionRepository.save(questionId, data).then(() => {
            this.tree.getNode(questionId).rect.attr({
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
        const answer = this.tree.getAnswer(cell);
        (<HTMLInputElement>document.getElementById('modal-answer-text')).value = answer.text;
        document.getElementById('modal-answer-id').innerText = answer.id;
        Modal.show(modalEl);
        modalEl.getElementsByClassName('action-save')[0].addEventListener('click', this.saveAnswer.bind(this));
    }

    saveAnswer(event: any) {
        event.target.removeEventListener('click', this.saveAnswer);
        const answerId = document.getElementById('modal-answer-id').innerText;
        const text = (<HTMLInputElement>document.getElementById('modal-answer-text')).value;
        AnswerRepository.save(answerId, {text}).then(() => {
            // this.tree.getNode(questionId).rect.attr({
            //     label: {
            //         text: text
            //     }
            // });
            const modalEl = document.getElementsByClassName('answer-modal')[0] as HTMLElement;
            Modal.close(modalEl)
        });
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