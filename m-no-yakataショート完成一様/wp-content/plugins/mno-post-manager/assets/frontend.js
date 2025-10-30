(function () {
  function initSlider(root) {
    var track = root.querySelector('.mno-pm-slider__track');
    if (!track) {
      return;
    }

    var slides = Array.prototype.slice.call(track.children);
    if (!slides.length) {
      return;
    }

    var dotsContainer = root.querySelector('.mno-pm-slider__dots');
    var prevButton = root.querySelector('.mno-pm-slider__nav--prev');
    var nextButton = root.querySelector('.mno-pm-slider__nav--next');
    var index = 0;
    var isDragging = false;
    var startX = 0;
    var currentX = 0;
    var activePointerId = null;

    track.style.width = slides.length * 100 + '%';
    slides.forEach(function (slide) {
      slide.style.width = 100 / slides.length + '%';
    });

    function getDotLabel(i) {
      if (typeof mnoPmSlider !== 'undefined' && mnoPmSlider.i18n && mnoPmSlider.i18n.slide) {
        return mnoPmSlider.i18n.slide.replace('%d', i + 1);
      }
      return 'Slide ' + (i + 1);
    }

    function updateDots() {
      if (!dotsContainer) {
        return;
      }
      dotsContainer.innerHTML = '';
      slides.forEach(function (_, i) {
        var dot = document.createElement('button');
        dot.type = 'button';
        dot.className = 'mno-pm-slider__dot' + (i === index ? ' is-active' : '');
        dot.setAttribute('aria-label', getDotLabel(i));
        dot.addEventListener('click', function () {
          index = i;
          setPosition(true);
        });
        dotsContainer.appendChild(dot);
      });
    }

    function clamp(value, min, max) {
      return Math.min(Math.max(value, min), max);
    }

    function setPosition(withTransition) {
      if (withTransition) {
        track.classList.add('is-animating');
      }
      var offset = index * -100;
      track.style.transform = 'translateX(' + offset + '%)';
      requestAnimationFrame(function () {
        track.classList.remove('is-animating');
      });
      updateDots();
    }

    function goTo(delta) {
      index = clamp(index + delta, 0, slides.length - 1);
      setPosition(true);
    }

    if (prevButton) {
      prevButton.addEventListener('click', function () {
        goTo(-1);
      });
    }

    if (nextButton) {
      nextButton.addEventListener('click', function () {
        goTo(1);
      });
    }

    function pointerDown(event) {
      isDragging = true;
      activePointerId = typeof event.pointerId === 'number' ? event.pointerId : null;
      startX = event.clientX || (event.touches && event.touches[0].clientX) || 0;
      currentX = 0;
      track.style.transition = 'none';
      if (typeof track.setPointerCapture === 'function' && activePointerId !== null) {
        track.setPointerCapture(activePointerId);
      }
    }

    function pointerMove(event) {
      if (!isDragging) {
        return;
      }
      if (typeof event.pointerId === 'number' && activePointerId !== null && event.pointerId !== activePointerId) {
        return;
      }
      var clientX = event.clientX || (event.touches && event.touches[0].clientX) || 0;
      currentX = clientX - startX;
      var percent = (currentX / root.offsetWidth) * 100;
      var offset = -index * 100 + percent;
      track.style.transform = 'translateX(' + offset + '%)';
      event.preventDefault();
    }

    function pointerUp(event) {
      if (!isDragging) {
        return;
      }
      isDragging = false;
      track.style.transition = '';
      if (typeof track.releasePointerCapture === 'function' && activePointerId !== null) {
        try {
          track.releasePointerCapture(activePointerId);
        } catch (error) {
          // Ignore release errors.
        }
      }
      activePointerId = null;
      if (Math.abs(currentX) > root.offsetWidth / 4) {
        index = clamp(index + (currentX > 0 ? -1 : 1), 0, slides.length - 1);
      }
      setPosition(true);
    }

    if ('PointerEvent' in window) {
      track.addEventListener('pointerdown', pointerDown);
      track.addEventListener('pointermove', pointerMove);
      window.addEventListener('pointerup', pointerUp);
      window.addEventListener('pointercancel', pointerUp);
    } else {
      track.addEventListener('touchstart', pointerDown, { passive: true });
      track.addEventListener('touchmove', pointerMove, { passive: false });
      track.addEventListener('touchend', pointerUp);
      track.addEventListener('mousedown', pointerDown);
      window.addEventListener('mousemove', pointerMove);
      window.addEventListener('mouseup', pointerUp);
    }

    updateDots();
    setPosition(false);
  }

  document.addEventListener('DOMContentLoaded', function () {
    var sliders = document.querySelectorAll('[data-mno-pm-slider]');
    Array.prototype.forEach.call(sliders, function (slider) {
      initSlider(slider);
    });
  });
})();
