/**
 * FUNC.js - Frontend Utilities for Networked Client (js).
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package func.event
 * @version 0.1;
 */
var FUNC = window.FUNC || {};
FUNC.version = FUNC.version || .1;

/**
 * Event library (register, dargNdrop).
 */
FUNC.hijax = function(elm)
{
  //- USE (dependant namespaces)
  //- PRIVATE VARABLES
  //- LOCAL METHODS
  //- INITIALISE
    
  //- LOCAL METHODS.
  var handleResponse = function(request) {
    if (request.readyState == 4) {
      var responsePlain = request.responseText;
      switch (request.status) {
        case 302: // Found (treating same as 303)
        case 303: // See Other (redirect)
            // browser handles redirect internally
          break;
        case 200: // Ok
        case 201: // Created
        case 202: // Accepted
        case 304: // Not Modified
          //if (responsePlain == 'success') { return true; }
          var response = request.responseXML.documentElement;
          for(var i=0, j=response['children'].length; i<j; i++) {
            var elWithId = response['children'][i], nParents = 0;
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
              if (pageEl) { pageEl.outerHTML = response['children'][i].outerHTML; }
            }
          }
          return true; //document.getElementById(elWithId.getAttribute('id'));
          break; // todo:mrenyard: move inside if statement for FULLTHROUGH
        default: // load in lightbox
          responsePlain = (responsePlain !== '')? responsePlain :
            '<h1>' + request.status + ' ' + request.statusText + '</h1>';
          var _lb = document.getElementById('lightbox'); _lb = (_lb)? _lb : document.body;
          _lb.innerHTML = responsePlain;
          location.hash = _lb.id;
          break;
      }
    }
    return false;
  };

  //- INITIALISE
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

  //- PUBLIC ACCESS
  return { handleResponse };
}();