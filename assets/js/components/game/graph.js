import GraphConfig from './graphConfig'
import {dia, shapes} from 'jointjs';
import logger from '../core/logger/error';
import Runner from '../core/helper/singleRun'

class GameGraph {

    constructor(targetElement) {
        this.graphUrl = targetElement.dataset.url;
        this.graph = new dia.Graph;
        this.tree = {};
        this.linkFromCidToQuestionId = {};
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
        this.graph.on('change:position', (cell) => this.onMoveEvent(cell));
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
                cid: rect.id
            };
            this.linkFromCidToQuestionId[rect.id] = el.id;
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

    onMoveEvent(cell) {
        const questionId = this.linkFromCidToQuestionId[cell.id];
        Runner.run(questionId, () => {
            fetch('/admin/game/question/' + questionId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify({
                    locationX: cell.position().x,
                    locationY: cell.position().y,
                })
            });
        }, 2000);
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