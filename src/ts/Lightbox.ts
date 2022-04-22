import EventEmitter from "./EventEmitter";
import { makeElem } from "./utils";

interface Options {
    selector?: string
    classPrefix?: string
    transitionDuration?: number
    showCloseButton: boolean
    iconClose: string
    closeOnClickOutside: boolean
    showArrows?: boolean
    arrowNext: string
    arrowPrev: string
    keyboardNavigation?: boolean
    touchNavigation?: boolean
    touchThreshold?: number
}

export class Lightbox extends EventEmitter {
    //Options
    transitionDuration: number
    classPrefix: string

    showCloseButton?: boolean
    closeOnClickOutside?: boolean
    iconClose: string

    showArrows?: boolean
    arrowNext: string
    arrowPrev: string

    keyboardNavigation?: boolean
    touchNavigation?: boolean
    touchThreshold: number


    // Elements / Functions
    urls: string[]
    nodes: NodeListOf<HTMLElement>
    currentIndex: number

    readonly nextButton: HTMLButtonElement
    readonly prevButton: HTMLButtonElement
    readonly closeButton: HTMLButtonElement
    readonly wrapper: Element
    readonly container: HTMLElement
    readonly currentImg: HTMLImageElement
    readonly nextImg: HTMLImageElement
    readonly prevImg: HTMLImageElement
    readonly imgContainerPrev: HTMLElement
    readonly imgContainer: HTMLElement
    readonly imgContainerNext: HTMLElement
    mouseEvents: {
        clientX: number
        offset: number
        isDown: boolean
    }

