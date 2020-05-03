import {EventDispatcher} from "../core/event";
import {CHANGE_SCALE, MOVE_SCREEN} from "../core/event/const";
import {KEY_CONTROL} from "../core/keyManager/keys";
import {Position} from './position';
import KeyManager from "../core/keyManager/keyManager";

const SCALE_STEP = 0.1;
const MIN_SCALE_STEP = 0.4;
const MOVE_STEP_HORIZONTAL = 15;
const MOVE_STEP_VERTICAL = 15;

class Matrix {
    constructor(
        public a: number,
        public b: number,
        public c: number,
        public d: number,
        public tx: number,
        public ty: number,
    ) {
    }

    toStyle() {
        let str = [this.a, this.b, this.c, this.d, this.tx, this.ty].join(', ');
        return 'matrix(' + str + ')';
    }
}

export class Scale {

    private scale: number;

    graph: HTMLElement;
    eventDispatcher: EventDispatcher;
    keyManager: KeyManager;

    movingMode: boolean = false;
    startMoving: boolean = false;
    startPosition: Position;

    constructor(graph: HTMLElement, eventDispatcher: EventDispatcher, keyManager: KeyManager) {
        this.graph = graph;
        this.eventDispatcher = eventDispatcher;
        this.keyManager = keyManager;

        window.addEventListener('wheel', (e) => {
            if (e.deltaY < 0) {
                this.increaseScale(e);
            }
            else if (e.deltaY > 0) {
                this.decreaseScale(e);
            }
        });

        this.scale = 1.0;
        this.graph.style.transform = 'matrix(1,0,0,1,0,0)';
        this.screenMoving();
        this.cursorMoving();
    }

    cursorMoving() {
        document.addEventListener('keydown', (e) => {
            if (e.key === KEY_CONTROL) {
                if (!document.body.classList.contains('moving')) {
                    document.body.classList.add('moving');
                    this.movingMode = true;
                }
            }
        });

        document.addEventListener('keyup', (e) => {
            if (e.key === KEY_CONTROL) {
                if (document.body.classList.contains('moving')) {
                    document.body.classList.remove('moving');
                    this.movingMode = false;
                }
            }
        });

        document.querySelector('svg').addEventListener('mousedown', (e: MouseEvent) => {
            this.startMoving = true;
            const matrix = this.getTransform();
            this.startPosition = {
                x: e.clientX - matrix.tx,
                y: e.clientY - matrix.ty,
            };
        });

        document.querySelector('svg').addEventListener('mouseup', (e: MouseEvent) => {
            this.startMoving = false;
        });
        document.querySelector('svg').addEventListener('mousemove', (e: MouseEvent) => {
            if (!this.startMoving) {
                return;
            }
            if (!this.keyManager.isKeyDown(KEY_CONTROL)) {
                return;
            }
            const transform = this.getTransform();
            transform.tx = e.clientX - this.startPosition.x;
            transform.ty = e.clientY - this.startPosition.y;
            this.setMatrix(transform);
            this.eventDispatcher.dispatch(MOVE_SCREEN);
        });
    }

    screenMoving() {
        document.addEventListener('keydown', event => {
            if (['ArrowRight', 'ArrowLeft', 'ArrowUp', 'ArrowDown'].indexOf(event.key) === -1) {
                return;
            }
            if (event.key === 'ArrowRight') {
                this.right();
            } else if (event.key === 'ArrowLeft') {
                this.left();
            } else if (event.key === 'ArrowUp') {
                this.up();
            } else if (event.key === 'ArrowDown') {
                this.down();
            }
            this.eventDispatcher.dispatch(MOVE_SCREEN);
        });
    }

    increaseScale(e: WheelEvent) {
        if (this.scale >= 1.5) {
            return;
        }
        this.scale += SCALE_STEP;
        this.changeScale(this.scale, true);
        this.updateWidth();
        this.eventDispatcher.dispatch(CHANGE_SCALE);
    }

    decreaseScale(e: WheelEvent) {
        if (this.scale <= MIN_SCALE_STEP) {
            return;
        }
        this.scale -= SCALE_STEP;
        this.changeScale(this.scale, false);
        this.updateWidth();
        this.eventDispatcher.dispatch(CHANGE_SCALE);
    }

    right() {
        const matrix = this.getTransform();
        matrix.tx = matrix.tx - MOVE_STEP_HORIZONTAL;
        this.setMatrix(matrix);
    }

    left() {
        const matrix = this.getTransform();
        matrix.tx = matrix.tx + MOVE_STEP_HORIZONTAL;
        this.setMatrix(matrix);
    }

    up() {
        const matrix = this.getTransform();
        matrix.ty = matrix.ty + MOVE_STEP_VERTICAL;
        this.setMatrix(matrix);
    }

    down() {
        const matrix = this.getTransform();
        matrix.ty = matrix.ty - MOVE_STEP_VERTICAL;
        this.setMatrix(matrix);
    }

    private changeScale(value: number, increase: boolean) {
        const matrix = this.getTransform();

        if (increase) {
            matrix.ty -= 30 / this.scale;
            matrix.tx += 30 / this.scale;
        } else {
            matrix.ty += 30 / this.scale;
            matrix.tx -= 30 / this.scale;
        }

        matrix.a = value;
        matrix.d = value;
        this.setMatrix(matrix);
    }

    private setMatrix(matrix: Matrix) {
        this.graph.style.transform = matrix.toStyle();
    }

    private updateWidth() {
        this.graph.style.width = 'calc(100% / ' + this.scale + ')'
    }

    getTransform(): Matrix {
        let matrixSource = this.graph.style.transform.replace(/matrix\((.*)\)/, '$1');
        const matrixArr = matrixSource.split(',');
        const matrixArrInt = matrixArr.map(value => parseFloat(value.trim()));
        return new Matrix(
            matrixArrInt[0],
            matrixArrInt[1],
            matrixArrInt[2],
            matrixArrInt[3],
            matrixArrInt[4],
            matrixArrInt[5],
        )
    }

    getScale() {
        return this.scale;
    }
}