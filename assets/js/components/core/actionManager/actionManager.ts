export default class ActionManager {

    private actions: Map<string, boolean> = new Map();

    add(action: string): void {
        this.actions.set(action, true);
    }

    has(action: string): boolean {
        return this.actions.has(action);
    }

    remove(action: string): void {
        this.actions.delete(action);
    }
}