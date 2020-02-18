import GameGraph from './graph';

const target = <HTMLElement>(document.getElementsByClassName('graph')[0]);

const gameGraph = new GameGraph(target);
gameGraph.showGraph();