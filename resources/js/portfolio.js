/**
 * Portfolio Animations & Interactions
 * Scroll-based animations and hover effects for project cards
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all animations
    initNavigation();
    initScrollAnimations();
    initCardEffects();
    initParallaxBackground();
    initSmoothReveal();
    initSkillBars();
    initMagneticButtons();
    initHeroEffects();
    initParticles();
});

/**
 * Navigation - hide on scroll down, show on scroll up
 */
function initNavigation() {
    const navbar = document.getElementById('navbar');
    if (!navbar) return;

    let lastScrollY = window.scrollY;
    let ticking = false;

    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                const currentScrollY = window.scrollY;

                // Always show at top
                if (currentScrollY < 50) {
                    navbar.classList.remove('nav-hidden');
                }
                // Hide when scrolling down, show when scrolling up
                else if (currentScrollY > lastScrollY) {
                    // Scrolling down
                    navbar.classList.add('nav-hidden');
                } else {
                    // Scrolling up
                    navbar.classList.remove('nav-hidden');
                }

                lastScrollY = currentScrollY;
                ticking = false;
            });
            ticking = true;
        }
    });

    // Update active nav link based on scroll position
    const sections = document.querySelectorAll('div[id]');
    const navLinks = document.querySelectorAll('.nav-link');

    const observerOptions = {
        root: null,
        rootMargin: '-20% 0px -80% 0px',
        threshold: 0
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute('id');
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${id}`) {
                        link.classList.add('active');
                    }
                });
            }
        });
    }, observerOptions);

    sections.forEach(section => observer.observe(section));

    // Smooth scroll for nav links
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = link.getAttribute('href');
            const target = document.querySelector(targetId);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

/**
 * Scroll-triggered animations for cards
 */
function initScrollAnimations() {
    const cards = document.querySelectorAll('.project-card');

    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: [0.1, 0.3, 0.5, 0.7, 1]
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const card = entry.target;
            const ratio = entry.intersectionRatio;

            // Scale and opacity based on visibility
            if (entry.isIntersecting) {
                card.style.opacity = Math.min(0.5 + ratio, 1);
                card.style.transform = `scale(${0.95 + (ratio * 0.05)})`;
                card.classList.add('is-visible');
            }
        });
    }, observerOptions);

    cards.forEach(card => observer.observe(card));
}

/**
 * Interactive card effects (tilt, glow)
 */
function initCardEffects() {
    const cards = document.querySelectorAll('.project-card');

    cards.forEach(card => {
        // Mouse move tilt effect
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = (y - centerY) / 20;
            const rotateY = (centerX - x) / 20;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;

            // Dynamic glow effect following cursor
            const glowX = (x / rect.width) * 100;
            const glowY = (y / rect.height) * 100;
            card.style.setProperty('--glow-x', `${glowX}%`);
            card.style.setProperty('--glow-y', `${glowY}%`);
        });

        // Reset on mouse leave
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
        });

        // Click ripple effect
        card.addEventListener('click', (e) => {
            createRipple(card, e);
        });
    });

    // Add tilt effect to glass cards too
    const glassCards = document.querySelectorAll('.glass-card:not(.project-card)');
    glassCards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = (y - centerY) / 40;
            const rotateY = (centerX - x) / 40;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-5px)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });
}

/**
 * Create ripple effect
 */
function createRipple(element, e) {
    const ripple = document.createElement('span');
    ripple.classList.add('ripple');

    const rect = element.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);

    ripple.style.width = ripple.style.height = `${size}px`;
    ripple.style.left = `${e.clientX - rect.left - size / 2}px`;
    ripple.style.top = `${e.clientY - rect.top - size / 2}px`;

    element.appendChild(ripple);

    setTimeout(() => ripple.remove(), 600);
}

/**
 * Parallax effect for background circles
 */
function initParallaxBackground() {
    const circles = document.querySelectorAll('.blob-circle');

    let ticking = false;

    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                const scrollY = window.scrollY;
                circles.forEach((circle, index) => {
                    const speed = (index + 1) * 0.05;
                    const yPos = scrollY * speed;
                    circle.style.transform = `translateY(${yPos}px)`;
                });
                ticking = false;
            });
            ticking = true;
        }
    });

    // Mouse parallax for circles
    document.addEventListener('mousemove', (e) => {
        const mouseX = e.clientX / window.innerWidth - 0.5;
        const mouseY = e.clientY / window.innerHeight - 0.5;

        circles.forEach((circle, index) => {
            const speed = (index + 1) * 15;
            circle.style.marginLeft = `${mouseX * speed}px`;
            circle.style.marginTop = `${mouseY * speed}px`;
        });
    });
}

/**
 * Smooth reveal for sections
 */
function initSmoothReveal() {
    const sections = document.querySelectorAll('.reveal-section');

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');

                // Stagger child animations
                const children = entry.target.querySelectorAll('.reveal-child');
                children.forEach((child, index) => {
                    setTimeout(() => {
                        child.classList.add('child-revealed');
                    }, index * 150);
                });
            }
        });
    }, {
        threshold: 0.15,
        rootMargin: '0px 0px -50px 0px'
    });

    sections.forEach(section => revealObserver.observe(section));
}

/**
 * Skill bars animation
 */
function initSkillBars() {
    const skillBars = document.querySelectorAll('.skill-progress');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const bar = entry.target;
                const targetWidth = bar.dataset.width || '80%';
                bar.style.setProperty('--target-width', targetWidth);

                setTimeout(() => {
                    bar.style.width = targetWidth;
                    bar.style.transition = 'width 1s ease-out';
                }, 200);
            }
        });
    }, { threshold: 0.5 });

    skillBars.forEach(bar => observer.observe(bar));
}

/**
 * Magnetic button effect
 */
function initMagneticButtons() {
    const buttons = document.querySelectorAll('.contact-btn, .pulse-cta');

    buttons.forEach(btn => {
        btn.addEventListener('mousemove', (e) => {
            const rect = btn.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;

            btn.style.transform = `translate(${x * 0.2}px, ${y * 0.2}px) scale(1.05)`;
        });

        btn.addEventListener('mouseleave', () => {
            btn.style.transform = '';
        });

        btn.addEventListener('click', (e) => {
            createRipple(btn, e);
        });
    });
}

/**
 * Hero section special effects
 */
function initHeroEffects() {
    const heroAvatar = document.querySelector('.hero-avatar');
    const heroTitle = document.querySelector('.hero-title');

    if (heroAvatar) {
        // Breathing glow effect on avatar
        heroAvatar.addEventListener('mouseenter', () => {
            heroAvatar.style.animation = 'none';
            heroAvatar.style.boxShadow = '0 0 80px rgba(139, 92, 246, 0.8)';
        });

        heroAvatar.addEventListener('mouseleave', () => {
            heroAvatar.style.animation = '';
            heroAvatar.style.boxShadow = '';
        });
    }

    // Animate info badges on load
    const badges = document.querySelectorAll('.info-badge');
    badges.forEach((badge, index) => {
        badge.style.opacity = '0';
        badge.style.transform = 'translateY(20px)';

        setTimeout(() => {
            badge.style.transition = 'all 0.5s ease';
            badge.style.opacity = '1';
            badge.style.transform = 'translateY(0)';
        }, 1000 + (index * 150));
    });
}

/**
 * Create floating particles
 */
function initParticles() {
    const particleContainer = document.body;
    const particleCount = 20;

    for (let i = 0; i < particleCount; i++) {
        createParticle(particleContainer, i);
    }
}

function createParticle(container, index) {
    const particle = document.createElement('div');
    particle.classList.add('particle');

    // Random position and animation
    const startX = Math.random() * 100;
    const size = Math.random() * 8 + 4;
    const duration = Math.random() * 10 + 10;
    const delay = Math.random() * 15;

    particle.style.cssText = `
        left: ${startX}vw;
        bottom: -20px;
        width: ${size}px;
        height: ${size}px;
        animation-duration: ${duration}s;
        animation-delay: ${delay}s;
        background: ${getRandomColor()};
    `;

    container.appendChild(particle);
}

function getRandomColor() {
    const colors = [
        'rgba(59, 130, 246, 0.3)',  // blue
        'rgba(139, 92, 246, 0.3)',  // purple
        'rgba(6, 182, 212, 0.3)',   // cyan
        'rgba(236, 72, 153, 0.3)',  // pink
    ];
    return colors[Math.floor(Math.random() * colors.length)];
}

/**
 * Smooth scroll for anchor links
 */
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

/**
 * Counter animation for stats
 */
function animateCounter(element, target, duration = 2000) {
    const start = 0;
    const increment = target / (duration / 16);
    let current = start;

    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            element.textContent = target;
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current);
        }
    }, 16);
}

/**
 * CamGuard Video Popup Functions
 */
window.openCamGuardVideo = function() {
    // Vervang VIDEO_ID met je YouTube video ID
    const videoId = 'fp3CZoAzmgs'; // Wijzig dit naar je unlisted video ID
    const popup = document.getElementById('camGuardPopup');
    const iframe = document.getElementById('camGuardVideo');

    iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
    popup.classList.remove('hidden');
    popup.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

window.closeCamGuardVideo = function() {
    const popup = document.getElementById('camGuardPopup');
    const iframe = document.getElementById('camGuardVideo');

    popup.classList.add('hidden');
    popup.classList.remove('flex');
    iframe.src = '';
    document.body.style.overflow = 'auto';
}

// Close CamGuard video on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const popup = document.getElementById('camGuardPopup');
        if (popup && !popup.classList.contains('hidden')) {
            closeCamGuardVideo();
        }
    }
});
