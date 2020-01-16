import {shapes} from "jointjs";
import Rectangle = shapes.standard.Rectangle;

export class Node {

    constructor(
        public rect: Rectangle,
        public el: any,
        public cid: string | number,
        public text: string
    ) {
    }
}
