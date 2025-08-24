function toggleNavBtn(button) {
    let Btns = document.getElementsByClassName('link');
    const NavBtns = document.getElementById("navBtns");

    Array.from(Btns).forEach(element => {
        element.classList.remove('active');
    });

    /*document.getElementById(button).classList.add('active');*/

    if (window.innerWidth < 950) {
        if (NavBtns.style.display == "flex") {
            NavBtns.style.display = "none";
        }
    }

}

function toggleNavBtns(hamburger) {
    const NavBtns = document.getElementById("navBtns");
    if (NavBtns.style.display == "flex") {
        NavBtns.style.display = "none";
    } else {
        NavBtns.style.display = "flex";
    }

}

window.addEventListener('scroll', () => {
    /*
    if (window.scrollY == 0) {
        let Btns = document.getElementsByClassName('link');

        Array.from(Btns).forEach(element => {
            element.classList.remove('active');
        });

        document.getElementById('link-domu').classList.add('active');
    }
        */
});

function toggleServices(btn) {

    hidden = document.getElementsByClassName('hidden-services');

    if (btn.innerHTML.includes('ZOBRAZIT')) {
        btn.innerHTML = "<i class='fa-solid fa-eye-slash'></i>SKRÝT SLUŽBY";
    } else {
        btn.innerHTML = "<i class='fa-solid fa-eye'></i>ZOBRAZIT VŠECHNY SLUŽBY";
    }

    Array.from(hidden).forEach(element => {
        if (element.style.display == "flex") {
            element.style.display = "none";
        } else {
            element.style.display = "flex";
        }
    });
    
}