(function () {
  function initIcons() {
    if (window.lucide) {
      window.lucide.createIcons();
    }
  }

  function closeAllMenus(header) {
    header.classList.remove('is-open', 'is-mega-open');
    document.querySelectorAll('[data-mega]').forEach(function (item) {
      item.classList.remove('is-active');
      item.setAttribute('aria-expanded', 'false');
    });
  }

  function initHeader() {
    var header = document.querySelector('[data-header]');
    var menuButton = document.querySelector('[data-menu]');
    var megaButtons = document.querySelectorAll('[data-mega]');
    if (!header) return;

    if (menuButton) {
      menuButton.addEventListener('click', function () {
        var isOpen = header.classList.toggle('is-open');
        menuButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      });
    }

    megaButtons.forEach(function (button) {
      button.setAttribute('aria-expanded', button.classList.contains('is-active') ? 'true' : 'false');
      button.addEventListener('click', function () {
        var isActive = button.classList.contains('is-active') && header.classList.contains('is-mega-open');

        megaButtons.forEach(function (item) {
          item.classList.remove('is-active');
          item.setAttribute('aria-expanded', 'false');
        });

        if (!isActive) {
          button.classList.add('is-active');
          button.setAttribute('aria-expanded', 'true');
          header.classList.add('is-mega-open');
        } else {
          header.classList.remove('is-mega-open');
        }
      });
    });

    document.addEventListener('click', function (event) {
      if (!header.contains(event.target)) {
        closeAllMenus(header);
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        closeAllMenus(header);
        closeModal();
      }
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

  function setMessage(form, message, isError) {
    var target = form.querySelector('[data-form-message]');
    if (!target) return;
    target.textContent = message || '';
    target.classList.toggle('is-error', Boolean(isError));
  }

  function request(action, form) {
    var data = new FormData(form);
    data.append('action', action);
    data.append('nonce', window.AstraLandData ? window.AstraLandData.nonce : '');

    return fetch(window.AstraLandData.ajaxUrl, {
      method: 'POST',
      credentials: 'same-origin',
      body: data
    }).then(function (response) {
      return response.json().then(function (payload) {
        if (!response.ok || !payload.success) {
          throw new Error((payload.data && payload.data.message) || 'Có lỗi xảy ra.');
        }
        return payload.data;
      });
    });
  }

  function openModal(name) {
    var modal = document.querySelector('[data-modal="' + name + '"]');
    if (!modal) return;
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('al-modal-lock');
    var firstInput = modal.querySelector('input, select, textarea, button');
    if (firstInput) firstInput.focus();
  }

  function closeModal() {
    document.querySelectorAll('.al-modal.is-open').forEach(function (modal) {
      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
    });
    document.body.classList.remove('al-modal-lock');
  }

  function initModals() {
    document.querySelectorAll('[data-auth-open]').forEach(function (button) {
      button.addEventListener('click', function () {
        openModal('auth');
      });
    });

    document.querySelectorAll('[data-post-open]').forEach(function (button) {
      button.addEventListener('click', function () {
        openModal('post');
      });
    });

    document.querySelectorAll('[data-modal-close]').forEach(function (button) {
      button.addEventListener('click', closeModal);
    });

    document.querySelectorAll('[data-auth-tab]').forEach(function (tab) {
      tab.addEventListener('click', function () {
        var mode = tab.getAttribute('data-auth-tab');
        document.querySelectorAll('[data-auth-tab]').forEach(function (item) {
          item.classList.toggle('is-selected', item === tab);
        });
        document.querySelectorAll('[data-auth-form]').forEach(function (form) {
          form.classList.toggle('is-active', form.getAttribute('data-auth-form') === mode);
          setMessage(form, '');
        });
      });
    });
  }

  function initForms() {
    var loginForm = document.querySelector('[data-auth-form="login"]');
    var registerForm = document.querySelector('[data-auth-form="register"]');
    var postForms = document.querySelectorAll('[data-post-form]');

    if (loginForm) {
      loginForm.addEventListener('submit', function (event) {
        event.preventDefault();
        setMessage(loginForm, 'Đang đăng nhập...');
        request('astraland_login', loginForm)
          .then(function (data) {
            setMessage(loginForm, data.message);
            window.setTimeout(function () { window.location.reload(); }, 600);
          })
          .catch(function (error) {
            setMessage(loginForm, error.message, true);
          });
      });
    }

    if (registerForm) {
      registerForm.addEventListener('submit', function (event) {
        event.preventDefault();
        setMessage(registerForm, 'Đang tạo tài khoản...');
        request('astraland_register', registerForm)
          .then(function (data) {
            setMessage(registerForm, data.message);
            window.setTimeout(function () { window.location.reload(); }, 700);
          })
          .catch(function (error) {
            setMessage(registerForm, error.message, true);
          });
      });
    }

    postForms.forEach(function (postForm) {
      postForm.addEventListener('submit', function (event) {
        event.preventDefault();
        setMessage(postForm, 'Đang gửi tin...');
        request('astraland_submit_listing', postForm)
          .then(function (data) {
            postForm.reset();
            setMessage(postForm, data.message);
          })
        .catch(function (error) {
          setMessage(postForm, error.message, true);
        });
      });
    });
  }

  function initSubmitSteps() {
    var form = document.querySelector('[data-step-form]');
    if (!form) return;

    var step = 1;
    var steps = form.querySelectorAll('[data-step]');
    var dots = document.querySelectorAll('[data-step-dot]');
    var prev = form.querySelector('[data-step-prev]');
    var next = form.querySelector('[data-step-next]');
    var submit = form.querySelector('.is-submit');

    function updateStep(nextStep) {
      step = Math.max(1, Math.min(steps.length, nextStep));
      steps.forEach(function (section) {
        section.classList.toggle('is-active', Number(section.getAttribute('data-step')) === step);
      });
      dots.forEach(function (dot) {
        dot.classList.toggle('is-active', Number(dot.getAttribute('data-step-dot')) === step);
      });
      if (prev) prev.disabled = step === 1;
      if (next) next.style.display = step === steps.length ? 'none' : 'inline-flex';
      if (submit) submit.style.display = step === steps.length ? 'inline-flex' : 'none';
    }

    function currentStepIsValid() {
      var active = form.querySelector('[data-step="' + step + '"]');
      var fields = active ? active.querySelectorAll('input, select, textarea') : [];
      for (var index = 0; index < fields.length; index += 1) {
        if (!fields[index].checkValidity()) {
          fields[index].reportValidity();
          return false;
        }
      }
      return true;
    }

    if (prev) {
      prev.addEventListener('click', function () {
        updateStep(step - 1);
      });
    }

    if (next) {
      next.addEventListener('click', function () {
        if (currentStepIsValid()) {
          updateStep(step + 1);
        }
      });
    }

    dots.forEach(function (dot) {
      dot.addEventListener('click', function () {
        var target = Number(dot.getAttribute('data-step-dot'));
        if (target < step || currentStepIsValid()) {
          updateStep(target);
        }
      });
    });

    form.addEventListener('input', updatePreview);
    form.addEventListener('change', updatePreview);
    updateStep(1);
    updatePreview();
  }

  function updatePreview() {
    var form = document.querySelector('[data-step-form]');
    if (!form) return;

    var values = {
      title: form.querySelector('[data-preview-title]'),
      price: form.querySelector('[data-preview-price]'),
      area: form.querySelector('[data-preview-area]'),
      location: form.querySelector('[data-preview-location]')
    };

    Object.keys(values).forEach(function (key) {
      var output = form.querySelector('[data-preview-output="' + key + '"]');
      if (!output) return;
      var fallback = {
        title: 'Tiêu đề tin của bạn',
        price: 'Giá bán',
        area: 'Diện tích',
        location: 'Khu vực'
      };
      output.textContent = values[key] && values[key].value ? values[key].value : fallback[key];
    });
  }

  function formatNumber(value) {
    return new Intl.NumberFormat('vi-VN').format(Number(value || 0));
  }

  function initStats() {
    var data = window.AstraLandData && window.AstraLandData.stats;
    if (!data) return;

    var date = document.querySelector('[data-stat-date]');
    var active = document.querySelector('[data-stat-active]');
    var today = document.querySelector('[data-stat-today]');
    var canvas = document.getElementById('alMarketChart');

    if (date) date.textContent = data.date;
    if (active) active.textContent = formatNumber(data.active);
    if (today) today.textContent = formatNumber(data.today);

    if (!canvas || !window.Chart) return;

    new window.Chart(canvas, {
      type: 'bar',
      data: {
        labels: data.labels,
        datasets: [{
          label: 'Tin đăng',
          data: data.values,
          borderRadius: 8,
          backgroundColor: ['#0a8f5a', '#16bd78', '#39c98a', '#85d9b2', '#d7a01f'],
          maxBarThickness: 38
        }]
      },
      options: {
        animation: {
          duration: 900,
          easing: 'easeOutQuart'
        },
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: function (context) {
                return formatNumber(context.raw) + ' tin';
              }
            }
          }
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: { color: '#64726d', font: { weight: 700 } }
          },
          y: {
            beginAtZero: true,
            ticks: {
              color: '#64726d',
              callback: function (value) { return formatNumber(value); }
            },
            grid: { color: '#e4ebe7' }
          }
        }
      }
    });
  }

  function initRevealAnimation() {
    var targets = document.querySelectorAll('.al-market, .al-ticker, .al-featured, .al-news, .al-agents, .al-ecosystem, .al-card, .al-agent, .al-tools article, .al-result-card, .al-filter-panel, .al-submit-form, .al-submit-guide');

    targets.forEach(function (target) {
      target.classList.add('al-reveal');
    });

    if (!('IntersectionObserver' in window)) {
      targets.forEach(function (target) {
        target.classList.add('is-visible');
      });
      return;
    }

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.14 });

    targets.forEach(function (target) {
      observer.observe(target);
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initIcons();
    initHeader();
    initTabs();
    initModals();
    initForms();
    initSubmitSteps();
    initStats();
    initRevealAnimation();
  });
})();
