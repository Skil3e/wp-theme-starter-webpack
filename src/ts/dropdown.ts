
(function dropdown() {
    function onClickOutsideDd(e) {
        e.stopPropagation();
        ddItems.forEach((item) => {
            if (e.target === item || !item.classList.contains('menu__item__dropdown--open')) {
                return
            }
            item.classList.remove('menu__item__dropdown--open');
        })
        window.removeEventListener("click", onClickOutsideDd);
    }
    const ddItems = document.querySelectorAll(".menu__item__dropdown");
    const dropdownLinks = document.querySelectorAll(".menu__link__dropdown-parent");

    dropdownLinks.forEach(dl => {
        dl.addEventListener("click", (e) => {
            e.preventDefault();
        })
    })

    ddItems.forEach((item) => {
        item.addEventListener("click", (e) => {
            e.stopPropagation();
            ddItems.forEach(it => {
                if (it != item) {
                    it.classList.remove('menu__item__dropdown--open')
                }
            })
            item.classList.toggle('menu__item__dropdown--open');
            window.addEventListener("click", onClickOutsideDd)
        })
    })
})();




