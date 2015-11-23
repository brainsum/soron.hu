(function (window, document, undefined) {
    "use strict";

    var root = document.documentElement || document.body;
    var head = document.getElementById('header');
    var next = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.msRequestAnimationFrame;

    // Dependency validation

    if (false === (next && root.getBoundingClientRect)) {
        return;
    }

    /*!
     * ==================================
     * [BRAINSUM] TRANSITIONS
     * ==================================
     */
    var Fx = {
        linear: function (t) {
            return t
        },

        easeInQuad: function (t) {
            return t * t
        },

        easeOutQuad: function (t) {
            return t * (2 - t)
        },

        easeInOutQuad: function (t) {
            return t < .5 ? 2 * t * t : -1 + (4 - 2 * t) * t
        },

        easeInCubic: function (t) {
            return t * t * t
        },

        easeOutCubic: function (t) {
            return (--t) * t * t + 1
        },

        easeInOutCubic: function (t) {
            return t < .5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1
        },

        easeInQuart: function (t) {
            return t * t * t * t
        },

        easeOutQuart: function (t) {
            return 1 - (--t) * t * t * t
        },

        easeInOutQuart: function (t) {
            return t < .5 ? 8 * t * t * t * t : 1 - 8 * (--t) * t * t * t
        },

        easeInQuint: function (t) {
            return t * t * t * t * t
        },

        easeOutQuint: function (t) {
            return 1 + (--t) * t * t * t * t
        },

        easeInOutQuint: function (t) {
            return t < .5 ? 16 * t * t * t * t * t : 1 + 16 * (--t) * t * t * t * t
        }
    };

    /*!
     * ==================================
     * [BRAINSUM] SMOOTH-SCROLL
     * ==================================
     */
    var SCROLL_MOVE_PAGE = 140;
    var SCROLL_MOVE_TIME = 500;

    var page = window.pageYOffset;
    var view = 0;
    var time = 0;
    var diff = 0;
    var flag = false;

    var ease = function (t, b, c, d) {
        return (t /= d / 2) < 1 ? c / 2 * t * t * t + b : c / 2 * ((t -= 2 ) * t * t + 2) + b;
    };
    var loop = function (timestamp) {
        if (flag === null) {
            var t = timestamp - time;

            window.scrollTo(0, ease(t, page, page + diff < view ? diff : view - page, SCROLL_MOVE_TIME));

            if (t > SCROLL_MOVE_TIME) {
                flag = false;
                diff = 0;
                time = 0;
            }
        }
        if (flag === true) {
            flag = null;
            view = root.scrollHeight - window.innerHeight;
            page = window.pageYOffset;
            time = timestamp;
        }
        next(loop);
    };

    /**
     * Event listener for mouse scroll
     * @param e
     */
    var roll = function (e) {
        e.preventDefault();

        diff += SCROLL_MOVE_PAGE * ((e.wheelDelta || -e.detail) > 1 ? -1 : 1);
        flag = true;

        console.log('> roll', time, diff, page);
    };

    next(loop);

    // Attaching the event listeners

    window.addEventListener('mousewheel', roll);
    window.addEventListener('DOMMouseScroll', roll);

    //
    //if (next && root.getBoundingClientRect !== undefined) {
    //    var time = 500;
    //    var hash = {};
    //    var flag = null;
    //    var over = null;
    //
    //    // Helper definitions
    //
    //    var getPage = function () {
    //        return window.pageYOffset || root.scrollTop;
    //    };
    //    var getNode = function (node, absolute) {
    //        return absolute ? ~~(getPage() + node.getBoundingClientRect().top) : node.getBoundingClientRect();
    //    };
    //    var ease = function (t, b, c, d) {
    //        return (t /= d / 2) < 1 ? c / 2 * t * t * t + b : c / 2 * ((t -= 2 ) * t * t + 2) + b;
    //    };
    //    var roll = function (e) {
    //        var delta = Math.max(-1, Math.min(1, (e.wheelDelta || -e.detail)));
    //
    //        console.log('> roll', delta);
    //    };
    //    var move = function (nano, event) {
    //
    //        /** @var {Number} - Start position */
    //        var p1 = window.pageYOffset;
    //
    //        /** @var {Number} - End position (relative) */
    //        var p2 = getNode(nano.e).top;
    //
    //        /** @var {Number} - Start time */
    //        var t1;
    //
    //        /** @var {Number} - Scroll max */
    //        var sm = root.scrollHeight - window.innerHeight;
    //
    //        /** @var {Number} - Scroll end */
    //        var se = p1 + p2 < sm ? p2 : sm - p1;
    //
    //        // Handling header overflow if scrolling upwards
    //
    //        se < 0 && !0 === flag && (se -= 40);
    //
    //        /**
    //         * Local scrolling mechanism
    //         * @param timestamp
    //         */
    //        var scroll = function (timestamp) {
    //            var elapsed = timestamp - (t1 = t1 || timestamp);
    //
    //            window.scrollTo(0, ease(elapsed, p1, se, time));
    //            elapsed < time && next(scroll);
    //        };
    //        next(scroll);
    //        event.preventDefault();
    //
    //        window.location.hash = nano.n;
    //    };
    //
    //    // Initializing and caching target/link connections
    //
    //    console.warn('!', getPage(), window.innerHeight);
    //
    //    return;
    //
    //    window.addEventListener('click', function (e) {
    //        if (e.target.hash) {
    //            if (e.target.hash !== true) {
    //
    //            }
    //            return;
    //        }
    //        if (e.target.href) {
    //            var hash = e.target.href.split('#')[1] || true;
    //        }
    //
    //        if (node) {
    //            over && over !== node.o && over.classList.toggle(role, false);
    //            over = node.o;
    //            over.classList.toggle(role, true);
    //
    //            move(node, e);
    //        }
    //    }, true);
    //
    //    window.addEventListener('resize', function () {
    //        if (window.innerWidth >= 768) {
    //            next(function () {
    //                for (var i in hash) hash.hasOwnProperty(i) && (hash[i].p = getNode(hash[i].e));
    //            });
    //        }
    //    }, false);
    //}
    //

    /*!
     * ==================================
     * [BRAINSUM] FIXED/SLIDING HEADER
     * ==================================
     *
     */
    //if (head && head.style.transform !== undefined && Math.max(screen.width, screen.height) > 840) {
    //    flag = true;
    //
    //    var d;
    //    var p;
    //    var s = function () {
    //        next(function () {
    //            var c = d - (d = window.pageYOffset);
    //            var n = p + c;
    //
    //            n > 40 && (n = 40) || n < 0 && (n = 0);
    //
    //            if (n !== p) {
    //                p = n;
    //                t(head, p);
    //            }
    //        });
    //    };
    //    var t = function (node, y) {
    //        if (y === true) {
    //            flag = true;
    //            d = window.pageYOffset;
    //            y = p = 40 - (d < 40 ? d : 40);
    //
    //            window.addEventListener('scroll', s, false);
    //        }
    //        if (y === false) {
    //            flag = false;
    //            y = 0;
    //
    //            window.removeEventListener('scroll', s);
    //        }
    //        node.style.transform = "translate3d(0," + y + "px,0)";
    //    };
    //
    //    // Apply on-render positioning
    //
    //    if (window.innerWidth >= 768) {
    //        t(head, true);
    //    }
    //
    //    // Appending event listener for screen resizing
    //
    //    window.addEventListener('resize', function () {
    //        next(function () {
    //            var w = window.innerWidth >= 768;
    //
    //            !0 === w && !1 === flag && t(head, true);
    //            !1 === w && !0 === flag && t(head, false);
    //        });
    //    }, false);
    //}

})(window, document, void 0);
