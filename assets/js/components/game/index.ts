import GameGraph from './graph';

const target = <HTMLElement>(document.getElementsByClassName('app')[0]);

const gameGraph = new GameGraph(target);
gameGraph.showGraph();