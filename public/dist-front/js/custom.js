$(document).ready(function(){
    var nav = $('#main-nav');
    var topBar = $('.top-bar');

    if (nav.length) {
        var navTop = topBar.outerHeight(); 

        $(window).scroll(function () {
            if ($(this).scrollTop() > navTop) {
                nav.addClass("sticky-nav");
            } else {
                nav.removeClass("sticky-nav");
            }
        });
    }

});
window.onscroll = function() {
    let nav = document.getElementById("main-nav");
    if (window.scrollY > 100) {
        nav.classList.add("sticky-nav");
    } else {
        nav.classList.remove("sticky-nav");
    }
};



window.onscroll = function() {
    updateScrollProgress();
};

function updateScrollProgress() {
    const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
    const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    const scrolled = (winScroll / height) * 100;
    const progressBar = document.querySelector(".scroll-progress-bar");
    if (progressBar) {
        progressBar.style.height = scrolled + "%";
    }
    const floatText = document.querySelector(".float-text");
    if (winScroll > 200) {
        floatText.style.opacity = "1";
        floatText.style.visibility = "visible";
    } else {
        floatText.style.opacity = "0";
        floatText.style.visibility = "hidden";
    }
}

document.querySelector('.float-text a').addEventListener('click', function(e) {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
});


