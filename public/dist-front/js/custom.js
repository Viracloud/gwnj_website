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
