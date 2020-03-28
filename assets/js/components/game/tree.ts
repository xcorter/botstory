import {Node} from './node'

export class Tree {

    nodes: { [viewId: string]: Node; };
    nodesQuestionId: { [questionId: number]: string; };

    constructor() {
        this.nodes = {};
        this.nodesQuestionId = {};
    }

    addNode(questionId: number, node: Node): void {
        this.nodes[node.viewId] = node;
        this.nodesQuestionId[questionId] = node.viewId;
    }

    getNode(questionId: number): Node {
        const viewId = this.nodesQuestionId[questionId];
        return this.getNodeByViewId(viewId);
    }

    getNodeByViewId(viewId: string): Node {
        return this.nodes[viewId];
    }

    removeNode(node: Node) {
        delete this.nodes[node.viewId];
        delete this.nodesQuestionId[node.el.id];
    }
}