    constructor( options?: Options ) {
        super()
        const {
            selector = ".lightbox",
            transitionDuration = 500,
            classPrefix = "lightbox",
            showCloseButton = true,
            closeOnClickOutside = true,
            iconClose = `<svg class="${ classPrefix }__close-icon" viewBox="0 0 24 24" width="35" height="35" fill="gray" xmlns="http://www.w3.org/2000/svg"><path d="M17.7,16.2c0.4,0.4,0.4,1,0,1.4s-1,0.4-1.4,0L12,13.4l-4.2,4.2c-0.4,0.4-1,0.4-1.4,0s-0.4-1,0-1.4l4.2-4.2L6.3,7.8 C6,7.4,6,6.7,6.3,6.3s1-0.4,1.4,0l4.2,4.2l4.2-4.2c0.4-0.4,1-0.4,1.4,0s0.4,1,0,1.4L13.4,12L17.7,16.2z"></path></svg>`,
            showArrows = true,
            arrowNext = `<svg class="${ classPrefix }__next-icon" viewBox="0 0 24 24" width="35" height="35" fill="lightgray" xmlns="http://www.w3.org/2000/svg"><path d="M15.8,12.6l-5,6C10.6,18.9,10.3,19,10,19c-0.2,0-0.5-0.1-0.6-0.2c-0.4-0.4-0.5-1-0.1-1.4c0,0,0,0,0,0l4.5-5.4L9.4,6.6C9,6.2,9.1,5.6,9.5,5.2c0,0,0,0,0,0c0.4-0.4,1-0.3,1.4,0.1c0,0,0,0,0,0.1l4.8,6C16.1,11.7,16.1,12.3,15.8,12.6z"></path></svg>`,
            arrowPrev = `<svg class="${ classPrefix }__prev-icon" viewBox="0 0 24 24" width="35" height="35" fill="lightgray" xmlns="http://www.w3.org/2000/svg"><path d="M14.6,17.4c0.4,0.4,0.3,1.1-0.1,1.4c-0.2,0.2-0.4,0.2-0.7,0.2c-0.3,0-0.6-0.1-0.8-0.4l-4.8-6c-0.3-0.4-0.3-0.9,0-1.3l5-6c0.4-0.4,1-0.5,1.4-0.1c0.4,0.4,0.5,1,0.1,1.4L10.3,12L14.6,17.4z"></path></svg>`,
            keyboardNavigation = true,
            touchNavigation = true,
            touchThreshold = 100,
        } = options || {};
        //-----------------------------------------------------------
        // Mouse events
        //-----------------------------------------------------------
        this.mouseEvents = {
            clientX: 0,
            offset : 0,
            isDown : false
        }
        //-----------------------------------------------------------
        // Setting up options
        //-----------------------------------------------------------
        this.transitionDuration = transitionDuration;
        this.classPrefix = classPrefix;
        this.showCloseButton = showCloseButton;
        this.closeOnClickOutside = closeOnClickOutside;
        this.showArrows = showArrows;
        this.iconClose = iconClose;
        this.arrowNext = arrowNext;
        this.arrowPrev = arrowPrev;
        this.keyboardNavigation = keyboardNavigation;
        this.touchNavigation = touchNavigation;
        this.touchThreshold = touchThreshold;
        //-----------------------------------------------------------
        // Select all nodes
        //-----------------------------------------------------------
        this.nodes = document.querySelectorAll( selector );
        //-----------------------------------------------------------
        // Create array of all the nodes urls
        //-----------------------------------------------------------
        this.urls = [];
        Array.from( this.nodes ).map( item => {
            const dataAttribute = item.getAttribute( "data-lightbox" );
            const href = item.getAttribute( "href" );
            const src = item.getAttribute( "src" );
            // const imgSrc = item.getAttribute( "href" ) ? item.getAttribute( "href" ) : item.getAttribute( "data-lightbox" );
            const imgSrc = dataAttribute ? dataAttribute : src ? src : href;
            if (!imgSrc) {
                console.warn( "Haven't set an attribute on one ore more of your lightbox elements. (href or data-lightbox atribute are required)" )
                return;
            }
            this.urls.push( imgSrc )
        } );

        this.currentIndex = 0;
        //-----------------------------------------------------------
        // Create images
        //-----------------------------------------------------------
        this.prevImg = makeElem( "img", [ `${ this.classPrefix }__image` ] );
        this.currentImg = makeElem( "img", [ `${ this.classPrefix }__image` ] );
        this.nextImg = makeElem( "img", [ `${ this.classPrefix }__image` ] );
        //-----------------------------------------------------------
        // Create images containers
        //-----------------------------------------------------------
        this.imgContainerPrev = makeElem( "div", [ `${ this.classPrefix }__image-container` ], this.prevImg );
        this.imgContainer = makeElem( "div", [ `${ this.classPrefix }__image-container` ], this.currentImg );
        this.imgContainerNext = makeElem( "div", [ `${ this.classPrefix }__image-container` ], this.nextImg );
        this.imgContainerPrev.style.transform = "translate(-100%)";
        this.imgContainer.style.transform = "translate(-100%)";
        this.imgContainerNext.style.transform = "translate(-100%)";

        //-----------------------------------------------------------
        // Create main container
        //-----------------------------------------------------------
        this.container = makeElem(
            "div",
            [ `${ this.classPrefix }__container` ],
            [ this.imgContainerPrev, this.imgContainer, this.imgContainerNext ]
        );
        //-----------------------------------------------------------
        // Create arrow navigation & close button
        //-----------------------------------------------------------
        this.closeButton = this.showCloseButton ? makeElem( "button", [ `${ this.classPrefix }__button`, `${ this.classPrefix }__button--close` ] ) : null;
        if (this.showCloseButton) {
            this.closeButton.innerHTML = this.iconClose;
            this.closeButton.onclick = this.close.bind( this );
        }
        this.prevButton = this.showArrows ? makeElem( "button", [ `${ this.classPrefix }__button`, `${ this.classPrefix }__button--prev` ] ) : null;
        this.nextButton = this.showArrows ? makeElem( "button", [ `${ this.classPrefix }__button`, `${ this.classPrefix }__button--next` ] ) : null;
        if (this.showArrows) {
            this.nextButton.innerHTML = this.arrowNext;
            this.prevButton.innerHTML = this.arrowPrev;
            this.prevButton.onclick = this.previous.bind( this );
            this.nextButton.onclick = this.next.bind( this );
        }
        //-----------------------------------------------------------
        // Create Lightbox Wrapper
        //-----------------------------------------------------------
        this.wrapper = makeElem(
            "div",
            [ `${ this.classPrefix }__wrapper` ],
            [ this.container, this.closeButton, this.prevButton, this.nextButton ]
        );
    }


