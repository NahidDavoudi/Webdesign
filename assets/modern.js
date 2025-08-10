// Modern JavaScript for Famo Academy
// Enhanced user experience with smooth animations and interactions

class FamoModern {
  constructor() {
    this.init();
  }

  init() {
    this.setupScrollEffects();
    this.setupMobileMenu();
    this.setupAnimationObserver();
    this.setupCounterAnimation();
    this.setupSmoothScroll();
    this.setupHeaderScroll();
    this.setupParticleEffect();
    this.setupFormEnhancements();
  }

  // Smooth scrolling for navigation links
  setupSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', (e) => {
        e.preventDefault();
        const target = document.querySelector(anchor.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });
  }

  // Header scroll effects
  setupHeaderScroll() {
    const header = document.querySelector('header');
    let lastScroll = 0;

    window.addEventListener('scroll', () => {
      const currentScroll = window.pageYOffset;
      
      if (currentScroll > 100) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }

      // Hide header on scroll down, show on scroll up
      if (currentScroll > lastScroll && currentScroll > 200) {
        header.style.transform = 'translateY(-100%)';
      } else {
        header.style.transform = 'translateY(0)';
      }
      
      lastScroll = currentScroll;
    });
  }

  // Mobile menu functionality
  setupMobileMenu() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mainMenu = document.getElementById('mainMenu');
    
    if (mobileMenuBtn && mainMenu) {
      mobileMenuBtn.addEventListener('click', () => {
        mainMenu.classList.toggle('show');
        const icon = mobileMenuBtn.querySelector('i');
        icon.classList.toggle('fa-bars');
        icon.classList.toggle('fa-times');
      });

      // Close menu when clicking on a link
      mainMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
          mainMenu.classList.remove('show');
          const icon = mobileMenuBtn.querySelector('i');
          icon.classList.add('fa-bars');
          icon.classList.remove('fa-times');
        });
      });
    }
  }

  // Intersection Observer for scroll animations
  setupAnimationObserver() {
    const options = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          
          // Add staggered animation for grid items
          if (entry.target.classList.contains('grid')) {
            const items = entry.target.querySelectorAll('.card, .course-card, .instructor-card');
            items.forEach((item, index) => {
              setTimeout(() => {
                item.style.animation = `fadeUp 0.6s ease forwards ${index * 0.1}s`;
              }, index * 100);
            });
          }
        }
      });
    }, options);

    // Observe all elements with reveal class
    document.querySelectorAll('.reveal').forEach(el => {
      observer.observe(el);
    });
  }

  // Animated counters for stats
  setupCounterAnimation() {
    const counters = {
      'years-counter': { target: 15, suffix: '' },
      'satisfaction-counter': { target: 98, suffix: '%' },
      'students-counter': { target: 2500, suffix: '+' },
      'teachers-counter': { target: 25, suffix: '+' }
    };

    const animateCounter = (element, target, suffix) => {
      let current = 0;
      const increment = target / 100;
      const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
          current = target;
          clearInterval(timer);
        }
        element.textContent = Math.floor(current) + suffix;
      }, 20);
    };

    // Start counters when stats section is visible
    const statsSection = document.querySelector('.stats');
    if (statsSection) {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            Object.keys(counters).forEach(id => {
              const element = document.getElementById(id);
              if (element) {
                animateCounter(element, counters[id].target, counters[id].suffix);
              }
            });
            observer.unobserve(entry.target);
          }
        });
      });
      observer.observe(statsSection);
    }
  }

  // Scroll-based effects
  setupScrollEffects() {
    window.addEventListener('scroll', () => {
      const scrolled = window.pageYOffset;
      const parallax = document.querySelector('.hero');
      
      if (parallax) {
        parallax.style.transform = `translateY(${scrolled * 0.5}px)`;
      }

      // Floating animation for cards
      document.querySelectorAll('.floating').forEach(element => {
        const speed = element.dataset.speed || 0.5;
        element.style.transform = `translateY(${scrolled * speed}px)`;
      });
    });
  }

  // Particle effect for hero section
  setupParticleEffect() {
    const hero = document.querySelector('.hero');
    if (!hero) return;

    const canvas = document.createElement('canvas');
    canvas.style.position = 'absolute';
    canvas.style.top = '0';
    canvas.style.left = '0';
    canvas.style.width = '100%';
    canvas.style.height = '100%';
    canvas.style.pointerEvents = 'none';
    canvas.style.zIndex = '1';
    hero.appendChild(canvas);

    const ctx = canvas.getContext('2d');
    const particles = [];

    const resizeCanvas = () => {
      canvas.width = hero.offsetWidth;
      canvas.height = hero.offsetHeight;
    };

    class Particle {
      constructor() {
        this.x = Math.random() * canvas.width;
        this.y = Math.random() * canvas.height;
        this.vx = (Math.random() - 0.5) * 0.5;
        this.vy = (Math.random() - 0.5) * 0.5;
        this.radius = Math.random() * 2 + 1;
        this.opacity = Math.random() * 0.5 + 0.2;
      }

      update() {
        this.x += this.vx;
        this.y += this.vy;

        if (this.x < 0 || this.x > canvas.width) this.vx *= -1;
        if (this.y < 0 || this.y > canvas.height) this.vy *= -1;
      }

      draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(255, 255, 255, ${this.opacity})`;
        ctx.fill();
      }
    }

    const initParticles = () => {
      particles.length = 0;
      const particleCount = Math.floor((canvas.width * canvas.height) / 15000);
      for (let i = 0; i < particleCount; i++) {
        particles.push(new Particle());
      }
    };

    const animate = () => {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      
      particles.forEach(particle => {
        particle.update();
        particle.draw();
      });

      // Draw connections
      particles.forEach((particle, i) => {
        particles.slice(i + 1).forEach(other => {
          const dx = particle.x - other.x;
          const dy = particle.y - other.y;
          const distance = Math.sqrt(dx * dx + dy * dy);

          if (distance < 100) {
            ctx.beginPath();
            ctx.moveTo(particle.x, particle.y);
            ctx.lineTo(other.x, other.y);
            ctx.strokeStyle = `rgba(255, 255, 255, ${0.1 * (1 - distance / 100)})`;
            ctx.lineWidth = 0.5;
            ctx.stroke();
          }
        });
      });

      requestAnimationFrame(animate);
    };

    resizeCanvas();
    initParticles();
    animate();

    window.addEventListener('resize', () => {
      resizeCanvas();
      initParticles();
    });
  }

  // Enhanced form interactions
  setupFormEnhancements() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
      const inputs = form.querySelectorAll('input, textarea, select');
      
      inputs.forEach(input => {
        // Floating label effect
        const createFloatingLabel = () => {
          const wrapper = document.createElement('div');
          wrapper.className = 'floating-input';
          input.parentNode.insertBefore(wrapper, input);
          wrapper.appendChild(input);
          
          if (input.placeholder) {
            const label = document.createElement('label');
            label.textContent = input.placeholder;
            label.className = 'floating-label';
            wrapper.appendChild(label);
            input.placeholder = '';
          }
        };

        // Add focus/blur effects
        input.addEventListener('focus', () => {
          input.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', () => {
          if (!input.value) {
            input.parentElement.classList.remove('focused');
          }
        });

        // Real-time validation feedback
        input.addEventListener('input', () => {
          this.validateField(input);
        });
      });

      // Enhanced form submission
      form.addEventListener('submit', (e) => {
        this.handleFormSubmit(e, form);
      });
    });
  }

  validateField(field) {
    const value = field.value.trim();
    const type = field.type;
    const required = field.hasAttribute('required');
    
    let isValid = true;
    let message = '';

    if (required && !value) {
      isValid = false;
      message = 'این فیلد الزامی است';
    } else if (type === 'tel' && value) {
      const phoneRegex = /^09\d{9}$/;
      if (!phoneRegex.test(value)) {
        isValid = false;
        message = 'شماره موبایل معتبر نیست';
      }
    } else if (type === 'email' && value) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(value)) {
        isValid = false;
        message = 'ایمیل معتبر نیست';
      }
    }

    // Remove existing validation messages
    const existingMessage = field.parentElement.querySelector('.validation-message');
    if (existingMessage) {
      existingMessage.remove();
    }

    // Add validation styling
    field.classList.toggle('invalid', !isValid);
    field.classList.toggle('valid', isValid && value);

    // Add validation message
    if (!isValid && message) {
      const messageElement = document.createElement('div');
      messageElement.className = 'validation-message';
      messageElement.textContent = message;
      field.parentElement.appendChild(messageElement);
    }

    return isValid;
  }

  handleFormSubmit(e, form) {
    e.preventDefault();
    
    // Validate all fields
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    let isFormValid = true;
    
    inputs.forEach(input => {
      if (!this.validateField(input)) {
        isFormValid = false;
      }
    });

    if (isFormValid) {
      // Add loading state
      const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
      if (submitBtn) {
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        
        // Simulate form submission
        setTimeout(() => {
          submitBtn.classList.remove('loading');
          submitBtn.disabled = false;
          this.showNotification('فرم با موفقیت ارسال شد!', 'success');
        }, 2000);
      }
    } else {
      this.showNotification('لطفاً تمام فیلدهای الزامی را پر کنید', 'error');
    }
  }

  // Notification system
  showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
      <div class="notification-content">
        <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
        <span>${message}</span>
      </div>
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => notification.classList.add('show'), 100);

    // Remove after delay
    setTimeout(() => {
      notification.classList.remove('show');
      setTimeout(() => notification.remove(), 300);
    }, 5000);
  }

  // Utility method for smooth element reveal
  revealElement(element, delay = 0) {
    setTimeout(() => {
      element.classList.add('visible');
    }, delay);
  }

  // Mouse parallax effect
  setupMouseParallax() {
    const cards = document.querySelectorAll('.card');
    
    cards.forEach(card => {
      card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        
        const rotateX = (y - centerY) / 10;
        const rotateY = (centerX - x) / 10;
        
        card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(10px)`;
      });
      
      card.addEventListener('mouseleave', () => {
        card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateZ(0)';
      });
    });
  }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  new FamoModern();
});

