import {Node} from './node'
import {dia} from "jointjs";
import Link = dia.Link;

export class Tree {

    nodes: { [questionId: string]: Node; };
    reverseKeys: { [cellId: string]: string; };

    constructor() {
        this.nodes = {};
        this.reverseKeys = {};
    }

    addNode(questionId: string, node: Node): void {
        this.nodes[questionId] = node;
        this.reverseKeys[node.cid] = questionId;
    }

    getNode(questionId: string): Node {
        return this.nodes[questionId];
    }

    getNodeByCellId(cellId: string): Node {
        const key = this.reverseKeys[cellId];
        return this.nodes[key];
    }

    getAnswer(link: Link) {
        const linkId = link.id;
        console.log(link);
        const sourceId = link.attributes.target.id;
        const node = this.getNodeByCellId(sourceId);
        for (let i in node.el.answers) {
            if (node.el.answers[i].cid == linkId) {
                return node.el.answers[i];
            }
        }
    }
}