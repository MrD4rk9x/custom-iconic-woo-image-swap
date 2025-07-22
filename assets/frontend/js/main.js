(function ($, document) {
  if (!iconic_wis_vars.settings.compatibility_general_ajax_events) {
    return;
  }
  const third_party_events = iconic_wis_vars.settings.compatibility_general_ajax_events.split(/\r?\n/).filter(Boolean);
  if (!third_party_events) {
    return;
  }
  $(document).on(third_party_events.join(' '), function () {
    $(document).trigger(`iconic_wis_${iconic_wis_vars.settings.general_display_effect}_init`);
  });
})(jQuery, document);
/**
 * Martfury.
 */

/**
 * Correct Embedded Slick.
 *
 * Fixes an issue if the slick slider is embedded within a slick slider.
 */
function correctEmbeddedSlick() {
  const martfury = document.querySelector('body.theme-martfury');
  if (!martfury) {
    return;
  }
  const embeddedSlick = document.querySelectorAll('.slick-track .iconic-wis-product-image--bullets, .slick-track .iconic-wis-product-image--slide');
  if (embeddedSlick.length < 1) {
    return;
  }
  Array.from(embeddedSlick).forEach(function (slides) {
    Array.from(slides.querySelectorAll('.slick-list')).forEach(function (list) {
      const width = list.querySelector('a:first-of-type').style.width;
      list.initialSlide(1); // Ensure the initial slide is 1.

      // Sometimes the width is being calculated.
      // If it doesn't exist, try again.
      if (!width) {
        correctEmbeddedSlick();
        return;
      }
      list.querySelector('.slick-track').style.marginLeft = width;
    });
  });
}
let rtime;
let timeout = false;
const delta = 200;
jQuery(window).on('resize', function () {
  rtime = new Date();
  if (timeout === false) {
    timeout = true;
    setTimeout(resizeEnd, delta);
  }
});
function resizeEnd() {
  if (new Date() - rtime < delta) {
    setTimeout(resizeEnd, delta);
  } else {
    timeout = false;
    // Needs time for slick to recalculate.
    setTimeout(correctEmbeddedSlick, 500);
  }
}
(function ($, document) {
  jQuery('.iconic-wis-product-image--bullets, .iconic-wis-product-image--slide').on('init', function (event, slick) {
    // Needs time for slick to recalculate.
    setTimeout(correctEmbeddedSlick, 500);
  });
})(jQuery, document);
(function ($, document) {
  const iconic_wis_fade = {
    cache() {
      iconic_wis_fade.els = {};
      iconic_wis_fade.els.products = $('.iconic-wis-product-image--fade');
    },
    on_ready() {
      iconic_wis_fade.cache();
      iconic_wis_fade.setup_fade();
    },
    /**
     * Setup fade.
     */
    setup_fade() {
      iconic_wis_fade.set_min_widths();
      $(window).on('resize', iconic_wis_fade.set_min_widths);
    },
    /**
     * Set minimum widths.
     */
    set_min_widths() {
      iconic_wis_fade.els.products.each(function (index) {
        let tallestHeight = 0;
        $(this).find('img').each(function () {
          const imgHeight = $(this).height();
          if (imgHeight > tallestHeight) {
            tallestHeight = imgHeight;
          }
        });
        $(this).css('min-height', tallestHeight);
      });
    }
  };
  $(document).ready(iconic_wis_fade.on_ready);
  $(document).on('iconic_wis_fade_init', iconic_wis_fade.on_ready);
})(jQuery, document);
(function ($, document) {
  var iconic_wis_magnific = {
    cache() {
      iconic_wis_magnific.vars = {};
      iconic_wis_magnific.els = {};

      // common elements
      iconic_wis_magnific.els.magnific_buttons = $('.iconic-wis-product-image__modal-button');
    },
    on_ready() {
      // on ready stuff here
      iconic_wis_magnific.cache();
      iconic_wis_magnific.setup_magnific();
    },
    setup_magnific() {
      iconic_wis_magnific.els.magnific_buttons.on('click', function () {
        let images = $(this).data('images');
        images = iconic_wis_magnific.prepare_images(images);
        if (images === false) {
          return;
        }
        $.magnificPopup.open({
          mainClass: 'iconic-wis-modal',
          closeOnContentClick: true,
          items: images,
          gallery: {
            enabled: true
          }
        });
        return false;
      });
    },
    /**
     * Prepare images for magnific
     *
     * @param array   $images
     * @param $images
     * @return object
     */
    prepare_images($images) {
      if (typeof $images === 'undefined') {
        return false;
      }
      if ($images.length <= 0) {
        return false;
      }
      const images_object = [];
      $.each($images, function (index, image) {
        const $image = $(image),
          src = $image.attr('src');
        images_object[index] = {
          src,
          type: 'image'
        };
      });
      return images_object;
    }
  };
  $(document).ready(iconic_wis_magnific.on_ready);
  $(document).on('iconic_wis_modal_init iconic_wis_magnific_init', iconic_wis_magnific.on_ready);
})(jQuery, document);
(function ($, document) {
  var iconic_wis_pip = {
    cache() {
      iconic_wis_pip.els = {};

      // common elements
      iconic_wis_pip.els.pip_switches = $('.iconic-wis-product-image__pip-switch');
    },
    on_ready() {
      iconic_wis_pip.cache();
      iconic_wis_pip.setup_pip();
    },
    /**
     * Setup thumbnails
     */
    setup_pip() {
      iconic_wis_pip.els.pip_switches.on('click', function (e) {
        e.preventDefault();
        const $pip_image = $(this).find('img'),
          $pip_image_source = $(this).find('source'),
          $wrapper = $pip_image.closest('.iconic-wis-product-image'),
          $large_image = $wrapper.find('.iconic-wis-product-image__large_image img'),
          $large_image_source = $wrapper.find('.iconic-wis-product-image__large_image picture source'),
          pip_src = $pip_image.attr('src'),
          pip_srcset = typeof $pip_image.attr('srcset') !== 'undefined' ? $pip_image.attr('srcset') : '',
          pip_sizes = typeof $pip_image.attr('sizes') !== 'undefined' ? $pip_image.attr('sizes') : '',
          large_image_src = $large_image.attr('src'),
          large_image_srcset = typeof $large_image.attr('srcset') !== 'undefined' ? $large_image.attr('srcset') : '',
          large_image_sizes = typeof $large_image.attr('sizes') !== 'undefined' ? $large_image.attr('sizes') : '';
        $large_image.attr('src', pip_src).attr('srcset', pip_srcset).attr('sizes', pip_sizes);
        $large_image_source.attr('src', pip_src).attr('srcset', pip_srcset).attr('sizes', pip_sizes);
        $pip_image.attr('src', large_image_src).attr('srcset', large_image_srcset).attr('sizes', large_image_sizes);
        $pip_image_source.attr('src', large_image_src).attr('srcset', large_image_srcset).attr('sizes', large_image_sizes);
      });
    }
  };
  $(document).ready(iconic_wis_pip.on_ready);
  $(document).on('iconic_wis_pip_init', iconic_wis_pip.on_ready);
})(jQuery, document);
(function ($, document) {
  var iconic_wis_slick = {
    cache() {
      iconic_wis_slick.vars = {};
      iconic_wis_slick.els = {};

      // common vars
      iconic_wis_slick.vars.hidden_class = 'iconic-wis-hidden';

      // common elements
      iconic_wis_slick.els.slick_sliders = $('[data-slick]:not(.slick-initialized)');
    },
    on_ready() {
      // on ready stuff here
      iconic_wis_slick.cache();
      iconic_wis_slick.setup_slick();
    },
    setup_slick() {
      if (iconic_wis_slick.els.slick_sliders.length <= 0) {
        return;
      }
      iconic_wis_slick.els.slick_sliders.on('init', function (event, slick) {
        $(this).find('.' + iconic_wis_slick.vars.hidden_class).removeClass(iconic_wis_slick.vars.hidden_class);
      });
      iconic_wis_slick.els.slick_sliders.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
        if (iconic_wis_slick.should_shrink_out()) {
          iconic_wis_slick.transition_shrink_out_before($(this), currentSlide);
        }
      });
      iconic_wis_slick.els.slick_sliders.on('afterChange', function (event, slick, currentSlide) {
        if (iconic_wis_slick.should_shrink_out()) {
          iconic_wis_slick.transition_shrink_out_after($(this));
        }
      });
      iconic_wis_slick.els.slick_sliders.slick({
        slidesToShow: 1,
        slidesToScroll: 1
      });

      // Don't follow parent anchor when clicked.
      $(document).on('click', '.slick-dots button, .iconic-wis-product-image__arrow', function (e) {
        e.preventDefault();
      });
    },
    /**
     * Transition - shrink out, before
     *
     * @param obj          $this
     * @param int          currentSlide
     * @param $this
     * @param currentSlide
     */
    transition_shrink_out_before($this, currentSlide) {
      const current_slide = $this.find('img').eq(currentSlide + 1);
      current_slide.css({
        transform: 'scale(0.5)'
      });
    },
    /**
     * Transition - shrink out, after
     *
     * @param obj   $this
     * @param $this
     */
    transition_shrink_out_after($this) {
      $this.find('img').css({
        transform: 'scale(1)'
      });
    },
    /**
     * Helper: Should shrink out
     *
     * @return bool
     */
    should_shrink_out() {
      return iconic_wis_vars.settings.general_display_effect === 'slide' && iconic_wis_vars.settings.effects_slide_transition === 'shrink_out';
    }
  };
  $(document).ready(iconic_wis_slick.on_ready);
  $(document).on('iconic_wis_slide_init iconic_wis_slick_init iconic_wis_bullets_init', iconic_wis_slick.on_ready);
})(jQuery, document);
(function ($, document) {
  var iconic_wis_thumbnails = {
    cache() {
      iconic_wis_thumbnails.vars = {};
      iconic_wis_thumbnails.els = {};

      // common vars
      iconic_wis_thumbnails.vars.active_class = 'iconic-wis-product-image__thumbnail--active';

      // common elements
      iconic_wis_thumbnails.els.thumbnails_container = $('.iconic-wis-product-image__thumbnails');
    },
    on_ready() {
      iconic_wis_thumbnails.cache();
      iconic_wis_thumbnails.setup_thumbnails();
    },
    /**
     * Setup thumbnails
     */
    setup_thumbnails() {
      iconic_wis_thumbnails.els.thumbnails_container.on('click', 'img', function (e) {
        e.preventDefault();
        const $thumbnail = $(this),
          $wrapper = $thumbnail.closest('.iconic-wis-product-image'),
          $list_item = $thumbnail.closest('li'),
          $large_image = $wrapper.find('.iconic-wis-product-image__large_image img'),
          large_image_src = $thumbnail.data('large-image'),
          srcset = $thumbnail.data('large-image-srcset'),
          sizes = $thumbnail.data('large-image-sizes');
        $wrapper.find('.' + iconic_wis_thumbnails.vars.active_class).removeClass(iconic_wis_thumbnails.vars.active_class);
        $list_item.addClass(iconic_wis_thumbnails.vars.active_class);
        $large_image.attr('src', large_image_src).attr('srcset', srcset).attr('sizes', sizes);
      });
    }
  };
  $(document).ready(iconic_wis_thumbnails.on_ready);
  $(document).on('iconic_wis_thumbnails_init', iconic_wis_thumbnails.on_ready);
})(jQuery, document);
(function ($, document) {
  var iconic_wis_zoom = {
    cache() {
      iconic_wis_zoom.vars = {};
      iconic_wis_zoom.els = {};

      // common vars
      iconic_wis_zoom.vars.active_class = 'iconic-wis-product-image__thumbnail--active';

      // common elements
      iconic_wis_zoom.els.zoom_images = $('.iconic-wis-product-image--zoom img');
    },
    on_ready() {
      iconic_wis_zoom.cache();
      iconic_wis_zoom.setup_zoom();
    },
    /**
     * Setup zoom
     */
    setup_zoom() {
      if (iconic_wis_zoom.els.zoom_images.length <= 0) {
        return;
      }
      iconic_wis_zoom.els.zoom_images.each(function (index, zoom_image) {
        const large_image_src = iconic_wis_zoom.get_large_image_src(zoom_image);
        $(zoom_image).ImageZoom({
          showDescription: false,
          smoothMove: true,
          bigImageSrc: large_image_src,
          max: iconic_wis_vars.settings.effects_zoom_max
        });
      });
    },
    /**
     * Get large image src
     * @param image
     */
    get_large_image_src(image) {
      const large_image_src = typeof $(image).data('large-image') !== 'undefined' ? $(image).data('large-image') : '';
      return large_image_src;
    }
  };
  $(document).ready(iconic_wis_zoom.on_ready);
  $(document).on('iconic_wis_zoom_init', iconic_wis_zoom.on_ready);

  // Compatibility with Porto theme.
  // This is the only event triggered at the end of the
  // porto_update_products event, so despite the name of
  // this event, it is in fact the only option we have.
  //
  // We cannot rely on Porto's skeleton-loaded event as this
  // does not fire when results or sorted or the view changes.
  $(document).on('yith-wcan-ajax-filtered', iconic_wis_zoom.on_ready);
})(jQuery, document);