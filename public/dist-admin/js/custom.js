// Language tabs in admin forms: English | دری | Français | Deutsch.
// Built from the field names, so every form gets them without changes to its markup:
//   "<field>_fa"            → Dari tab
//   xl[table][fr][field]    → French tab (from the "other languages" component), same for de
//   "<field>" with a _fa twin → English tab
//   anything else (image, status, dates …) stays above the tabs, shared by all languages.
// Runs BEFORE TinyMCE is started further down, because moving an editor afterwards breaks it.
(function () {
  var LABELS = { en: 'English', fa: 'دری' };
  var uid = 0;

  function fieldBox(el, form) {
    var box = el.closest('.mb-3, .form-group');
    return box && form.contains(box) ? box : el.parentElement;
  }

  function langOf(box, faNames) {
    var langs = {};
    box.querySelectorAll('input[name], textarea[name], select[name]').forEach(function (f) {
      var n = f.getAttribute('name');
      if (/^xl\[/.test(n)) return; // handled with its <details> box
      if (/_fa(\[\])?$/.test(n)) langs.fa = 1;
      else if (faNames[n]) langs.en = 1;
      else langs.shared = 1;
    });
    var keys = Object.keys(langs);
    return keys.length === 1 && keys[0] !== 'shared' ? keys[0] : null; // mixed boxes stay put
  }

  function hasValue(pane) {
    return Array.prototype.some.call(pane.querySelectorAll('input[type=text], input:not([type]), textarea'), function (f) {
      return f.value && f.value.trim() !== '';
    });
  }

  function build(form) {
    var faNames = {};
    form.querySelectorAll('[name$="_fa"]').forEach(function (f) {
      faNames[f.getAttribute('name').replace(/_fa$/, '')] = 1;
    });
    if (!Object.keys(faNames).length) return;

    // Group the language fields by their parent element (one tab set per card / section).
    var groups = [];
    form.querySelectorAll('input[name], textarea[name]').forEach(function (f) {
      var box = fieldBox(f, form);
      if (!box || box.dataset.ltDone) return;
      box.dataset.ltDone = '1';
      var lang = langOf(box, faNames);
      if (!lang) return;
      var parent = box.parentElement;
      var g = groups.find(function (x) { return x.parent === parent; });
      if (!g) groups.push(g = { parent: parent, boxes: [] });
      g.boxes.push({ box: box, lang: lang });
    });
    groups = groups.filter(function (g) {
      var l = g.boxes.map(function (b) { return b.lang; });
      return l.indexOf('en') > -1 && l.indexOf('fa') > -1;
    });
    if (!groups.length) return;

    var extras = Array.prototype.slice.call(form.querySelectorAll('details.xl-box[data-locale]'));

    groups.forEach(function (g) {
      var id = 'lt' + (++uid);
      var order = ['en', 'fa'];
      var labels = { en: LABELS.en, fa: LABELS.fa };
      var live = {};
      // French / German boxes join the tabs when the form has a single language section.
      var ownExtras = groups.length === 1 ? extras : [];
      ownExtras.forEach(function (d) {
        var c = d.dataset.locale;
        order.push(c);
        labels[c] = d.dataset.localeLabel;
        live[c] = d.dataset.localeLive === '1';
      });

      var wrap = document.createElement('div');
      wrap.className = 'lt-wrap';
      var nav = document.createElement('div');
      nav.className = 'lt-nav';
      nav.setAttribute('role', 'tablist');
      wrap.appendChild(nav);
      var panes = {};
      order.forEach(function (c, i) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'lt-tab' + (i === 0 ? ' active' : '');
        btn.setAttribute('role', 'tab');
        btn.setAttribute('aria-controls', id + '-' + c);
        btn.dataset.lang = c;
        btn.innerHTML = '<span></span><i class="lt-check fa-solid fa-circle-check" aria-hidden="true"></i>';
        btn.querySelector('span').textContent = labels[c] + (live[c] === false ? ' (not published)' : '');
        nav.appendChild(btn);
        var pane = document.createElement('div');
        pane.className = 'lt-pane' + (i === 0 ? ' active' : '');
        pane.id = id + '-' + c;
        pane.setAttribute('role', 'tabpanel');
        pane.dataset.lang = c;
        panes[c] = pane;
        wrap.appendChild(pane);
      });

      // Put the tab set where the first language field was, then move the fields in.
      g.parent.insertBefore(wrap, g.boxes[0].box);
      g.boxes.forEach(function (b) { panes[b.lang].appendChild(b.box); });
      ownExtras.forEach(function (d) {
        d.open = true;
        d.classList.add('lt-inline');
        panes[d.dataset.locale].appendChild(d);
      });

      // Old section headings ("English", "دری (second language)") and their divider are no longer needed.
      g.parent.querySelectorAll(':scope > h6, :scope > hr').forEach(function (h) {
        var t = h.textContent.trim();
        if (h.tagName === 'HR' || t === 'English' || t.indexOf('دری') === 0) h.style.display = 'none';
      });

      function refresh() {
        nav.querySelectorAll('.lt-tab').forEach(function (btn) {
          btn.classList.toggle('filled', hasValue(panes[btn.dataset.lang]));
        });
      }
      nav.addEventListener('click', function (e) {
        var btn = e.target.closest('.lt-tab');
        if (!btn) return;
        nav.querySelectorAll('.lt-tab').forEach(function (b) { b.classList.toggle('active', b === btn); });
        Object.keys(panes).forEach(function (c) { panes[c].classList.toggle('active', c === btn.dataset.lang); });
      });
      wrap.addEventListener('input', refresh);
      refresh();
    });
  }

  document.querySelectorAll('form').forEach(build);

  // A required field in a hidden tab: open that tab so the browser can show the message.
  document.addEventListener('invalid', function (e) {
    var pane = e.target.closest && e.target.closest('.lt-pane');
    if (pane && !pane.classList.contains('active')) {
      var nav = pane.parentElement.querySelector('.lt-nav');
      var btn = nav && nav.querySelector('.lt-tab[data-lang="' + pane.dataset.lang + '"]');
      if (btn) btn.click();
    }
  }, true);
})();

