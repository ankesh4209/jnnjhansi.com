/**
 * Jhansi Nagar Nigam - Portal JavaScript UI Interactions
 */
document.addEventListener('DOMContentLoaded', function () {
  // 1. Mobile Menu Toggle
  var mobileToggle = document.getElementById('mobileMenuToggle');
  var navMenu = document.getElementById('navMenuList');

  if (mobileToggle && navMenu) {
    mobileToggle.addEventListener('click', function (e) {
      e.stopPropagation();
      navMenu.classList.toggle('show-mobile');
    });

    // Mobile dropdown toggle
    var dropdownItems = navMenu.querySelectorAll('.nav-item.has-dropdown');
    dropdownItems.forEach(function (item) {
      var link = item.querySelector('.nav-link');
      if (link) {
        link.addEventListener('click', function (e) {
          if (window.innerWidth <= 900) {
            e.preventDefault();
            item.classList.toggle('open-mobile');
          }
        });
      }
    });

    // Close when clicking outside
    document.addEventListener('click', function (e) {
      if (!navMenu.contains(e.target) && !mobileToggle.contains(e.target)) {
        navMenu.classList.remove('show-mobile');
      }
    });
  }

  // 2. Active Navigation Link Detection
  var currentPath = window.location.pathname.toLowerCase();
  var pageFile = currentPath.substring(currentPath.lastIndexOf('/') + 1) || 'index.php';

  var navLinks = document.querySelectorAll('.nav-link');
  navLinks.forEach(function (link) {
    var href = link.getAttribute('href');
    if (href) {
      var linkFile = href.substring(href.lastIndexOf('/') + 1);
      if (linkFile === pageFile || (pageFile === '' && linkFile === 'index.php')) {
        link.classList.add('active');
      }
    }
  });

  // 3. Accessibility Font Resizing
  var fontDecrease = document.getElementById('fontDecrease');
  var fontNormal = document.getElementById('fontNormal');
  var fontIncrease = document.getElementById('fontIncrease');
  var body = document.body;

  if (fontDecrease && fontNormal && fontIncrease) {
    fontDecrease.addEventListener('click', function () {
      body.classList.remove('font-large', 'font-xlarge');
      body.classList.add('font-small');
      setActiveFontBtn(fontDecrease);
    });

    fontNormal.addEventListener('click', function () {
      body.classList.remove('font-small', 'font-large', 'font-xlarge');
      setActiveFontBtn(fontNormal);
    });

    fontIncrease.addEventListener('click', function () {
      if (body.classList.contains('font-large')) {
        body.classList.remove('font-large');
        body.classList.add('font-xlarge');
      } else {
        body.classList.remove('font-small');
        body.classList.add('font-large');
      }
      setActiveFontBtn(fontIncrease);
    });
  }

  function setActiveFontBtn(activeBtn) {
    [fontDecrease, fontNormal, fontIncrease].forEach(function (btn) {
      if (btn) btn.classList.remove('active');
    });
    if (activeBtn) activeBtn.classList.add('active');
  }

  // 4. High Contrast / Theme Toggle
  var accessibilityToggle = document.getElementById('accessibilityToggle');
  if (accessibilityToggle) {
    accessibilityToggle.addEventListener('click', function () {
      body.classList.toggle('high-contrast');
    });
  }

  // 5. Global Search Bar functionality
  var searchForms = document.querySelectorAll('.hero-search-wrapper, .portal-search-box');
  searchForms.forEach(function (form) {
    var input = form.querySelector('input');
    var btn = form.querySelector('button');
    if (input && btn) {
      btn.addEventListener('click', function (e) {
        var query = input.value.trim();
        if (query) {
          window.location.href = 'notices.php?q=' + encodeURIComponent(query);
        } else {
          input.focus();
        }
      });
      input.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          var query = input.value.trim();
          if (query) {
            window.location.href = 'notices.php?q=' + encodeURIComponent(query);
          }
        }
      });
    }
  });

  // 6. Interactive Tab Filter for Tenders and Services
  var filterButtons = document.querySelectorAll('.filter-tab-btn');
  filterButtons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var target = this.getAttribute('data-filter');
      var container = this.closest('.filterable-section') || document;
      
      // Update active button
      this.parentElement.querySelectorAll('.filter-tab-btn').forEach(function (b) {
        b.classList.remove('active');
      });
      this.classList.add('active');

      // Filter items
      var items = container.querySelectorAll('.filterable-item');
      items.forEach(function (item) {
        if (!target || target === 'all' || item.classList.contains(target) || item.getAttribute('data-category') === target) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
    });
  });

  // 7. Auto Breadcrumbs & Banner Title update for inner pages
  var bannerTitleElem = document.getElementById('innerBannerTitle');
  var breadcrumbElem = document.getElementById('innerBreadcrumbActive');
  
  if (bannerTitleElem && breadcrumbElem) {
    // Map known file paths to titles
    var titleMap = {
      'aboutus.php': 'About Jhansi Nagar Nigam',
      'services.php': 'Our Services',
      'tenders.php': 'e-Tender Notifications',
      'contactus.php': 'Contact Us',
      'notices.php': 'Public Notices & Announcements',
      'departments.php': 'Municipal Departments',
      'administration.php': 'Administrative Directory',
      'downloads.php': 'Forms & Downloads',
      'gallery.php': 'Photo Gallery',
      'jhansi_history.php': 'History of Jhansi City',
      'citizen_charter.php': 'Citizen Charter',
      'stastistics.php': 'City Statistics',
      'taxes.php': 'Property & Civic Taxes',
      'nagar_vikash.php': 'Nagar Vikas Vibhag, U.P.',
      'house_resolution.php': 'House Resolutions',
      'sbm.php': 'Swachh Bharat Mission',
      'smart_city.php': 'Smart City Jhansi',
      'finance_docs.php': 'Finance & Budget Documents',
      'feedback.php': 'Citizen Feedback'
    };

    if (titleMap[pageFile]) {
      var detectedTitle = titleMap[pageFile];
      if (!bannerTitleElem.textContent || bannerTitleElem.textContent.trim() === '__pagetitle__' || bannerTitleElem.textContent.trim() === '') {
        bannerTitleElem.textContent = detectedTitle;
      }
      if (!breadcrumbElem.textContent || breadcrumbElem.textContent.trim() === '__pagetitle__' || breadcrumbElem.textContent.trim() === '') {
        breadcrumbElem.textContent = detectedTitle;
      }
    }
  }
});
