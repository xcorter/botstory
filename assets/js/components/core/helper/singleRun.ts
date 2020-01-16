class Runner {

    queue: any;

    constructor() {
        this.queue = {};
    }

    run(id: number, callback: () => void, time: number) {

        if (this.queue[id] != null) {
            clearTimeout(this.queue[id]);
            this.queue[id] = null;
        }

        this.queue[id] = setTimeout(() => {
            callback();
            this.queue[id] = null;
        }, time);
    }
}



export default new Runner();