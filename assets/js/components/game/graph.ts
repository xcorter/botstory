import logger from '../core/logger/error';
import Runner from '../core/helper/singleRun'
import NodeRepository from '../repository/nodeRepository'
import {Tree} from "./tree";
import {Node, Answer, Templates} from "./node";
import * as _ from 'lodash';
import {AnswerHelper} from "./answer";
import {LinkHelper} from "./link";
import {Menu} from "./menu";
import {EventDispatcher} from "../core/event";
import {NEW_NODE} from "../core/event/const";

class GameGraph {

    graphUrl: string;
    graph: any;
    tree: Tree;
    graphNode: HTMLElement;
    menu: Menu;
    nodeRepository: NodeRepository;
    gameId: number;
    eventDispatcher: EventDispatcher;

    constructor(targetElement: HTMLElement) {
        this.graphUrl = targetElement.dataset.url;
        this.gameId = parseInt(targetElement.dataset.gameId);
        this.tree = new Tree();
        this.graphNode = targetElement;
        this.configureGraphArea();

        this.nodeRepository = new NodeRepository(this.gameId);
        this.eventDispatcher = new EventDispatcher();

        this.menu = new Menu(this.tree, this.nodeRepository, this.eventDispatcher);
        this.menu.init();
        // window.tree = this.tree;
    }

    configureGraphArea() {
        this.graphNode.style.width = '100%';
        this.graphNode.style.height = '1024px';
    }

    showNodes(data: any): Tree {
        for (let i = 0; i < data.length; i++) {
            let el = data[i];
            const node = new Node(el);
            this.renderNode(node);
            this.tree.addNode(node.el.id, node);
        }
        return this.tree;
    }

    renderNode(node: Node) {
        const element = <HTMLElement>node.getEl();
        if (element) {
            element.remove();
        }
        let view = node.render();
        this.graphNode.insertAdjacentHTML('beforeend', view);
        this.tree.addNode(node.el.id, node);
        this.addMove(node);
        this.setListeners(node);
    }

    setListeners(node: Node) {
        const options = <HTMLElement>node.getEl().getElementsByClassName('options')[0];
        options.childNodes.forEach((option: HTMLElement) =>{
            option.addEventListener('input', (e) => {
                const target = <HTMLElement> e.target;
                const answerViewId = target.parentElement.dataset.viewId;
                node.updateAnswer(answerViewId, target.innerText);
                Runner.run(node.el.id, () => {
                    this.nodeRepository.save(node);
                }, 1000);
            })
        });

        const addButton = <HTMLElement>node.getEl().getElementsByClassName('option-title')[0];
        addButton.addEventListener('click', (e) => {
            node.addNewAnswer();
            this.renderNode(node);
        });

        const removeAnswerButtons =
            <HTMLCollectionOf<HTMLElement>>node.getEl().getElementsByClassName('answer-remove');
        for (let removeAnswerButton of Array.from(removeAnswerButtons)) {
            removeAnswerButton.addEventListener('click', (e) => {
                const target = <HTMLElement> e.target;
                const answerViewId = target.parentElement.dataset.viewId;
                node.removeAnswer(answerViewId);
                this.renderNode(node);
                Runner.run(node.el.id, () => {
                    this.nodeRepository.save(node);
                }, 1000);
            });
        }

        const pins = <HTMLCollectionOf<HTMLElement>>node.getEl().getElementsByClassName('pin');
        for (let pin of Array.from(pins)) {
            this.addPinMove(pin, node);
        }
    }

    addPinMove(pin: HTMLElement, node: Node) {
        pin.addEventListener('mousedown', (e) => {
            e.preventDefault();
            e.stopPropagation();

            const coords = pin.getBoundingClientRect();
            const position = {
                y: coords.top + pageYOffset,
                x: coords.left + pageXOffset
            };
            const startShiftX = e.pageX - position.x;
            const startShiftY = e.pageY - position.y;
            const answerEl = this.getAnswerByPin(pin);
            const link = this.addLink(position, answerEl);

            function moveAt(e: any) {
                const lineEl = <SVGElement> link.querySelector('.line');
                const x1 = Number(lineEl.getAttribute('x1'));
                const y1 = Number(lineEl.getAttribute('y1'));
                const x2 = e.pageX;
                const y2 = e.pageY;
                LinkHelper.updateCoordinates(link, x1, x2, y1, y2);
            }

            // 3, перемещать по экрану
            document.onmousemove = (e) => {
                moveAt(e);
            };

            document.onmouseup = (e: MouseEvent) => {
                document.onmousemove = null;
                document.onmouseup = null;
                pin.onmouseup = null;
                const nodePin = <HTMLElement>e.target;
                if (!nodePin.classList.contains('pin-node')) {
                    link.remove();
                    return;
                }
                const nodeEl = <HTMLElement>nodePin.closest('.node');
                const targetNode = this.tree.getNodeByViewId(nodeEl.dataset.viewId);
                const nodeLineId = targetNode.getNodeLineId();
                link.classList.add(nodeLineId);
                const answer = node.getAnswerById(answerEl.dataset.viewId);
                answer.next_question_id = targetNode.el.id;
                this.nodeRepository.save(node);
            };
        });
    }

    getAnswerByPin(pin: HTMLElement): HTMLElement {
        return <HTMLElement>pin.closest('.answer');
    }