    listen() {
        this.nodes.forEach( ( item, index ) => {
            item.addEventListener( "click", ( e ) => {
                e.preventDefault();
                this.keyboardListener();
                this.touchListeners();
                this.onClickOutside();
                this.open( index )
                this.emit( "open", index, item );
            } )
        } )
    }

    open( index: number ) {
        document.body.style.overflow = "hidden";
        this.currentIndex = index;
        this.setSrc()
        document.body.appendChild( this.wrapper );
    }

    close() {
        document.body.style.overflow = "";
        if (!document.body.contains( this.wrapper )) {
            return
        }
        this.removeListeners()
        document.body.removeChild( this.wrapper );
        this.emit( "close", "" );
    }

    private onClickOutside() {
        if (!this.closeOnClickOutside) {
            return
        }
        document.addEventListener( "click", e => {
            const target = e.target as HTMLTextAreaElement;
            if (target.className === `${ this.classPrefix }__image-container`) {
                this.close()
            }
        } )
    }

    private setSrc() {
        this.prevImg.src = this.urls[(this.currentIndex + this.urls.length - 1) % this.urls.length];
        this.currentImg.src = this.urls[this.currentIndex];
        this.nextImg.src = this.urls[(this.currentIndex + 1) % this.urls.length];

        this.imgContainer.style.transition = "";
        this.imgContainerNext.style.transition = "";
        this.imgContainerPrev.style.transition = "";
        this.imgContainerPrev.style.transform = "translate(-100%)";
        this.imgContainer.style.transform = "translate(-100%)";
        this.imgContainerNext.style.transform = "translate(-100%)";
        if (this.showArrows) {
            this.nextButton.disabled = false;
            this.prevButton.disabled = false;
        }
    }

    //-----------------------------------------------------------
    // Navigation functions
    //-----------------------------------------------------------
    private next() {

        this.currentIndex = (this.currentIndex + 1) % this.urls.length;

        this.imgContainer.style.transform = "translate(-200%)";
        this.imgContainerNext.style.transform = "translate(-200%)";

        this.imgContainer.style.transition = `${ this.transitionDuration }ms ease-in-out`;
        this.imgContainerNext.style.transition = `${ this.transitionDuration }ms ease-in-out`;
        if (this.showArrows) {
            this.nextButton.disabled = true;
            this.prevButton.disabled = true;
        }
        this.emit( "next", this.currentIndex );
        setTimeout( this.setSrc.bind( this ), this.transitionDuration );
    }

    private previous() {
        this.currentIndex = (this.currentIndex + this.urls.length - 1) % this.urls.length

        this.imgContainer.style.transform = "translate(100%)";
        this.imgContainerPrev.style.transform = "translate(0)";

        this.imgContainer.style.transition = `${ this.transitionDuration }ms ease-in-out`;
        this.imgContainerPrev.style.transition = `${ this.transitionDuration }ms ease-in-out`;
        if (this.showArrows) {
            this.nextButton.disabled = true;
            this.prevButton.disabled = true;
        }
        this.emit( "previous", this.currentIndex );
        setTimeout( this.setSrc.bind( this ), this.transitionDuration )
    }

