import {EventDispatcher} from "../core/event";
import {CHANGE_SCALE, MOVE_SCREEN} from "../core/event/const";
import {KEY_CONTROL} from "../core/keyManager/keys";
import {Position} from './position';
import KeyManager from "../core/keyManager/keyManager";

const SCALE_STEP = 0.1;
const MIN_SCALE_STEP = 0.4;
const MAX_SCALE_STEP = 1.5;
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

        window.addEventListener('wheel', (e) => this.changeScale(e));

        this.scale = 1.0;
        this.graph.style.transform = 'matrix(1,0,0,1,0,0)';
        this.screenMoving();
        this.cursorMoving();
    }

    width() {
        const rect = this.graph.getBoundingClientRect();
        console.log(rect.right - rect.left);
        return rect.right - rect.left;
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

    private changeScale(e: WheelEvent) {
        let scaleDelta = -1 * SCALE_STEP;
        if (e.deltaY < 0) {
            scaleDelta = SCALE_STEP;
        }
        if (e.deltaY < 0 && this.scale >= MAX_SCALE_STEP) {
            return;
        } else if (e.deltaY > 0 && this.scale <= MIN_SCALE_STEP) {
            return;
        }
        let lastScale = this.scale;
        this.scale += scaleDelta;

        const matrix = this.getTransform();
        let mouseX = e.pageX - matrix.tx,
            mouseY = e.pageY - matrix.ty,
            newX = mouseX * (this.scale / lastScale),
            newY = mouseY * (this.scale / lastScale),
            deltaX = mouseX - newX,
            deltaY = mouseY - newY;

        matrix.tx += deltaX;
        matrix.ty += deltaY;

        matrix.a = this.scale;
        matrix.d = this.scale;

        this.setMatrix(matrix);
        this.eventDispatcher.dispatch(CHANGE_SCALE);
    }

    private setMatrix(matrix: Matrix) {
        this.graph.style.transform = matrix.toStyle();
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