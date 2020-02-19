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
    }

    configureGraphArea() {
        this.graphNode.style.width = '100%';
        this.graphNode.style.height = '1024px';
    }

    showNodes(data: any): Tree {
        for (let i = 0; i < data.length; i++) {
            let el = data[i];
            const node = new Node(el);
            let view = node.render();
            this.graphNode.insertAdjacentHTML('beforeend', view);
            this.tree.addNode(el.id, node);
            this.addMove(node);
            this.setListeners(node);
        }
        return this.tree;
    }

    setListeners(node: Node) {
        const options = <HTMLElement>node.getEl().getElementsByClassName('options')[0];
        options.childNodes.forEach((option: HTMLElement) =>{
            option.addEventListener('input', (e) => {
                const target = <HTMLElement> e.target;
                const id = parseInt(target.dataset.id);
                node.updateAnswer(id);
                Runner.run(id, () => {
                    NodeRepository.save(node);
                }, 1000);
            })
        });

        const addButton = <HTMLElement>node.getEl().getElementsByClassName('option-title')[0];
        addButton.addEventListener('click', (e) => {
            node.addNewAnswer();
        });
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

    // showQuestionModal(cellView: CellView) {
    //     const cell = cellView.model;
    //     const modalEl = document.getElementsByClassName('question-modal')[0] as HTMLElement;
    //     const questionId = this.tree.getNodeByCellId(<string>cell.id).el.id;
    //     // (<HTMLInputElement>document.getElementById('modal-question-text')).value = this.tree.getNode(questionId).text;
    //     // Modal.show(modalEl);
    //     // document.getElementById('modal-question-id').innerText = questionId;
    //     // modalEl.getElementsByClassName('action-save')[0].addEventListener('click', this.saveQuestion.bind(this));
    // }

    saveQuestion(event: any) {
        event.target.removeEventListener('click', this.saveQuestion);
        const questionId = document.getElementById('modal-question-id').innerText;
        const text = (<HTMLInputElement>document.getElementById('modal-question-text')).value;
        const data = {
            text
        };
    }

    showGraph() {
        fetch(this.graphUrl)
            .then(res => res.json())
            .then((data) => this.showNodes(data))
            .catch(logger.error);
    }
}

export default GameGraph;
