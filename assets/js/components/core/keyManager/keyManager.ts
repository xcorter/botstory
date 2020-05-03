export default class KeyManager {

    private keyDown: Map<string, boolean> = new Map();

    constructor() {
        addEventListener('keydown', (e) => this.keyDownHandler(e));
        addEventListener('keyup', (e) => this.keyUpHandler(e));
    }

    keyDownHandler(e: KeyboardEvent): void {
        this.keyDown.set(e.key, true);
    }

    keyUpHandler(e: KeyboardEvent): void {
        this.keyDown.set(e.key, false);
    }

    isKeyDown(key: string): boolean {
        if (this.keyDown.has(key)) {
            return this.keyDown.get(key);
        }
        return false;
    }
}