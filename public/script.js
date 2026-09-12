const menuButton = document.querySelector('.menu-toggle');
const navigation = document.querySelector('.site-nav');
const toast = document.querySelector('.toast');

menuButton?.addEventListener('click', () => {
  const open = menuButton.getAttribute('aria-expanded') === 'true';
  menuButton.setAttribute('aria-expanded', String(!open));
  navigation.classList.toggle('open', !open);
});

navigation?.addEventListener('click', (event) => {
  if (event.target.matches('a')) {
    navigation.classList.remove('open');
    menuButton?.setAttribute('aria-expanded', 'false');
  }
});

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
const heroCarousel = document.querySelector('[data-hero-carousel]');

if (heroCarousel) {
  const heroSlides = [...heroCarousel.querySelectorAll('[data-hero-slide]')];
  const heroDots = [...heroCarousel.querySelectorAll('[data-hero-dot]')];
  const heroProgress = heroCarousel.querySelector('[data-hero-progress]');
  let activeHeroSlide = 0;
  let heroAutoplay;
  let heroIsVisible = true;
  let heroHasFocus = false;
  let pointerStartX;

  const showHeroSlide = (index) => {
    activeHeroSlide = (index + heroSlides.length) % heroSlides.length;

    heroSlides.forEach((slide, slideIndex) => {
      const isActive = slideIndex === activeHeroSlide;
      slide.classList.toggle('active', isActive);
      slide.setAttribute('aria-hidden', String(!isActive));
      slide.inert = !isActive;
    });

    heroDots.forEach((dot, dotIndex) => {
      const isActive = dotIndex === activeHeroSlide;
      dot.classList.toggle('active', isActive);
      dot.setAttribute('aria-current', String(isActive));
    });
  };

  const stopHeroAutoplay = () => {
    window.clearTimeout(heroAutoplay);
    heroProgress?.classList.remove('running');
  };

  const restartHeroProgress = () => {
    if (!heroProgress) {
      return;
    }

    heroProgress.classList.remove('running');
    void heroProgress.offsetWidth;
    heroProgress.classList.add('running');
  };

  const startHeroAutoplay = () => {
    stopHeroAutoplay();

    if (heroSlides.length > 1 && !reducedMotion.matches && !document.hidden && heroIsVisible && !heroHasFocus) {
      restartHeroProgress();
      heroAutoplay = window.setTimeout(() => {
        showHeroSlide(activeHeroSlide + 1);
        startHeroAutoplay();
      }, 6500);
    }
  };

  heroDots.forEach((dot) => dot.addEventListener('click', (event) => {
    showHeroSlide(Number(dot.dataset.heroDot));

    if (event.detail > 0) {
      dot.blur();
      heroHasFocus = false;
    }

    startHeroAutoplay();
  }));

  heroCarousel.addEventListener('focusin', () => {
    heroHasFocus = true;
    stopHeroAutoplay();
  });
  heroCarousel.addEventListener('focusout', () => {
    heroHasFocus = false;
    startHeroAutoplay();
  });
  heroCarousel.addEventListener('pointerdown', (event) => {
    pointerStartX = event.clientX;
  });
  heroCarousel.addEventListener('pointerup', (event) => {
    if (pointerStartX === undefined || Math.abs(event.clientX - pointerStartX) < 45) {
      pointerStartX = undefined;
      return;
    }

    showHeroSlide(activeHeroSlide + (event.clientX < pointerStartX ? 1 : -1));
    pointerStartX = undefined;
    startHeroAutoplay();
  });
  const heroVisibilityObserver = new IntersectionObserver(([entry]) => {
    heroIsVisible = entry.isIntersecting && entry.intersectionRatio >= 0.25;

    if (heroIsVisible) {
      startHeroAutoplay();
    } else {
      heroHasFocus = false;
      stopHeroAutoplay();
    }
  }, { threshold: [0, 0.25] });

  heroVisibilityObserver.observe(heroCarousel);
  document.addEventListener('visibilitychange', () => (document.hidden ? stopHeroAutoplay() : startHeroAutoplay()));
  reducedMotion.addEventListener('change', startHeroAutoplay);
  startHeroAutoplay();
}

