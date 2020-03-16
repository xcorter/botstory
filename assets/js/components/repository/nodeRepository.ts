import {Node} from "../game/node";

export default class NodeRepository {

    gameId: number;

    constructor(gameId: number) {
        this.gameId = gameId;
    }

    save(node: Node):  Promise<Response> {
        return fetch('/admin/game/' + this.gameId + '/node/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: node.toJson()
        })
    }
}
