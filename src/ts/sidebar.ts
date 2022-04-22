const sidebar = document.getElementById( "site-sidebar" )
const sidebarBackdrop = document.getElementById( "sidebar-backdrop" )
const sidebarOpen = document.getElementById( "sidebar-open" )
const sidebarClose = document.getElementById( "sidebar-close" )

function openSidebar() {
    sidebar?.setAttribute( "open", "" )
    sidebarBackdrop?.setAttribute( "open", "" )
    sidebarBackdrop?.addEventListener( "click", closeSidebar, { once: true } )
}

function closeSidebar() {
    sidebar?.removeAttribute( "open" )
    sidebarBackdrop?.removeAttribute( "open" )
}

sidebarOpen?.addEventListener( "click", openSidebar )
sidebarClose?.addEventListener( "click", closeSidebar )

