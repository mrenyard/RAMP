/**
 * Author: renyard.m
 */
var SVELTE = window.SVELTE || {};

SVELTE.hijax = function() {

  if (typeof XMLHttpRequest === 'undefined') {
    XMLHttpRequest = function () {
      try {
        return new ActiveXObject('Msxml2.XMLHTTP.6.0');
      } catch (e) {}
      try {
        return new ActiveXObject('Msxml2.XMLHTTP.3.0');
      } catch (e) {}
      try {
        return new ActiveXObject('Microsoft.XMLHTTP');
      } catch (e) {}
      return false;
    };
  }

  var _lightbox = document.createElement('div'),
      _dialogue = document.createElement('div');

  _lightbox.setAttribute('id','lightbox'); _dialogue.setAttribute('id', 'dialogue');
  _lightbox.appendChild(_dialogue); document.body.appendChild(_lightbox);
  _dialogue.remove = undefined;

  _dialogue.open = function() {
    _lightbox.classList.add('open');
    _dialogue.setAttribute('tabindex', '0'); _dialogue.focus();
    _dialogue.addEventListener('blur', function() { this.focus(); });
  };

  var _handleResponse = function(request) {
    if (request.readyState == 4) {
      var fragHTML = request.responseText;
      switch (request.status) {
        case 302: // Found (treating same as 303)
        case 303: // See Other (redirect)
            // browser handles redirect internally
          break;
        case 200: // Ok
        case 201: // Created
        case 202: // Accepted
        case 304: // Not Modified
          var fragRoot = request.responseXML.documentElement,
              elWithId = fragRoot,
              nParents = 0;
          do {
            if (elWithId.getAttribute('id') !== null) { break; }
            elWithId = elWithId['children'][0];
            nParents++;
          } while (elWithId);
          if (elWithId) {
            var pageEl = document.getElementById(elWithId.getAttribute('id'));
            while (nParents > 0) {
              pageEl = pageEl['parentNode'];
              nParents--;
            }
            pageEl.outerHTML = fragHTML;
            return document.getElementById(elWithId.getAttribute('id'));
          }
          break; // todo:mrenyard: move inside if statement for FULLTHROUGH
        default: // load in lightbox
          fragHTML = (fragHTML !== '')? fragHTML :
            '<h1>' + request.status + ' ' + request.statusText + '</h1>';
          this.dialogue.innerHTML = fragHTML;
          this.dialogue.open();
          break;
      }
    }
    return false;
  };

  return {
    dialogue: _dialogue,
    handleResponse: function(request) { return _handleResponse(request); }
  };
}();
