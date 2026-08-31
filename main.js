/**
 * DIGITRADE ACADEMY - MAIN JAVASCRIPT
 * Features: Multi-Page Navigation, Official WhatsApp Dispatcher (+92 332 7292282),
 * Animated Counters, FAQ Accordions, Course URL Auto-Selection, and Floating WhatsApp.
 */

// ==========================================
// CONFIGURATION (Official Academy Mentor)
// ==========================================
const ACADEMY_CONFIG = {
  mentor: {
    name: 'Muhammad Safiullah',
    role: 'Forex & Trading Mentor',
    number: '923327292282',
    display: '+92 332 7292282'
  },
  academyName: 'DigiTrade Academy',
  tagline: 'LEARN • TRADE • EARN',
  facebookUrl: 'https://www.facebook.com/digitradeacademy',
  instagramUrl: 'https://www.instagram.com/digitradeacademy'
};

document.addEventListener('DOMContentLoaded', () => {
  initNavbar();
  initCounters();
  initFaqAccordion();
  initAdmissionForm();
  initQuickEnrollButtons();
  initFloatingWhatsApp();
  initTickerDuplicate();
  checkUrlParamsForCourse();
  highlightActivePageNav();
});

/* --------------------------------------------------
   1. NAVBAR & MOBILE NAVIGATION
   -------------------------------------------------- */
function initNavbar() {
  const navbar = document.querySelector('.navbar');
  const navToggle = document.getElementById('navToggle');
  const navMenu = document.getElementById('navMenu');
  const navLinks = document.querySelectorAll('.nav-link');

  // Sticky Navbar Effect on Scroll
  window.addEventListener('scroll', () => {
    if (window.scrollY > 40) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });

  // Mobile Menu Toggle
  if (navToggle && navMenu) {
    navToggle.addEventListener('click', () => {
      navMenu.classList.toggle('active');
      const icon = navToggle.querySelector('i');
      if (navMenu.classList.contains('active')) {
        icon.classList.remove('fa-bars');
        icon.classList.add('fa-times');
      } else {
        icon.classList.remove('fa-times');
        icon.classList.add('fa-bars');
      }
    });

    // Close mobile menu when clicking a link
    navLinks.forEach(link => {
      link.addEventListener('click', () => {
        navMenu.classList.remove('active');
        const icon = navToggle.querySelector('i');
        if (icon) {
          icon.classList.remove('fa-times');
          icon.classList.add('fa-bars');
        }
      });
    });
  }
}

// Highlight current page in navbar
function highlightActivePageNav() {
  const currentPath = window.location.pathname.split('/').pop() || 'index.html';
  const navLinks = document.querySelectorAll('.nav-link');
  
  navLinks.forEach(link => {
    const href = link.getAttribute('href');
    if (href === currentPath || (currentPath === '' && href === 'index.html')) {
      link.classList.add('active');
    } else if (!href.startsWith('#') && href !== currentPath) {
      link.classList.remove('active');
    }
  });
}

/* --------------------------------------------------
   2. KEY STATS ANIMATED COUNTERS
   -------------------------------------------------- */
function initCounters() {
  const counterElements = document.querySelectorAll('.stat-number');
  if (!counterElements.length) return;

  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const target = entry.target;
        const targetNum = parseInt(target.getAttribute('data-target'), 10);
        const prefix = target.getAttribute('data-prefix') || '';
        const suffix = target.getAttribute('data-suffix') || '';
        animateValue(target, 0, targetNum, 1800, prefix, suffix);
        obs.unobserve(target);
      }
    });
  }, { threshold: 0.3 });

  counterElements.forEach(el => observer.observe(el));
}

function animateValue(obj, start, end, duration, prefix = '', suffix = '') {
  let startTimestamp = null;
  const step = (timestamp) => {
    if (!startTimestamp) startTimestamp = timestamp;
    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
    const easeProgress = easeOutQuad(progress);
    const currentVal = Math.floor(easeProgress * (end - start) + start);
    obj.innerHTML = `${prefix}${currentVal.toLocaleString()}${suffix}`;
    if (progress < 1) {
      window.requestAnimationFrame(step);
    } else {
      obj.innerHTML = `${prefix}${end.toLocaleString()}${suffix}`;
    }
  };
  window.requestAnimationFrame(step);
}

function easeOutQuad(x) {
  return 1 - (1 - x) * (1 - x);
}

/* --------------------------------------------------
   3. FAQ ACCORDION
   -------------------------------------------------- */
function initFaqAccordion() {
  const faqItems = document.querySelectorAll('.faq-item');
  if (!faqItems.length) return;

  faqItems.forEach(item => {
    const header = item.querySelector('.faq-header');
    const body = item.querySelector('.faq-body');
    if (!header || !body) return;

    header.addEventListener('click', () => {
      const isActive = item.classList.contains('active');

      // Close all other accordions
      faqItems.forEach(otherItem => {
        if (otherItem !== item) {
          otherItem.classList.remove('active');
          const otherBody = otherItem.querySelector('.faq-body');
          if (otherBody) otherBody.style.maxHeight = null;
        }
      });

      // Toggle current
      if (!isActive) {
        item.classList.add('active');
        body.style.maxHeight = body.scrollHeight + 'px';
      } else {
        item.classList.remove('active');
        body.style.maxHeight = null;
      }
    });
  });
}

/* --------------------------------------------------
   4. COURSE ENROLLMENT SHORTCUTS & PRE-SELECTION
   -------------------------------------------------- */
