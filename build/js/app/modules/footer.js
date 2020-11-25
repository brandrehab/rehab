exports.module = (function() {

  /**
   * @var string Name of the module
   */
  var _name = 'footer';

  /**
   * @return string Name of the module
   */
  var getName = function() {
    return _name;
  }

  /**
   * @var boolean Lazy loading is currently running.
   */
  var _lazyLoading = false;

  /**
   * Lazy load any images.
   */
  var _lazyImageLoad = function() {
    _lazyLoading = true;
    let lazyImages = $('.lazy');

    if (lazyImages.length === 0) {
      $(document).off('scroll.lazy');
      $(window).off('resize.lazy');
      $(window).off('orientationchange.lazy');
      return;
    }

    var displayImages = [];
    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).innerHeight();

    $(lazyImages).each(function() {
      var imgWrapper = $(this).parent();
      var elemTop = imgWrapper.offset().top;
      var elemBottom = elemTop + imgWrapper.height();
      if ((elemBottom <= docViewBottom) && (elemTop >= docViewTop)) {
        displayImages.push(this);
      }
    });

    $(displayImages).each(function() {
      if ($(this).data('lazy-srcset')) {
        $(this).attr('srcset', $(this).data('lazy-srcset')).removeClass('lazy');
      }
      if ($(this).data('lazy')) {
        $(this).attr('src', $(this).data('lazy')).removeClass('lazy');
      }
    });

    _lazyLoading = false;
  }

  /**
   * Events the lazy loader should listen for.
   */
  var _attachListeners = function() {
    $('.lazy-trigger').on('click.lazy', function(evt) {
      if (_lazyLoading == false) {
        _lazyImageLoad();
      }
    });
    $(document).on('scroll.lazy', function(evt) {
      if (_lazyLoading == false) {
        _lazyImageLoad();
      }
    });
    $(window).on('resize.lazy', function(evt) {
      if (_lazyLoading == false) {
        _lazyImageLoad();
      }
    });
    $(window).on('orientationchange.lazy', function(evt) {
      if (_lazyLoading == false) {
        _lazyImageLoad();
      }
    });
  }

  /**
   * Actions to perform when doc is ready according to jquery.
   */
  var _ready = function() {
    $(function() {
      if ($('#copyright-year').length > 0) {
        $('#copyright-year').html(new Date().getFullYear());
      }
    });
  }

  /**
   * Triggered on module load to set jquery ready function and include public methods.
   * @return object An object provides public access to any functions you wish to make available outside the module.
   */
  var _init = function() {
    _ready();
    return {
      getName: getName,
    };
  }

  return _init();
})();
