import logger from '../core/logger/error';
import Runner from '../core/helper/singleRun'
import NodeRepository from '../repository/nodeRepository'
import {Tree} from "./tree";
import {Position} from './position';
import {Node, Answer, Templates, Element} from "./node";
import * as _ from 'lodash';
import {AnswerHelper} from "./answer";
import {LinkHelper} from "./link";
import {Menu} from "./menu";
import {EventDispatcher} from "../core/event";
import {
    CHANGE_NODE,
    CHANGE_SCALE_START, CHANGE_SCALE_STOP,
    LOADING_RUN,
    LOADING_STOP,
    MOVE_SCREEN_START,
    MOVE_SCREEN_STOP,
    NEW_NODE
} from "../core/event/const";
import {Scale} from "./scale";
import {Loading} from "./loading";
import {KEY_ENTER, KEY_SHIFT} from "../core/keyManager/keys";
import KeyManager from "../core/keyManager/keyManager";
import ActionManager from "../core/actionManager/actionManager";
import {EDITING} from "../core/actionManager/actions";

class GameGraph {

    graphUrl: string;
    graph: any;
    tree: Tree;
    app: HTMLElement;
    graphNode: HTMLElement;
    menu: Menu;
    nodeRepository: NodeRepository;
    gameId: number;
    eventDispatcher: EventDispatcher;
    scale: Scale;
    loading: Loading;
    keyManager: KeyManager;
    actionManager: ActionManager;

    constructor(app: HTMLElement) {
        this.app = app;
        this.graphUrl = app.dataset.url;
        this.gameId = parseInt(app.dataset.gameId);
        this.tree = new Tree();
        this.graphNode = app.querySelector('.graph');

        this.nodeRepository = new NodeRepository(this.gameId);
        this.eventDispatcher = new EventDispatcher();

        this.keyManager = new KeyManager();
        this.actionManager = new ActionManager();
        this.scale = new Scale(
            this.graphNode,
            this.eventDispatcher,
            this.keyManager,
            this.actionManager
        );

        this.menu = new Menu(this.tree, this.nodeRepository, this.eventDispatcher, this.scale);
        this.menu.init();

        this.loading = new Loading(this.eventDispatcher);
        // window.tree = this.tree;
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

    editAnswerHandler(node: Node, target: HTMLElement): void {
        const answerViewId = target.parentElement.dataset.viewId;
        node.updateAnswer(answerViewId, target.innerText);
        this.updateNodeWithoutRerender(node);
    }

    setListeners(node: Node) {
        const options = <HTMLElement>node.getEl().getElementsByClassName('options')[0];
        // edit answer events
        options.childNodes.forEach((option: HTMLElement) => {
            const answerText = <HTMLElement>option.querySelector('.answer-text');
            answerText.addEventListener('focus', (e) => {
                this.actionManager.add(EDITING);
                console.log('focus');
            });
            answerText.addEventListener('keydown', (e) => {
                if (e.key === KEY_ENTER && !this.keyManager.isKeyDown(KEY_SHIFT)) {
                    e.preventDefault();
                    window.getSelection().removeAllRanges();
                    const target = <HTMLElement> e.target;
                    target.blur();
                }
            });
            answerText.addEventListener('input', (e) => {
                this.updateLines(node);
            });
            answerText.addEventListener('blur', (e) => {
                this.actionManager.remove(EDITING);
                const target = <HTMLElement> e.target;
                this.editAnswerHandler(node, target);
            })
        });

        const dataText = (<HTMLElement>node.getEl().querySelector('[data-text]'));
        dataText.addEventListener('input', (e) => {
                // const target = <HTMLElement> e.target;
                // node.updateText(target.innerText);
                this.updateLines(node);
            });
        dataText.addEventListener('focus', (e) => {
            this.actionManager.add(EDITING);
        });
        dataText.addEventListener('blur', (e) => {
            this.actionManager.remove(EDITING);
            this.eventDispatcher.dispatch(CHANGE_NODE);
            const target = <HTMLElement> e.target;
            node.updateText(target.innerText);
            this.updateNode(node);
        });

        const addButton = <HTMLElement>node.getEl().getElementsByClassName('option-title')[0];
        addButton.addEventListener('click', (e) => {
            node.addNewAnswer();
            this.renderNode(node);
            this.drawLines();
        });

        const removeAnswerButtons =
            <HTMLCollectionOf<HTMLElement>>node.getEl().getElementsByClassName('answer-remove');
        for (let removeAnswerButton of Array.from(removeAnswerButtons)) {
            removeAnswerButton.addEventListener('click', (e) => {
                const target = <HTMLElement> e.target;
                const answerViewId = target.parentElement.dataset.viewId;
                node.removeAnswer(answerViewId);
                this.renderNode(node);
                this.updateNode(node);
                this.updateLines(node);
            });
        }

        const pins = <HTMLCollectionOf<HTMLElement>>node.getEl().getElementsByClassName('pin');
        for (let pin of Array.from(pins)) {
            this.addPinMove(pin, node);
        }

        const removeButton = <HTMLElement>node.getEl().querySelector('[data-node-delete]');
        removeButton.addEventListener('click', (e) => {
            this.removeNode(node);
            Runner.run(node.el.id, () => {
                this.eventDispatcher.dispatch(LOADING_RUN);
                this.nodeRepository.delete(node).finally(() => this.eventDispatcher.dispatch(LOADING_STOP));
            }, 1000);
        });

        this.eventDispatcher.addListener([MOVE_SCREEN_START, CHANGE_SCALE_START], () => {
            this.hideLines()
        });

        this.eventDispatcher.addListener([MOVE_SCREEN_STOP, CHANGE_SCALE_STOP], () => {
            this.showLines();
            this.drawLines();
        });
    }

    removeNode(node: Node) {
        this.tree.removeNode(node);
        const linesId = node.getNodeLineId();
        const elementNodeListOf = this.app.querySelectorAll('.' + linesId);
        elementNodeListOf.forEach((value) => {
            value.remove();
        });
        node.getAnswers().forEach((answer) => {
            const selector = '[data-answer-view-id=' + answer.viewId + ']';
            const link = this.app.querySelector(selector);
            if (link) {
                link.remove();
            }
        });
        node.getEl().remove();
    }

    addPinMove(pin: HTMLElement, node: Node) {
        pin.addEventListener('mousedown', (e) => {
            e.preventDefault();
            e.stopPropagation();

            const position = {
                y: e.pageY,
                x: e.pageX
            };
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
                answer.nextQuestionId = targetNode.el.id;
                this.eventDispatcher.dispatch(LOADING_RUN);
                this.nodeRepository.save(node).then(() => this.eventDispatcher.dispatch(LOADING_STOP));
            };
        });
    }

