import {Tree} from "./tree";
import NodeRepository from "../repository/nodeRepository";
import {Node} from "./node";
import {EventDispatcher} from "../core/event";
import {NEW_NODE} from "../core/event/const";
import {Scale} from "./scale";

export class Menu {

    tree: Tree;
    nodeRepository: NodeRepository;
    eventDispatcher: EventDispatcher;
    scale: Scale;

    constructor(tree: Tree, nodeRepository: NodeRepository, eventDispatcher: EventDispatcher, scale: Scale) {
        this.tree = tree;
        this.nodeRepository = nodeRepository;
        this.eventDispatcher = eventDispatcher;
        this.scale = scale;
    }

    init() {
        const menu = document.querySelector('.menu');
        menu.querySelector('.new-node').addEventListener('click', () => {
            this.newNode();
        })
    }

    newNode() {
        const matrix = this.scale.getTransform();
        const nodePosition = {
            x: (window.screen.availWidth) / matrix.d / 2 - matrix.tx  * matrix.d,
            y: (window.screen.availHeight / 2) * matrix.d - matrix.ty,
        };
        const node = new Node({
            id: null,
            position: nodePosition,
            text: "",
            isStart: false,
            answers: []
        });
        this.nodeRepository.save(node).then((result) => {
            return result.json();
        }).then((response) => {
            const data = response.data;
            node.el.id = data.id;
            this.tree.addNode(data.id, node);
            this.eventDispatcher.dispatch(NEW_NODE, {
                'id': data.id
            });
        });
    }
}
