const burgerMenu = document.querySelector('.burger-menu');
const burgerMenu2 = document.querySelector('.burger-menu2');
const sideBar = document.querySelector('.sidebar');
const mainContent = document.querySelector('.main-content');

burgerMenu.addEventListener('click', () => {
    sideBar.classList.toggle('close');
    mainContent.classList.toggle('close');
    // subMenu.classList.remove('dropdown')
    // arrowDownIcon.style.transform = ""
})

burgerMenu2.addEventListener('click', () => {
    sideBar.classList.toggle('mobile');
})

window.onresize = function (event) {
    const mediaQuery = window.matchMedia('(max-width: 768px)')
    // Check if the media query is true
    if (mediaQuery.matches) {
        sideBar.classList.remove('close');
    } else {
        mainContent.classList.remove('close');
    }
};


/* Sidebar dropdown menu */
const arrowDown = document.querySelector('.dropdown-arrow')
const arrowDownIcon = document.querySelector('.fa-caret-down')
const subMenu = document.querySelector('.sub-menu')
const dropdownButton = document.querySelector('#dropdown-button')


dropdownButton.addEventListener('click', () => {
    subMenu.classList.toggle('dropdown')
    if (arrowDownIcon.style.transform === "rotate(-180deg)") {
        arrowDownIcon.style.transform = ""

    } else {
        arrowDownIcon.style.transform = "rotate(-180deg)"
    }
})
