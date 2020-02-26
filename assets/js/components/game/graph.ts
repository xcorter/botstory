import logger from '../core/logger/error';
import Runner from '../core/helper/singleRun'
import NodeRepository from '../repository/nodeRepository'
import {Tree} from "./tree";
import {Node} from "./node";

class GameGraph {

    graphUrl: string;
    graph: any;
    tree: Tree;
    graphNode: HTMLElement;

    constructor(targetElement: HTMLElement) {
        this.graphUrl = targetElement.dataset.url;
        this.tree = new Tree();
        this.graphNode = targetElement;
        this.configureGraphArea();
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
                    NodeRepository.save(node);
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
                    NodeRepository.save(node);
                }, 1000);
            });
        }

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
            };

            // 4. отследить окончание переноса
            title.onmouseup = () => {
                document.onmousemove = null;
                title.onmouseup = null;
                node.updatePosition();
                NodeRepository.save(node);
            }
        });

        title.addEventListener('dragstart', () => {
            return false;
        });
    }

    showGraph() {
        fetch(this.graphUrl)
            .then(res => res.json())
            .then((data) => this.showNodes(data))
            .catch(logger.error);
    }
}

export default GameGraph;
