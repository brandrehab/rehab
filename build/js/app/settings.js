exports.module = (function () {

  var loadDrupalSettings = function() {
    return document.getElementById('drupalSettings') ? JSON.parse(document.getElementById('drupalSettings').innerHTML) : null;
  }

  var _init = function() {
    return loadDrupalSettings();
  }

  return _init();
})();