const categoryTrack = document.querySelector('[data-category-track]');

document.querySelectorAll('[data-category-scroll]').forEach((button) => {
  button.addEventListener('click', () => {
    if (!categoryTrack) {
      return;
    }

    const direction = Number(button.dataset.categoryScroll);
    const cards = categoryTrack.querySelectorAll('.product-explorer-card');

    if (cards.length < 2) {
      return;
    }

    categoryTrack.scrollTo({ left: 0, behavior: 'auto' });

    if (direction > 0) {
      categoryTrack.append(cards[0]);
    } else {
      categoryTrack.prepend(cards[cards.length - 1]);
    }
  });
});

const branchMap = document.querySelector('#branch-map');
const branchMapEmpty = document.querySelector('[data-branch-map-empty]');

document.querySelectorAll('[data-branch-map]').forEach((button) => {
  button.addEventListener('click', () => {
    if (!branchMap || !button.dataset.embedUrl) {
      return;
    }

    branchMap.src = button.dataset.embedUrl;
    branchMap.title = `Map of ${button.dataset.location}`;
    branchMap.hidden = false;
    branchMapEmpty?.setAttribute('hidden', '');

    document.querySelectorAll('.branch-card').forEach((card) => card.classList.remove('selected'));
    button.closest('.branch-card')?.classList.add('selected');
  });
});

document.querySelector('#year').textContent = new Date().getFullYear();

const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      revealObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.12 });

document.querySelectorAll('.reveal').forEach((element) => revealObserver.observe(element));

const inquiryLauncher = document.querySelector('.inquiry-launcher');
const inquiryAssistant = document.querySelector('.inquiry-assistant');
const inquiryClose = document.querySelector('[data-inquiry-close]');
const inquiryForm = document.querySelector('[data-inquiry-form]');
const inquirySuccess = document.querySelector('[data-inquiry-success]');
let inquiryStep = 1;

const showInquiryStep = (step) => {
  inquiryStep = step;
  document.querySelectorAll('[data-inquiry-step]').forEach((panel) => {
    const isActive = Number(panel.dataset.inquiryStep) === step;
    panel.hidden = !isActive;
    panel.classList.toggle('active', isActive);
  });
};

const setInquiryOpen = (open) => {
  inquiryAssistant?.classList.toggle('open', open);
  inquiryAssistant?.setAttribute('aria-hidden', String(!open));
  inquiryLauncher?.setAttribute('aria-expanded', String(open));

  if (open) {
    inquiryClose?.focus();
  } else {
    inquiryLauncher?.focus();
  }
};

inquiryLauncher?.addEventListener('click', () => setInquiryOpen(true));
inquiryClose?.addEventListener('click', () => setInquiryOpen(false));

document.addEventListener('keydown', (event) => {
  if (event.key === 'Escape' && inquiryAssistant?.classList.contains('open')) {
    setInquiryOpen(false);
  }
});

document.querySelectorAll('[data-inquiry-next]').forEach((button) => {
  button.addEventListener('click', () => {
    const panel = button.closest('[data-inquiry-step]');
    const requiredFields = [...panel.querySelectorAll('[required]')];
    const invalidField = requiredFields.find((field) => !field.checkValidity());

    if (invalidField) {
      invalidField.reportValidity();
      return;
    }

    showInquiryStep(Math.min(inquiryStep + 1, 4));
  });
});

document.querySelectorAll('[data-inquiry-back]').forEach((button) => {
  button.addEventListener('click', () => showInquiryStep(Math.max(inquiryStep - 1, 1)));
});

const distanceBetween = (latitudeA, longitudeA, latitudeB, longitudeB) => {
  const toRadians = (degrees) => degrees * (Math.PI / 180);
  const latitudeDifference = toRadians(latitudeB - latitudeA);
  const longitudeDifference = toRadians(longitudeB - longitudeA);
  const value = Math.sin(latitudeDifference / 2) ** 2
    + Math.cos(toRadians(latitudeA)) * Math.cos(toRadians(latitudeB))
    * Math.sin(longitudeDifference / 2) ** 2;

  return 6371 * 2 * Math.atan2(Math.sqrt(value), Math.sqrt(1 - value));
};

