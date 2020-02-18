import {Node} from "../game/node";

class NodeRepository {
    save(node: Node):  Promise<Response> {
        return fetch('/admin/game/node/' + node.el.id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: node.toJson()
        })
    }
}
export default new NodeRepository();