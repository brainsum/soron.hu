'use strict';(function(b,c,p){var l=c.documentElement||c.body;c.getElementById("header");var h=b.requestAnimationFrame||b.webkitRequestAnimationFrame||b.mozRequestAnimationFrame||b.msRequestAnimationFrame;if(!1!==(h&&l.getBoundingClientRect)){var e=b.pageYOffset,k=0,g=0,d=0,f=!1,n=function(a,b,c,d){return 1>(a/=d/2)?c/2*a*a*a+b:c/2*((a-=2)*a*a+2)+b},m=function(a){if(null===f){var c=a-g;b.scrollTo(0,n(c,e,e+d<k?d:k-e,500));500<c&&(f=!1,g=d=0)}!0===f&&(f=null,k=l.scrollHeight-b.innerHeight,e=b.pageYOffset,
g=a);h(m)};c=function(a){a.preventDefault();d+=140*(1<(a.wheelDelta||-a.detail)?-1:1);f=!0;console.log("> roll",g,d,e)};h(m);b.addEventListener("mousewheel",c);b.addEventListener("DOMMouseScroll",c)}})(window,document,void 0);