document.querySelector('[data-find-nearest]')?.addEventListener('click', (event) => {
  const locationButton = event.currentTarget;
  const status = document.querySelector('[data-location-status]');
  const branchOptions = [...(inquiryForm?.querySelectorAll('[name="branch_id"]') ?? [])];
  const mappedBranches = branchOptions.filter((option) => (
    option.dataset.latitude
    && option.dataset.longitude
    && Number.isFinite(Number(option.dataset.latitude))
    && Number.isFinite(Number(option.dataset.longitude))
  ));

  if (!navigator.geolocation) {
    status.classList.remove('is-success');
    status.textContent = 'Location is unavailable in this browser. Please choose a branch below.';
    return;
  }

  if (mappedBranches.length === 0) {
    status.classList.remove('is-success');
    status.textContent = 'Branch locations are still being mapped. Please choose a branch below.';
    return;
  }

  locationButton.disabled = true;
  locationButton.textContent = 'Finding your nearest branch…';
  status.classList.remove('is-success');
  status.textContent = 'Finding the nearest branch…';
  navigator.geolocation.getCurrentPosition((position) => {
    const nearestBranch = mappedBranches.reduce((nearest, option) => {
      const distance = distanceBetween(
        position.coords.latitude,
        position.coords.longitude,
        Number(option.dataset.latitude),
        Number(option.dataset.longitude),
      );

      return !nearest || distance < nearest.distance ? { option, distance } : nearest;
    }, null);

    nearestBranch.option.checked = true;
    nearestBranch.option.dispatchEvent(new Event('change', { bubbles: true }));
    const branchOption = nearestBranch.option.closest('.inquiry-branch-option');
    const branchName = branchOption?.querySelector('strong')?.textContent;
    status.textContent = `Nearest branch found: ${branchName} (about ${Math.round(nearestBranch.distance)} km away). Review the selection below, then press Continue.`;
    status.classList.add('is-success');
    branchOption?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    nearestBranch.option.focus({ preventScroll: true });
    locationButton.disabled = false;
    locationButton.textContent = 'Use my current location';
  }, () => {
    status.classList.remove('is-success');
    status.textContent = 'We could not access your location. Please choose a branch below.';
    locationButton.disabled = false;
    locationButton.textContent = 'Use my current location';
  }, { enableHighAccuracy: false, timeout: 8000, maximumAge: 300000 });
});

inquiryForm?.addEventListener('submit', async (event) => {
  event.preventDefault();

  if (!inquiryForm.checkValidity()) {
    inquiryForm.reportValidity();
    return;
  }

  const submitButton = inquiryForm.querySelector('[type="submit"]');
  const errorMessage = inquiryForm.querySelector('[data-inquiry-error]');
  submitButton.disabled = true;
  submitButton.textContent = 'Sending…';
  errorMessage.hidden = true;

  try {
    const response = await fetch(inquiryForm.action, {
      method: 'POST',
      headers: { Accept: 'application/json' },
      body: new FormData(inquiryForm),
    });
    const result = await response.json();

    if (!response.ok) {
      const validationMessage = result.errors ? Object.values(result.errors).flat()[0] : null;
      throw new Error(validationMessage || (response.status === 429 ? 'Too many attempts. Please try again later.' : 'We could not send your inquiry. Please try again.'));
    }

    inquiryForm.hidden = true;
    inquirySuccess.hidden = false;
    inquirySuccess.querySelector('[data-inquiry-success-message]').textContent = result.message;
  } catch (error) {
    errorMessage.textContent = error.message;
    errorMessage.hidden = false;
  } finally {
    submitButton.disabled = false;
    submitButton.textContent = 'Send inquiry';
  }
});

document.querySelector('[data-inquiry-done]')?.addEventListener('click', () => {
  inquiryForm?.reset();
  inquiryForm.hidden = false;
  inquirySuccess.hidden = true;
  showInquiryStep(1);
  setInquiryOpen(false);
});

