exports.module = (function() {

  /**
   * @var string Name of the module
   */
  var _name = 'recaptcha';

  /**
   * @return string Name of the module
   */
  var getName = function() {
    return _name;
  }

  /**
   * Add the recaptcha tag to the head of the page
   */
  var _initRecaptcha = function() {
    if (drupalSettings.recaptcha) {
      var script = document.createElement('script');
      script.type = 'text/javascript';
      script.src = drupalSettings.recaptcha.url + '?render=' + drupalSettings.recaptcha.key;
      document.head.appendChild(script);
    }
  }

  /**
   * Actions to perform when document is ready according to jquery
   */
  var _ready = function() {
    $(function() {
      _initRecaptcha();
    });
  }

  /**
   * Triggered on module load to set jquery ready function and include public methods.
   * @return object An object provides public access to any functions you wish to make available outside the module.
   */
  var _init = function() {
    _ready();
    return {};
  };

  return _init();
})();