    getAnswerByPin(pin: HTMLElement): HTMLElement {
        return <HTMLElement>pin.closest('.answer');
    }

    addLink(position: any, answerEl: HTMLElement): SVGElement {
        const answerLineId = AnswerHelper.getAnswerLineIdByHTML(answerEl);
        const linkEl = <SVGElement> this.app.querySelector('.' + answerLineId);
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
        this.app.querySelector('svg').insertAdjacentHTML('beforeend', link);
        this.setRemoveLineListener(answerLineId);
        return <SVGElement> this.app.querySelector('.' + answerLineId);
    }

    addMove(node: Node) {
        const graphNode: HTMLElement = this.graphNode;
        const title = <HTMLElement>node.getEl().getElementsByClassName('title')[0];
        title.addEventListener('mousedown', (e) => {
            e.preventDefault();
            e.stopPropagation();
            // подготовить к перемещению
            const scale = this.scale.getScale();
            const coords = node.el.position;
            const shiftX = (e.pageX) / scale - coords.x;
            const shiftY = (e.pageY) / scale - coords.y;
            // 2. разместить на том же месте, но в абсолютных координатах
            moveAt(e);
            graphNode.appendChild(node.getEl());

            node.getEl().style.zIndex = '1000';

            function moveAt(e: any) {
                const left = e.pageX / scale - shiftX + 'px';
                const top = e.pageY / scale - shiftY + 'px';
                node.getEl().style.transform = 'translate(' + left + ', ' + top + ')';
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
                this.eventDispatcher.dispatch(LOADING_RUN);
                this.nodeRepository.save(node).then(() => this.eventDispatcher.dispatch(LOADING_STOP));
            };
        });

        title.addEventListener('dragstart', () => {
            return false;
        });
    }

    updateLines(node: Node) {
        node.getAnswers().forEach((answer) => this.drawLine(answer));
        this.updateLinkIn(node);
    }

    drawLines() {
        for (let nodesKey in this.tree.nodes) {
            const node = this.tree.nodes[nodesKey];
            node.getAnswers().forEach((answer) => this.drawLine(answer))
        }
    }

