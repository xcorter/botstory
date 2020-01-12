class Runner {

    constructor() {
        this.queue = {};
    }

    run(id, callback, time) {

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