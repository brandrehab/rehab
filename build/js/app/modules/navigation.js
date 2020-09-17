exports.module = (function () {

  /**
   * @var string Name of the module
   */
  var _name = 'navigation';

  /**
   * @return string Name of the module
   */
  var getName = function() {
    return _name;
  }


  /**
   * Events this module should listen to
   */
  var _attachListeners = function() {
    $(window).on('scroll', function(evt) {
      _toggleNavigationTheme($('#navigation'));
    });

    $('#navigation .nav-item.parent').on('mouseenter', function(evt) {
      $('#navigation .sub-nav').addClass('d-none');
      $(this).children('.sub-nav').removeClass('d-none');
    });

    $('#navigation .nav-item.parent').on('mouseleave', function(evt) {
      $('#navigation .sub-nav').addClass('d-none');
    });
  }

  /**
   * Actions to perform when document is ready according to jquery
   */
  var _ready = function() {
    $(function() {
      if ($('#navigation').length > 0) {
        _attachListeners();
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
  };

  return _init();
})();