    getLinksIn(node: Node)  {
        const nodeLineId = node.getNodeLineId();
        return <NodeListOf<SVGElement>> this.app.querySelectorAll('.' + nodeLineId);
    }

    updateLinkIn(node: Node) {
        const links = this.getLinksIn(node);
        links.forEach((linkEl: SVGElement) => {
            const pinNode = <HTMLElement> node.getEl().querySelector('.pin-node');
            const nodePinPosition = this.getCenter(pinNode);
            const x1 = Number(linkEl.querySelector('line').getAttribute('x1'));
            const y1 = Number(linkEl.querySelector('line').getAttribute('y1'));
            const x2 = nodePinPosition.x;
            const y2 = nodePinPosition.y;
            LinkHelper.updateCoordinates(linkEl, x1, x2, y1, y2);
        })
    }

    drawLine(answer: Answer) {
        const answerEl = <HTMLElement> document.querySelector('[data-view-id=' + answer.viewId + ']');
        const answerPinEl = <HTMLElement> answerEl.querySelector('.pin');

        const answerPinPosition = this.getCenter(answerPinEl);

        if (!answer.nextQuestionId) {
            return;
        }
        const nextNode = this.tree.getNode(answer.nextQuestionId);
        if (!nextNode) {
            logger.error("Node for pin not found");
            return;
        }
        const nextPinEl = <HTMLElement>nextNode.getEl().querySelector('.pin-node');
        const nodePinPosition = this.getCenter(nextPinEl);

        const answerLineId = AnswerHelper.getAnswerLineId(answer);

        const linkEl = <SVGElement> this.app.querySelector('.' + answerLineId);

        const x1 = answerPinPosition.x;
        const y1 = answerPinPosition.y ;
        const x2 = nodePinPosition.x ;
        const y2 = nodePinPosition.y ;
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
        this.app.querySelector('svg').insertAdjacentHTML('beforeend', link);
        this.setRemoveLineListener(answerLineId);
    }

    setRemoveLineListener(answerLineId: string) {
        const linkEl = <HTMLElement>this.app.querySelector('.' + answerLineId);
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
        this.eventDispatcher.dispatch(LOADING_RUN);
        fetch(this.graphUrl)
            .then(res => res.json())
            .then((data) => this.showNodes(data))
            .then((tree) => this.drawLines())
            .catch(logger.error)
            .finally(() => this.eventDispatcher.dispatch(LOADING_STOP));
    }

    private getCenter(el: HTMLElement): Position {
        const rect = el.getBoundingClientRect();
        const x = rect.left + (rect.right - rect.left) / 2;
        const y = rect.top + (rect.bottom - rect.top) / 2;
        return {
            x,
            y
        }
    }

    private updateNodeWithoutRerender(node: Node): void {
        Runner.run(node.el.id, () => {
            this.eventDispatcher.dispatch(LOADING_RUN);
            this.nodeRepository.save(node)
                .then(res => res.json())
                .then(result => {
                    console.log(result)
                    // this.removeNode(node);
                    // const newNode = new Node(data.data);
                    // this.renderNode(newNode);
                    // this.tree.addNode(newNode.el.id, newNode);
                    // this.drawLines();
                    this.updateNodeFields(result.data);
                })
                .finally(() => this.eventDispatcher.dispatch(LOADING_STOP));
        }, 1000);
    }

    private updateNodeFields(data: any) {
        let node = this.tree.getNode(data.id);

        console.dir(node);
        console.dir(data);
    }


    private updateNode(node: Node): void {
        Runner.run(node.el.id, () => {
            this.eventDispatcher.dispatch(LOADING_RUN);
            this.nodeRepository.save(node)
                .then(res => res.json())
                .then(data => {
                    // this.removeNode(node);
                    // const newNode = new Node(data.data);
                    // this.renderNode(newNode);
                    // this.tree.addNode(newNode.el.id, newNode);
                    this.rerender(data.data)
                })
                .finally(() => this.eventDispatcher.dispatch(LOADING_STOP));
        }, 1000);
    }

    private rerender(el: Element) {
        // el.id
        let node = this.tree.getNode(el.id);

    }

    private hideLines() {
        const svg = this.getSVG();
        if (!svg.classList.contains('hidden')) {
            svg.classList.add("hidden");
        }
    }

    private showLines() {
        const svg = this.getSVG();
        if (svg.classList.contains('hidden')) {
            svg.classList.remove("hidden");
        }
    }

    private getSVG(): SVGElement {
        return <SVGElement> this.app.querySelector('svg');
    }
}

export default GameGraph;
