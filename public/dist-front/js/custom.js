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
    if (!floatText) { return; }
    if (winScroll > 200) {
        floatText.style.opacity = "1";
        floatText.style.visibility = "visible";
    } else {
        floatText.style.opacity = "0";
        floatText.style.visibility = "hidden";
    }
}

var floatLink = document.querySelector('.float-text a');
if (floatLink) {
    floatLink.addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}



document.addEventListener('click', function (e) {
    var btn = e.target.closest('.share-copy');
    if (!btn) return;
    e.preventDefault();
    var url = btn.getAttribute('data-copy-url');
    var done = function () {
        var icon = btn.querySelector('i');
        var prevClass = icon ? icon.className : null;
        btn.classList.add('copied');
        if (icon) icon.className = 'fa-solid fa-check';
        setTimeout(function () {
            btn.classList.remove('copied');
            if (icon && prevClass) icon.className = prevClass;
        }, 1800);
    };
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(done).catch(function () { fallbackCopy(url, done); });
    } else {
        fallbackCopy(url, done);
    }
});

function fallbackCopy(text, done) {
    var ta = document.createElement('textarea');
    ta.value = text;
    ta.style.position = 'fixed';
    ta.style.opacity = '0';
    document.body.appendChild(ta);
    ta.focus();
    ta.select();
    try { document.execCommand('copy'); done(); } catch (e) {}
    document.body.removeChild(ta);
}

/* Stat bar: count numbers up when they scroll into view.
   Works with plain values like "61", "14+", "1,200" (Latin or Persian digits);
   anything else is left untouched. On RTL pages digits are shown in Persian. */
(function () {
    var nums = document.querySelectorAll('.stat b');
    if (!nums.length || !('IntersectionObserver' in window)) return;
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var fa = '۰۱۲۳۴۵۶۷۸۹', ar = '٠١٢٣٤٥٦٧٨٩';
    var rtl = document.documentElement.getAttribute('dir') === 'rtl';
    var toLatin = function (s) {
        return s.replace(/[۰-۹]/g, function (d) { return fa.indexOf(d); })
                .replace(/[٠-٩]/g, function (d) { return ar.indexOf(d); });
    };
    var localize = function (s) {
        return rtl ? String(s).replace(/\d/g, function (d) { return fa[d]; }) : String(s);
    };

    var run = function (el) {
        var m = toLatin(el.textContent.trim()).match(/^(\D*)(\d[\d,]*)(\D*)$/);
        if (!m) return;
        var target = parseInt(m[2].replace(/,/g, ''), 10);
        var comma = m[2].indexOf(',') > -1;
        var fmt = function (n) { return comma ? n.toLocaleString('en-US') : String(n); };
        var start = null, dur = 1400;
        var step = function (t) {
            if (!start) start = t;
            var p = Math.min((t - start) / dur, 1);
            var val = Math.round(target * (1 - Math.pow(1 - p, 3)));
            el.textContent = localize(m[1] + fmt(val) + m[3]);
            if (p < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    };

    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
            if (e.isIntersecting) { run(e.target); io.unobserve(e.target); }
        });
    }, { threshold: 0.5 });
    nums.forEach(function (el) { io.observe(el); });
})();

/* About slideshow: cross-fades the about photos every 5 seconds; dots jump to a photo.
   Pauses while the pointer is over it and when the tab is hidden. */
(function () {
    document.querySelectorAll('[data-slideshow]').forEach(function (box) {
        var imgs = box.querySelectorAll('img');
        var dots = box.querySelectorAll('.about-dots button');
        if (imgs.length < 2) return;
        var i = 0, timer = null, paused = false;
        var show = function (n) {
            imgs[i].classList.remove('is-active'); if (dots[i]) dots[i].classList.remove('is-active');
            i = (n + imgs.length) % imgs.length;
            imgs[i].classList.add('is-active'); if (dots[i]) dots[i].classList.add('is-active');
        };
        var start = function () { stop(); timer = setInterval(function () { if (!paused && !document.hidden) show(i + 1); }, 5000); };
        var stop = function () { if (timer) clearInterval(timer); };
        dots.forEach(function (d, n) { d.addEventListener('click', function () { show(n); start(); }); });
        box.addEventListener('mouseenter', function () { paused = true; });
        box.addEventListener('mouseleave', function () { paused = false; });
        if (!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches)) start();
    });
})();

/* Video player window.
   Anything with data-video-embed (YouTube / Instagram / Facebook / Vimeo player address) or
   data-video-file (uploaded video) opens in a window on this site when clicked. Nothing is
   loaded from those platforms before the click, and the player is removed again on close. */
