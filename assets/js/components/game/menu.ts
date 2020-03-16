import {Tree} from "./tree";
import NodeRepository from "../repository/nodeRepository";
import {Node} from "./node";
import {EventDispatcher} from "../core/event";
import {NEW_NODE} from "../core/event/const";

export class Menu {

    tree: Tree;
    nodeRepository: NodeRepository;
    eventDispatcher: EventDispatcher;

    constructor(tree: Tree, nodeRepository: NodeRepository, eventDispatcher: EventDispatcher) {
        this.tree = tree;
        this.nodeRepository = nodeRepository;
        this.eventDispatcher = eventDispatcher;
    }

    init() {
        const menu = document.querySelector('.menu');
        menu.querySelector('.new-node').addEventListener('click', () => {
            this.newNode();
        })
    }

    newNode() {
        const node = new Node({
            id: null,
            position: {
                x: 0,
                y: 0,
            },
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