document.querySelectorAll('[data-image-zoom]').forEach((zoomArea) => {
  const image = zoomArea.querySelector('img');
  const lens = zoomArea.querySelector('.image-zoom-lens');
  const zoomLevel = 3.5;

  const updateLensImage = () => {
    if (image && lens) {
      lens.style.backgroundImage = `url("${image.currentSrc || image.src}")`;
    }
  };

  const setZoomPosition = (clientX, clientY) => {
    const bounds = zoomArea.getBoundingClientRect();
    const x = Math.min(Math.max(clientX - bounds.left, 0), bounds.width);
    const y = Math.min(Math.max(clientY - bounds.top, 0), bounds.height);

    zoomArea.style.setProperty('--zoom-x', `${(x / bounds.width) * 100}%`);
    zoomArea.style.setProperty('--zoom-y', `${(y / bounds.height) * 100}%`);
    zoomArea.style.setProperty('--lens-x', `${x}px`);
    zoomArea.style.setProperty('--lens-y', `${y}px`);

    if (!image?.naturalWidth || !image.naturalHeight || !lens) {
      return;
    }

    const imageStyles = window.getComputedStyle(image);
    const paddingLeft = Number.parseFloat(imageStyles.paddingLeft) || 0;
    const paddingRight = Number.parseFloat(imageStyles.paddingRight) || 0;
    const paddingTop = Number.parseFloat(imageStyles.paddingTop) || 0;
    const paddingBottom = Number.parseFloat(imageStyles.paddingBottom) || 0;
    const availableWidth = image.clientWidth - paddingLeft - paddingRight;
    const availableHeight = image.clientHeight - paddingTop - paddingBottom;
    const imageRatio = image.naturalWidth / image.naturalHeight;
    const availableRatio = availableWidth / availableHeight;
    const renderedWidth = availableRatio > imageRatio ? availableHeight * imageRatio : availableWidth;
    const renderedHeight = availableRatio > imageRatio ? availableHeight : availableWidth / imageRatio;
    const renderedLeft = image.offsetLeft + paddingLeft + ((availableWidth - renderedWidth) / 2);
    const renderedTop = image.offsetTop + paddingTop + ((availableHeight - renderedHeight) / 2);
    const imageX = Math.min(Math.max(x - renderedLeft, 0), renderedWidth);
    const imageY = Math.min(Math.max(y - renderedTop, 0), renderedHeight);
    const lensRadius = lens.offsetWidth / 2;

    lens.style.backgroundSize = `${renderedWidth * zoomLevel}px ${renderedHeight * zoomLevel}px`;
    lens.style.backgroundPosition = `${lensRadius - (imageX * zoomLevel)}px ${lensRadius - (imageY * zoomLevel)}px`;
  };

  updateLensImage();
  image?.addEventListener('load', updateLensImage);
  zoomArea.addEventListener('pointermove', (event) => setZoomPosition(event.clientX, event.clientY));
  zoomArea.addEventListener('click', (event) => {
    if (window.matchMedia('(hover: none)').matches) {
      setZoomPosition(event.clientX, event.clientY);
      zoomArea.classList.toggle('is-zoomed');
    }
  });
  zoomArea.addEventListener('keydown', (event) => {
    if (event.key === 'Enter' || event.key === ' ') {
      event.preventDefault();
      zoomArea.classList.toggle('is-zoomed');
    }

    if (event.key === 'Escape') {
      zoomArea.classList.remove('is-zoomed');
    }
  });
});

document.querySelectorAll('[data-product-gallery]').forEach((gallery) => {
  const mainImage = gallery.querySelector('[data-gallery-main]');
  const zoomArea = gallery.querySelector('[data-image-zoom]');

  gallery.querySelectorAll('[data-gallery-thumbnail]').forEach((thumbnail) => {
    thumbnail.addEventListener('click', () => {
      if (!mainImage) {
        return;
      }

      mainImage.src = thumbnail.dataset.imageUrl;
      mainImage.alt = thumbnail.dataset.imageAlt;
      zoomArea?.classList.remove('is-zoomed');

      gallery.querySelectorAll('[data-gallery-thumbnail]').forEach((item) => {
        const isSelected = item === thumbnail;
        item.classList.toggle('is-active', isSelected);
        item.setAttribute('aria-pressed', String(isSelected));
      });
    });
  });
});