function initQuickEnrollButtons() {
  const enrollBtns = document.querySelectorAll('.btn-enroll-course');
  const courseSelect = document.getElementById('selectedCourse');
  const admissionSection = document.getElementById('admission');

  enrollBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
      const courseName = btn.getAttribute('data-course');
      if (courseSelect && courseName) {
        courseSelect.value = courseName;
        if (admissionSection) {
          admissionSection.scrollIntoView({ behavior: 'smooth' });
        }
      } else if (courseName) {
        window.location.href = `admission.html?course=${encodeURIComponent(courseName)}`;
      }
    });
  });
}

function checkUrlParamsForCourse() {
  const urlParams = new URLSearchParams(window.location.search);
  const courseParam = urlParams.get('course');
  const courseSelect = document.getElementById('selectedCourse');
  if (courseParam && courseSelect) {
    courseSelect.value = courseParam;
  }
}

/* --------------------------------------------------
   5. ADMISSION FORM & DIRECT WHATSAPP + DB DISPATCHER
   -------------------------------------------------- */
function initAdmissionForm() {
  const form = document.getElementById('admissionForm');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const fullName = document.getElementById('fullName').value.trim();
    const whatsappNum = document.getElementById('whatsappNumber').value.trim();
    const email = document.getElementById('email') ? document.getElementById('email').value.trim() : '';
    const city = document.getElementById('city') ? document.getElementById('city').value.trim() : '';
    const selectedCourse = document.getElementById('selectedCourse').value;
    const experienceLevel = document.getElementById('experienceLevel') ? document.getElementById('experienceLevel').value : 'Beginner';
    const message = document.getElementById('userMessage') ? document.getElementById('userMessage').value.trim() : '';

    const mentorObj = ACADEMY_CONFIG.mentor;
    const mentorNumber = mentorObj.number;

    if (!fullName || !whatsappNum || !selectedCourse) {
      showToast('⚠️ Please fill in all required fields.', '#EF4444');
      return;
    }

    // Build structured inquiry text for WhatsApp
    const inquiryText =
      `🎓 *NEW ADMISSION INQUIRY - DIGITRADE ACADEMY*\n` +
      `----------------------------------------\n` +
      `👨‍🏫 *Assigned Mentor:* ${mentorObj.name} (${mentorObj.role})\n` +
      `👤 *Student Name:* ${fullName}\n` +
      `📱 *Student WhatsApp:* ${whatsappNum}\n` +
      `📧 *Email:* ${email || 'Not Provided'}\n` +
      `📍 *City:* ${city || 'Not Provided'}\n` +
      `📚 *Selected Course:* ${selectedCourse}\n` +
      `📈 *Experience Level:* ${experienceLevel || 'Beginner'}\n` +
      (message ? `💬 *Message:* ${message}\n` : '') +
      `----------------------------------------\n` +
      `_Sent from DigiTrade Academy Online Admission Portal_`;

    const encodedText = encodeURIComponent(inquiryText);
    const targetUrl = `https://wa.me/${mentorNumber}?text=${encodedText}`;

    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving Application...';
    }

    showToast(`🚀 Saving lead to database...`, '#10B981');

    // 1. Submit to MySQL Database API
    try {
      const response = await fetch('api/submit-admission.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          fullName,
          whatsappNumber: whatsappNum,
          email,
          city,
          selectedCourse,
          experienceLevel,
          mentorChoice: mentorObj.name,
          userMessage: message
        })
      });
      const data = await response.json();
      if (data && data.success) {
        showToast(`✅ Saved to Database! Connecting WhatsApp...`, '#10B981');
      } else {
        console.warn('API returned response:', data);
      }
    } catch (apiErr) {
      console.warn('API submission error:', apiErr);
      if (window.location.protocol === 'file:') {
        alert('⚠️ Note: You opened this file directly from folders (file://). To save in MySQL database, please open through: http://localhost/digitrade/admission.html');
      }
    }

    if (submitBtn) {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalBtnHtml;
    }

    // 2. Open WhatsApp in new tab for direct mentor communication
    window.open(targetUrl, '_blank');
    form.reset();
  });
}

/* --------------------------------------------------
   6. FLOATING WHATSAPP BUTTON CLICK
   -------------------------------------------------- */
function initFloatingWhatsApp() {
  const floatBtn = document.getElementById('floatingWhatsApp');
  if (!floatBtn) return;

  floatBtn.addEventListener('click', (e) => {
    e.preventDefault();
    const greeting = encodeURIComponent(`Hello DigiTrade Academy! I want to inquire about upcoming Forex Trading & Meta Ads courses with Muhammad Safiullah.`);
    window.open(`https://wa.me/${ACADEMY_CONFIG.mentor.number}?text=${greeting}`, '_blank');
  });
}

/* --------------------------------------------------
   7. TICKER SEAMLESS LOOP DUPLICATION
   -------------------------------------------------- */
function initTickerDuplicate() {
  const tickerTrack = document.getElementById('tickerTrack');
  if (!tickerTrack) return;
  // Duplicate ticker content to guarantee an infinite seamless marquee
  const clone = tickerTrack.innerHTML;
  tickerTrack.innerHTML += clone;
}

/* --------------------------------------------------
   8. TOAST NOTIFICATION UTILITY
   -------------------------------------------------- */
function showToast(message, color = '#D4AF37') {
  let toast = document.getElementById('toastNotice');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'toastNotice';
    toast.className = 'toast-notice';
    document.body.appendChild(toast);
  }

  toast.innerHTML = `
    <i class="fas fa-check-circle toast-icon" style="color:${color}"></i>
    <span>${message}</span>
  `;
  toast.classList.add('show');

  setTimeout(() => {
    toast.classList.remove('show');
  }, 4000);
}