(function () {
  function initIcons() {
    if (window.lucide) {
      window.lucide.createIcons();
    }
  }

  function initHeader() {
    var header = document.querySelector('[data-header]');
    var menuButton = document.querySelector('[data-menu]');
    if (!header || !menuButton) return;

    menuButton.addEventListener('click', function () {
      header.classList.toggle('is-open');
    });
  }

  function initTabs() {
    document.querySelectorAll('.al-search__tabs, .al-segment').forEach(function (group) {
      group.addEventListener('click', function (event) {
        var button = event.target.closest('button');
        if (!button) return;
        group.querySelectorAll('button').forEach(function (item) {
          item.classList.remove('is-selected');
        });
        button.classList.add('is-selected');
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initIcons();
    initHeader();
    initTabs();
  });
})();
