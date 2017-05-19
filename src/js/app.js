/**
*  Resizes the menu on the top if the user starts scrolling
*/
window.onscroll = function(e) {
    var scrollTop = (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;
    var basicHeight = 138;
    var basicPaddingTop = 84;
    //var menu = document.getElementById('header');
    var menu = document.querySelector('.header');

    /**
    *  Adding a class name if scrolled down and setting the className back to 'header' if the user scrolls on the top
    */
    if (scrollTop >= 100) {
        menu.className = 'header scroll-header';
        menu.id='';
    }
    else {
        menu.className = 'header';
        menu.id='header'
    }
}