export class EventDispatcher {

    events: any;

    constructor () {
        this.events = {};
    }

    addListener(event: string | string[], callback: (data?: any) => any) {
        if (typeof event === 'string') {
            this.addOneListener(event, callback);
            return;
        }
        event.forEach((value) => {
            this.addOneListener(value, callback);
        });
    }

    private addOneListener(event: string, callback: (data?: any) => any) {
        // Create the event if not exists
        if (this.events[event] === undefined) {
            this.events[event] = {
                listeners: []
            };
        }
        this.events[event].listeners.push(callback);
    }

    removeListener(event: string, callback: (data?: any) => any) {
        // Check if this event not exists
        if (this.events[event] === undefined) {
            return false;
        }
        this.events[event].listeners = this.events[event].listeners.filter(
            (listener: string) => {
                return listener.toString() !== callback.toString();
            }
        );
    }

    dispatch(event: string, data?: any) {
        // Check if this event not exists
        if (this.events[event] === undefined) {
            return false;
        }
        this.events[event].listeners.forEach((listener: any) => {
            listener(data);
        });
    }
}