// Sidebar active state — the "active" class is already set server-side (Blade);
// just open whichever dropdown contains the active link, then reveal the nav.
(function () {
  $('.sidebar-nav .nav-link.active').each(function () {
    var $dropdown = $(this).closest('.nav-dropdown');
    if ($dropdown.length) {
      $dropdown.addClass('open');
      $dropdown.find('.nav-dropdown-toggle').addClass('active');
    }
  });
  $('.sidebar-nav').addClass('ready');
})();

$(function () {
  // Sidebar dropdown toggle
  $('.nav-dropdown-toggle').on('click', function () {
    $(this).closest('.nav-dropdown').toggleClass('open');
  });

  // TinyMCE
  if ($('#editor1').length) {
    tinymce.init({
      selector: '#editor1',
      height: 300,
      menubar: false,
      plugins: 'lists link image media code table wordcount',
      media_live_embeds: true,
      toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | code | removeformat',
      block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6',
      content_style: 'body { font-family: system-ui, -apple-system, sans-serif; font-size: 14px; }',
      branding: false,
      promotion: false,
      skin: 'oxide',
      statusbar: true
    });
  }

  if ($('#editor2').length) {
    tinymce.init({
      selector: '#editor2',
      height: 300,
      menubar: false,
      plugins: 'lists link image media code table wordcount',
      media_live_embeds: true,
      toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | code | removeformat',
      block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6',
      content_style: 'body { font-family: system-ui, -apple-system, sans-serif; font-size: 14px; }',
      branding: false,
      promotion: false,
      skin: 'oxide',
      statusbar: true
    });
  }

  // Generic TinyMCE init for any other rich-text field (e.g. second-language
  // content columns) — give each one a unique id and class="tinymce-editor"
  document.querySelectorAll('textarea.tinymce-editor').forEach(function (el) {
    if (!el.id || (window.tinymce && tinymce.get(el.id))) return;
    tinymce.init({
      selector: '#' + el.id,
      height: 300,
      menubar: false,
      plugins: 'lists link image media code table wordcount',
      media_live_embeds: true,
      toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | code | removeformat',
      block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6',
      content_style: 'body { font-family: system-ui, -apple-system, sans-serif; font-size: 14px; }',
      branding: false,
      promotion: false,
      skin: 'oxide',
      statusbar: true
    });
  });

  // Flatpickr
  if ($('#datepicker').length) {
    flatpickr('#datepicker', { dateFormat: 'Y-m-d' });
  }
  if ($('#datepicker1').length) {
    flatpickr('#datepicker1', { dateFormat: 'Y-m-d' });
  }
  if ($('#datepicker2').length) {
    flatpickr('#datepicker2', { dateFormat: 'Y-m-d' });
  }
  if ($('#datepicker3').length) {
    flatpickr('#datepicker3', { dateFormat: 'Y-m-d' });
  }
  if ($('#timepicker').length) {
    flatpickr('#timepicker', { enableTime: true, noCalendar: true, dateFormat: 'h:i K', time_24hr: false });
  }
  if ($('#datetimepicker').length) {
    flatpickr('#datetimepicker', { enableTime: true, dateFormat: 'Y-m-d h:i K', time_24hr: false });
  }

  // Photo preview (form + profile)
  var $input = $('#photoUpload'), $preview = $('#photoPreview'), $icon = $('#previewIcon'), $wrap = $('#previewWrap'), $removeBtn = $('#removeBtn');

  if ($input.length) {
    $input.on('change', function () {
      var file = this.files[0];
      if (!file || !file.type.startsWith('image/')) return;
      var reader = new FileReader();
      reader.onload = function (e) {
        $preview.attr('src', e.target.result).removeClass('d-none');
        $icon.addClass('d-none');
        if ($wrap.hasClass('photo-preview-wrap')) {
          $wrap.addClass('has-photo');
        } else {
          $wrap.css('border-style', 'solid').css('border-color', 'var(--primary-300)').css('background', 'var(--gray-100)');
        }
        $removeBtn.addClass('show');
      };
      reader.readAsDataURL(file);
    });

    $removeBtn.on('click', function () {
      $input.val('');
      $preview.addClass('d-none').attr('src', '');
      $icon.removeClass('d-none');
      if ($wrap.hasClass('photo-preview-wrap')) {
        $wrap.removeClass('has-photo');
      } else {
        $wrap.css('border-style', 'dashed').css('border-color', '').css('background', 'var(--primary-600)');
      }
      $(this).removeClass('show');
    });
  }

  // DataTable
  if ($('#dataTable').length) {
    $('#dataTable').DataTable({
      pageLength: 10,
      order: [[0, 'asc']],
      scrollX: true,
      autoWidth: false,
      columnDefs: [{ orderable: false, targets: -1 }],
      language: {
        search: '_INPUT_',
        searchPlaceholder: 'Search Here ...\u2026',
        lengthMenu: 'Show _MENU_ entries'
      }
    });

    // Edit modal
    $(document).on('click', '.btn-edit-row', function () {
      $('#editName').val($(this).data('name'));
      $('#editEmail').val($(this).data('email'));
      new bootstrap.Modal($('#editModal')[0]).show();
    });
  }

  // Select2
  if ($('#languageSelect').length) {
    $('#languageSelect').select2({
      placeholder: 'Select a language',
      allowClear: true
    });
  }

});

