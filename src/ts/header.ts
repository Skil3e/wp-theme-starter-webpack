const header = document.getElementById( "site-header" );
const headerObserverTrigger = document.getElementById( "site-header-observer-trigger" );

let options = {
    rootMargin: "0px",
}

let headerObserver = new IntersectionObserver( ( entries ) => {
    entries.forEach( entry => {
        if (!entry.isIntersecting) {
            header?.classList.add( "scrolled" )
        } else {
            header?.classList.remove( "scrolled" )
        }
    } )
}, options )
if (headerObserverTrigger) {
    headerObserver.observe( headerObserverTrigger )
}
