// Arefin Admin - App JS

// Sidebar toggle (mobile)
function toggleSidebar() {
  const sb = document.getElementById('sidebar');
  const ov = document.getElementById('overlay');
  sb.classList.toggle('open');
  if (ov.style.display === 'block') {
    ov.style.display = 'none';
  } else {
    ov.style.display = 'block';
  }
}

$(function () {
  // Close dropdown on outside click
  $(document).on('click', function (e) {
    if (!$(e.target).closest('#userDropdown').length) $('#userMenu').removeClass('show');
  });
});
