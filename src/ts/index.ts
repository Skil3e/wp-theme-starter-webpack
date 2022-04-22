import { Lightbox } from './Lightbox';
import "./src/dropdown";
import "./src/header";
import "./src/sidebar";

const lightbox = new Lightbox();
lightbox.listen();


// window.addEventListener( 'DOMContentLoaded', () => {
//     const preloader = document.getElementById( "preloader" )
//     document.body.classList.remove( "preload" );
//     setTimeout( () => {
//         if (preloader) {
//             document.body.removeChild( preloader );
//         }
//     }, 2000 )
// } );

