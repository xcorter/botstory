import {Node} from './node'

export class Tree {

    nodes: { [questionId: number]: Node; };

    constructor() {
        this.nodes = {};
    }

    addNode(questionId: number, node: Node): void {
        this.nodes[questionId] = node;
    }

    getNode(questionId: number): Node {
        return this.nodes[questionId];
    }
}