// Open the "Add" form straight away when a page is reached via a "#add" link
// (used by the dashboard's quick-action buttons).
$(function () {
  if (window.location.hash === '#add' && $('#modal_add').length && window.bootstrap) {
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modal_add')).show();
    history.replaceState(null, '', window.location.pathname + window.location.search);
  }
});

// Top-bar search: type a section name ("events", "logo", "comments"...) and jump to it.
// Searches the links in the sidebar, so it only offers what this user is allowed to see.
$(function () {
  var $input = $('#adminSearch');
  var $box = $('#adminSearchResults');
  if (!$input.length) return;

  var items = [];
  $('.sidebar-nav a.nav-link').each(function () {
    var href = $(this).attr('href');
    if (!href || href.indexOf('javascript') === 0) return;
    var label = $.trim($(this).clone().children('.nav-badge').remove().end().text());
    var group = $.trim($(this).closest('.nav-dropdown').find('> .nav-dropdown-toggle .sidebar-label').first().text());
    items.push({ label: label, group: group, href: href, hay: (label + ' ' + group).toLowerCase() });
  });

  var render = function () {
    var q = $.trim($input.val()).toLowerCase();
    if (!q) { $box.removeClass('show').empty(); return; }
    var words = q.split(/\s+/);
    var hits = items.filter(function (it) {
      return words.every(function (w) { return it.hay.indexOf(w) > -1; });
    }).slice(0, 10);
    $box.empty();
    if (!hits.length) {
      $box.append($('<div class="none">').text('No section found for "' + q + '"'));
    } else {
      hits.forEach(function (it, i) {
        var $a = $('<a>').attr('href', it.href).toggleClass('focus', i === 0);
        $a.append($('<span>').text(it.label));
        if (it.group) $a.append($('<small>').text(it.group));
        $box.append($a);
      });
    }
    $box.addClass('show');
  };

  $input.on('input focus', render);
  $input.on('keydown', function (e) {
    var $links = $box.find('a');
    var idx = $links.index($links.filter('.focus'));
    if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
      e.preventDefault();
      idx = e.key === 'ArrowDown' ? Math.min(idx + 1, $links.length - 1) : Math.max(idx - 1, 0);
      $links.removeClass('focus').eq(idx).addClass('focus');
    } else if (e.key === 'Enter' && $links.length) {
      window.location = $links.filter('.focus').attr('href') || $links.first().attr('href');
    } else if (e.key === 'Escape') {
      $box.removeClass('show');
    }
  });
  $(document).on('click', function (e) {
    if (!$(e.target).closest('.topbar-search').length) $box.removeClass('show');
  });
});
