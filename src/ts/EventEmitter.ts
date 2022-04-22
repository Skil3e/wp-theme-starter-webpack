export default class EventEmitter {
    events: object

    constructor() {
        this.events = {};
    }

    on( name: string, cb: (...args) => void ) {
        if (!this.events[name]) {
            this.events[name] = [];
        }

        this.events[name].push( cb );
        return () => {
            this.off( name, cb );
        };
    }

    once( name: string, cb: ( ...args ) => void ) {
        const wrappedCB = ( ...args ) => {
            this.off( name, wrappedCB );
            cb( ...args );
        };
        this.on( name, wrappedCB );
    }

    emit( name: string, ...args: any[] ) {
        if (!this.events[name]) {
            return;
        }

        for (let cb of this.events[name]) {
            cb( ...args );
        }
    }

    off( name: string, cb: () => void ) {
        if (!this.events[name]) {
            return;
        }
        this.events[name] = this.events[name].filter( callback => callback !== cb );
    }
}
