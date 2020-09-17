exports.module = (function() {
  /**
   * @var string Service worker js page.
   */
  const SERVICE_WORKER_JS = '/sw.js';

  /**
   * @return string Name of the module
   */
  var getName = function() {
    return _name;
  }

  /**
   * Actions to perform when the DOM is loaded.
   */
  var _ready = function() {
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register(SERVICE_WORKER_JS);
    }
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