(function () {
    var modal, box, lastFocus;
    var rtl = document.documentElement.getAttribute('dir') === 'rtl';

    function build() {
        modal = document.createElement('div');
        modal.className = 'vmodal';
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-modal', 'true');
        modal.setAttribute('aria-label', rtl ? 'پخش ویدیو' : 'Video player');
        modal.innerHTML = '<div class="vmodal-box"><button type="button" class="vmodal-close" aria-label="' +
            (rtl ? 'بستن' : 'Close') + '">&times;</button><div class="vmodal-frame"></div></div>';
        document.body.appendChild(modal);
        box = modal.querySelector('.vmodal-box');
        modal.addEventListener('click', function (e) {
            if (e.target === modal || e.target.closest('.vmodal-close')) close();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('is-open')) close();
        });
    }

    function open(el) {
        if (!modal) build();
        lastFocus = el;
        var frame = modal.querySelector('.vmodal-frame');
        var embed = el.getAttribute('data-video-embed');
        var file = el.getAttribute('data-video-file');
        box.classList.toggle('is-portrait', el.getAttribute('data-video-portrait') === '1');
        frame.innerHTML = '';
        if (embed) {
            var f = document.createElement('iframe');
            f.src = embed;
            f.setAttribute('allow', 'autoplay; encrypted-media; picture-in-picture; fullscreen');
            f.setAttribute('allowfullscreen', '');
            f.setAttribute('referrerpolicy', 'strict-origin-when-cross-origin');
            f.setAttribute('title', rtl ? 'ویدیو' : 'Video');
            frame.appendChild(f);
        } else if (file) {
            var v = document.createElement('video');
            v.src = file;
            v.controls = true;
            v.autoplay = true;
            v.setAttribute('playsinline', '');
            frame.appendChild(v);
        }
        modal.classList.add('is-open');
        document.documentElement.classList.add('vmodal-lock');
        modal.querySelector('.vmodal-close').focus();
    }

    function close() {
        modal.classList.remove('is-open');
        document.documentElement.classList.remove('vmodal-lock');
        modal.querySelector('.vmodal-frame').innerHTML = ''; // stops playback and unloads the platform's player
        if (lastFocus) lastFocus.focus();
    }

    document.addEventListener('click', function (e) {
        var el = e.target.closest('[data-video-embed],[data-video-file]');
        if (!el) return;
        e.preventDefault();
        open(el);
    });
})();

/* Poll form: mark answered questions and show progress next to the submit button. */
(function () {
    var form = document.querySelector('.poll-form');
    if (!form) return;
    var questions = form.querySelectorAll('.poll-q');
    var done = form.querySelector('[data-poll-done]');
    var bar = form.querySelector('[data-poll-bar]');
    var faDigits = form.getAttribute('data-digits') === 'fa';

    function digits(n) {
        return faDigits ? String(n).replace(/\d/g, function (d) { return '۰۱۲۳۴۵۶۷۸۹'[d]; }) : String(n);
    }

    function update() {
        var answered = 0;
        questions.forEach(function (q) {
            var text = q.querySelector('textarea');
            var isDone = !!q.querySelector('input:checked') || (text && text.value.trim() !== '');
            q.classList.toggle('is-done', isDone);
            if (isDone) answered++;
        });
        if (done) done.textContent = digits(answered);
        if (bar) bar.style.width = (questions.length ? answered / questions.length * 100 : 0) + '%';
    }

    form.addEventListener('change', update);
    form.addEventListener('input', update);
    update();
})();

/* Home polls slider: arrow buttons + hide them when everything fits. */
(function () {
    document.querySelectorAll('[data-ptrack]').forEach(function (wrap) {
        var track = wrap.querySelector('.ptrack');
        var prev = wrap.querySelector('[data-ptrack-prev]');
        var next = wrap.querySelector('[data-ptrack-next]');
        if (!track) return;
        var rtl = getComputedStyle(track).direction === 'rtl';

        function step() {
            var card = track.firstElementChild;
            var gap = parseFloat(getComputedStyle(track).columnGap) || 0;
            return card ? card.getBoundingClientRect().width + gap : track.clientWidth;
        }

        function update() {
            var max = track.scrollWidth - track.clientWidth;
            var pos = Math.abs(track.scrollLeft); // RTL scrollLeft is negative
            wrap.classList.toggle('has-overflow', max > 2);
            if (prev) prev.disabled = pos <= 2;
            if (next) next.disabled = pos >= max - 2;
        }

        function go(dir) {
            track.scrollBy({ left: dir * step() * (rtl ? -1 : 1) });
        }

        // Desktop hover: the pointed card becomes square (width = height) and the other
        // cards on screen shrink to make room, so the row keeps its width.
        var hoverMq = window.matchMedia('(hover: hover) and (min-width: 992px)');
        function resetSizes() {
            track.classList.remove('is-hovering');
            Array.prototype.forEach.call(track.children, function (c) { c.style.flexBasis = ''; });
        }
        Array.prototype.forEach.call(track.children, function (card) {
            card.addEventListener('mouseenter', function () {
                if (!hoverMq.matches) return;
                var box = track.getBoundingClientRect();
                var visible = Array.prototype.filter.call(track.children, function (c) {
                    var r = c.getBoundingClientRect();
                    return r.right > box.left + 2 && r.left < box.right - 2;
                });
                if (visible.length < 2 || visible.indexOf(card) === -1) return;
                var gap = parseFloat(getComputedStyle(track).columnGap) || 0;
                var square = card.getBoundingClientRect().height;
                var rest = (track.clientWidth - gap * (visible.length - 1) - square) / (visible.length - 1);
                if (rest < 140) return; // not enough room: leave the layout as it is
                track.classList.add('is-hovering');
                visible.forEach(function (c) { c.style.flexBasis = (c === card ? square : rest) + 'px'; });
            });
        });
        track.addEventListener('mouseleave', resetSizes);
        hoverMq.addEventListener && hoverMq.addEventListener('change', resetSizes);
        if (prev) prev.addEventListener('click', function () { go(-1); });
        if (next) next.addEventListener('click', function () { go(1); });
        track.addEventListener('scroll', update, { passive: true });
        window.addEventListener('resize', update);
        update();
    });
})();

/* Header over a hero photograph: transparent at the top of the page, solid once scrolled.
   The hero is pulled up under the bar, so the bar's height has to be measured. */
(function () {
    var body = document.body;
    if (!body.classList.contains('float-head') || body.classList.contains('no-float')) return;
    var head = document.querySelector('.masthead');
    if (!head) return;

    function measure() {
        document.documentElement.style.setProperty('--masthead-h', head.offsetHeight + 'px');
    }
    function onScroll() {
        head.classList.toggle('is-solid', window.scrollY > 20);
    }

    measure();
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', measure);
    if (document.fonts && document.fonts.ready) { document.fonts.ready.then(measure); }
})();