// Add CSS for new features
const modernStyles = `
  .floating-input {
    position: relative;
    margin-bottom: 1.5rem;
  }

  .floating-label {
    position: absolute;
    top: 1rem;
    right: 1rem;
    color: var(--text-secondary);
    transition: var(--transition-fast);
    pointer-events: none;
    background: var(--bg-glass);
    padding: 0 0.5rem;
  }

  .floating-input.focused .floating-label,
  .floating-input input:not(:placeholder-shown) + .floating-label {
    top: -0.5rem;
    font-size: 0.875rem;
    color: var(--primary);
  }

  .validation-message {
    color: var(--danger);
    font-size: 0.875rem;
    margin-top: 0.25rem;
  }

  input.invalid {
    border-color: var(--danger);
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
  }

  input.valid {
    border-color: var(--success);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
  }

  .btn.loading {
    position: relative;
    color: transparent;
  }

  .btn.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    transform: translate(-50%, -50%);
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    to { transform: translate(-50%, -50%) rotate(360deg); }
  }

  .notification {
    position: fixed;
    top: 2rem;
    right: 2rem;
    background: var(--bg-glass);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--radius-md);
    padding: 1rem;
    transform: translateX(100%);
    transition: var(--transition-normal);
    z-index: 10000;
    max-width: 400px;
  }

  .notification.show {
    transform: translateX(0);
  }

  .notification.success {
    border-color: var(--success);
    color: var(--success);
  }

  .notification.error {
    border-color: var(--danger);
    color: var(--danger);
  }

  .notification-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .notification-content i {
    font-size: 1.25rem;
  }
`;

// Inject styles
const styleSheet = document.createElement('style');
styleSheet.textContent = modernStyles;
document.head.appendChild(styleSheet);