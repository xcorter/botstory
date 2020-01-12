import GraphConfig from './graphConfig'
import {dia, shapes} from 'jointjs';
import logger from '../core/logger/error';

class GameGraph {

    constructor(targetElement) {
        this.graphUrl = targetElement.dataset.url;
        this.graph = new dia.Graph;
        this.tree = {};
        this.paper = new dia.Paper({
            el: targetElement,
            model: this.graph,
            width: 600,
            height: 800,
            gridSize: 10,
            drawGrid: true,
            background: {
                color: 'rgba(0, 255, 0, 0.3)'
            }
        });
    }

    createRect() {
        let rect = new shapes.standard.Rectangle();
        rect.resize(100, 40);
        rect.attr({
            body: GraphConfig.bodyStyle,
            label: {
                ...GraphConfig.labelStyle
            }
        });
        return rect;
    }

    showButtons(data) {
        for (let i = 0; i < data.length; i++) {
            let el = data[i];
            let rect = this.createRect();
            rect.attr({
                label: {
                    text: el.text
                }
            });
            rect.position(el.position.x, el.position.y);
            rect.addTo(this.graph);
            this.tree[el.id] = {
                rect: rect,
                el: el,
            };
        }
        return this.tree;
    }

    createLink(source, target) {
        let link = new shapes.standard.Link();
        link.source(source);
        link.target(target);
        return link;
    }

    showLinks(tree) {
        for (let key in tree) {
            console.log(key)
            let source = tree[key].rect;

            for (let answerKey in tree[key].el.answers) {
                let answer = tree[key].el.answers[answerKey];
                let target = tree[answer.id].rect;
                let link = this.createLink(source, target);
                link.appendLabel({
                    attrs: {
                        text: {
                            text: answer.text
                        }
                    }
                });
                link.addTo(this.graph);
            }
        }
    }

    showGraph() {
        fetch(this.graphUrl)
            .then(res => res.json())
            .then((data) => this.showButtons(data))
            .then((data) => this.showLinks(data))
            .catch(logger.error);
    }
}

export default GameGraph;