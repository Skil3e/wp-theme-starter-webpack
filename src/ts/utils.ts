type elementArray = HTMLElement | null;

export function makeElem( elem: any, classes: string[], children?: HTMLElement | elementArray[]  ) {
    const element = document.createElement( elem );
    classes.forEach( cls => element.classList.add( cls ) );
    if (children && Array.isArray( children ) && children.length > 0) {
        children?.forEach( child => child && element.appendChild( child ) )
    } else if (children && children instanceof Element) {
        element.appendChild( children )
    }
    return element;
}