    addLink(position: any, answerEl: HTMLElement): SVGElement {
        const answerLineId = AnswerHelper.getAnswerLineIdByHTML(answerEl);
        const linkEl = <SVGElement> this.graphNode.querySelector('.' + answerLineId);
        if (linkEl) {
            return linkEl;
        }
        const link = _.template(Templates.line)({
            x1: position.x,
            y1: position.y,
            x2: position.x,
            y2: position.y,
            answerLineId: answerLineId,
            nodeLineId: null,
            answerViewId: answerEl.dataset.viewId
        });
        this.graphNode.querySelector('svg').insertAdjacentHTML('beforeend', link);
        this.setRemoveLineListener(answerLineId);
        return <SVGElement> this.graphNode.querySelector('.' + answerLineId);
    }

    addMove(node: Node) {
        const graphNode: HTMLElement = this.graphNode;
        const title = <HTMLElement>node.getEl().getElementsByClassName('title')[0];
        title.addEventListener('mousedown', (e) => {
            e.preventDefault();
            e.stopPropagation();
            // подготовить к перемещению
            const coords = node.getCoords();
            const shiftX = e.pageX - coords.x;
            const shiftY = e.pageY - coords.y;
            // 2. разместить на том же месте, но в абсолютных координатах
            moveAt(e);
            // переместим в body, чтобы мяч был точно не внутри position:relative
            graphNode.appendChild(node.getEl());

            node.getEl().style.zIndex = '1000'; // показывать мяч над другими элементами

            // передвинуть мяч под координаты курсора
            // и сдвинуть на половину ширины/высоты для центрирования
            function moveAt(e: any) {
                node.getEl().style.left = e.pageX - shiftX + 'px';
                node.getEl().style.top = e.pageY - shiftY + 'px';
            }

            // 3, перемещать по экрану
            document.onmousemove = (e) => {
                moveAt(e);
                node.getAnswers().forEach((answer) => this.drawLine(answer));
                this.updateLinkIn(node);
            };

            // 4. отследить окончание переноса
            title.onmouseup = () => {
                document.onmousemove = null;
                title.onmouseup = null;
                node.updatePosition();
                this.nodeRepository.save(node);
            };
        });

        title.addEventListener('dragstart', () => {
            return false;
        });
    }

    drawLines() {
        for (let nodesKey in this.tree.nodes) {
            const node = this.tree.nodes[nodesKey];
            node.getAnswers().forEach((answer) => this.drawLine(answer))
        }
    }

    updateLinkIn(node: Node) {
        const nodeLineId = node.getNodeLineId();
        const links = <NodeListOf<SVGElement>> this.graphNode.querySelectorAll('.' + nodeLineId);
        links.forEach(function(linkEl: SVGElement) {
            const pinNode = <HTMLElement> node.getEl().querySelector('.pin-node');
            const nodePinPosition = pinNode.getBoundingClientRect();
            const x1 = Number(linkEl.querySelector('line').getAttribute('x1'));
            const y1 = Number(linkEl.querySelector('line').getAttribute('y1'));
            LinkHelper.updateCoordinates(linkEl, x1, nodePinPosition.x, y1, nodePinPosition.y);
        })

    }

    drawLine(answer: Answer) {
        const answerEl = <HTMLElement> document.querySelector('[data-view-id=' + answer.viewId + ']');
        const answerPinEl = <HTMLElement> answerEl.querySelector('.pin');

        const answerPinPosition = answerPinEl.getBoundingClientRect();

        if (!answer.next_question_id) {
            return;
        }
        const nextNode = this.tree.getNode(answer.next_question_id);
        if (!nextNode) {
            logger.error("Node for pin not found");
            return;
        }
        const nextPinEl = nextNode.getEl().querySelector('.pin-node');
        const nodePinPosition = nextPinEl.getBoundingClientRect();

        const answerLineId = AnswerHelper.getAnswerLineId(answer);

        const linkEl = <SVGElement> this.graphNode.querySelector('.' + answerLineId);

        const x1 = answerPinPosition.x;
        const y1 = answerPinPosition.y;
        const x2 = nodePinPosition.x;
        const y2 = nodePinPosition.y;
        if (linkEl) {
            LinkHelper.updateCoordinates(linkEl, x1, x2, y1, y2);
            return;
        }

        const link = _.template(Templates.line)({
            x1,
            y1,
            x2,
            y2,
            answerLineId: answerLineId,
            nodeLineId: nextNode.getNodeLineId(),
            answerViewId: answer.viewId
        });
        this.graphNode.querySelector('svg').insertAdjacentHTML('beforeend', link);
        this.setRemoveLineListener(answerLineId);
    }

    setRemoveLineListener(answerLineId: string) {
        const linkEl = <HTMLElement>this.graphNode.querySelector('.' + answerLineId);
        linkEl.querySelector('.remove-link').addEventListener('click', (e) => {
            const answerViewId = linkEl.dataset.answerViewId;
            const nodeEl = <HTMLElement>document.querySelector('[data-view-id="' + answerViewId + '"]').closest('.node');
            const node = this.tree.getNodeByViewId(nodeEl.dataset.viewId);
            node.removeAnswerLink(answerViewId);
            linkEl.remove();
            this.nodeRepository.save(node);
        });
    }

    initEventDispatcher() {
        this.eventDispatcher.addListener(NEW_NODE, (obj) => {
            const node = this.tree.getNode(obj.id);
            this.renderNode(node);
        })
    }

    showGraph() {
        this.initEventDispatcher();
        fetch(this.graphUrl)
            .then(res => res.json())
            .then((data) => this.showNodes(data))
            .then((tree) => this.drawLines())
            .catch(logger.error);
    }
}

export default GameGraph;
