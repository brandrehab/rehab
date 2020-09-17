exports.module = (function() {

  /**
   * @var string Name of the module
   */
  var _name = 'hash';

  /**
   * @return string Name of the module
   */
  var getName = function() {
    return _name;
  }

  /**
   * Provides an anchor jump allowing 100px for the fixed navigation.
   */
  var _hashJump = function() {
    var locationHash = location.hash;
    if (locationHash) {
      $(function() {
        let offsetTop = $(locationHash).offset().top - 100;
        $(document).scrollTop(offsetTop);
      });
    }
  };

  /**
   * Actions to perform when the DOM is loaded.
   */
  var _ready = function() {
    $(function() {
      _hashJump();
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