    private touchListeners() {
        if (!this.touchNavigation) {
            return
        }
        this.currentImg.addEventListener( "mousedown", this.onMouseDown.bind( this ) );
        this.currentImg.addEventListener( "mousemove", this.onMouseMove.bind( this ) );
        this.currentImg.addEventListener( "mouseup", this.onRelease.bind( this ) );
        this.currentImg.addEventListener( "mouseleave", this.onRelease.bind( this ) );

        this.currentImg.addEventListener( "touchstart", this.onTouchStart.bind( this ), { passive: true } );
        this.currentImg.addEventListener( "touchmove", this.onTouchMove.bind( this ), { passive: true } );
        this.currentImg.addEventListener( "touchend", this.onRelease.bind( this ) );
    }

    private keyboardListener() {
        if (!this.keyboardNavigation) {
            return
        }
        document.addEventListener( "keydown", this.onKeyPress.bind( this ) )
    }


    //-----------------------------------------------------------
    // Navigation functions
    //-----------------------------------------------------------
    private onKeyPress( e ) {
        let pressed = false;
        if (e.key === "Escape") {
            this.close()
        }
        if (e.key === "ArrowRight") {
            if (pressed) {
                return
            }
            this.next()
            pressed = true
            setTimeout( () => pressed = false, this.transitionDuration )
        }
        if (e.key === "ArrowLeft") {
            if (pressed) {
                return
            }
            this.previous()
            pressed = true
            setTimeout( () => pressed = false, this.transitionDuration )
        }
    }

    private onMouseDown( e ) {
        e.preventDefault();
        this.mouseEvents.clientX = e.clientX - this.mouseEvents.offset
        this.mouseEvents.isDown = true
    }

    private onMouseMove( e ) {
        e.preventDefault()
        if (!this.mouseEvents.isDown) {
            return
        }
        let currentOffset = (e.clientX - this.mouseEvents.clientX);
        this.imgContainer.style.left = `${ currentOffset }px`;
        this.mouseEvents.offset = currentOffset;
    }

    private onTouchStart( e ) {
        this.mouseEvents.clientX = e.touches[0].clientX - this.mouseEvents.offset
    }

    private onTouchMove( e ) {
        let i = 0;
        const changedTouches = e.changedTouches.length;
        for (i; i < changedTouches; i++) {
            let currentOffset = (e.changedTouches[i].pageX - this.mouseEvents.clientX);
            this.imgContainer.style.left = `${ currentOffset }px`;
            this.mouseEvents.offset = currentOffset;
        }
    }

    private onRelease() {
        if (this.mouseEvents.offset <= -this.touchThreshold) {
            this.next()
            this.mouseEvents.offset = 0;
            this.mouseEvents.isDown = false
        } else if (this.mouseEvents.offset >= this.touchThreshold) {
            this.previous()
            this.mouseEvents.offset = 0;
            this.mouseEvents.isDown = false
        }
        this.mouseEvents.offset = 0;
        this.imgContainer.style.left = "0"
        this.mouseEvents.isDown = false
    }

    //-----------------------------------------------------------
    // Clean up functions
    //-----------------------------------------------------------
    private removeListeners() {
        if (!this.touchNavigation) {
            return
        }
        this.currentImg.removeEventListener( "mousedown", this.onMouseDown.bind( this ) );
        this.currentImg.removeEventListener( "mousemove", this.onMouseMove.bind( this ) );
        this.currentImg.removeEventListener( "mouseup", this.onRelease.bind( this ) );
        this.currentImg.removeEventListener( "mouseleave", this.onRelease.bind( this ) );
        this.currentImg.removeEventListener( "touchstart", this.onTouchStart.bind( this ) );
        this.currentImg.removeEventListener( "touchmove", this.onTouchMove.bind( this ) );
        this.currentImg.removeEventListener( "touchend", this.onRelease.bind( this ) );
        if (!this.keyboardNavigation) {
            return
        }
        document.removeEventListener( "keydown", this.onKeyPress.bind( this ) )
    }
}
