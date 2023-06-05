/**
 * Author: renyard.m
 */

// NAMESPACE
var SVELTE = window.SVELTE || {};

// ENUM
SVELTE.FormType = { CREATE:{ value:1 }, UPDATE:{ value:2 } };

SVELTE.hijax = function() {

  var formCount = 0;

  if (typeof XMLHttpRequest === "undefined") {
    XMLHttpRequest = function () {
      try {
        return new ActiveXObject("Msxml2.XMLHTTP.6.0");
      } catch (e) {}
      try {
        return new ActiveXObject("Msxml2.XMLHTTP.3.0");
      } catch (e) {}
      try {
        return new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e) {}
      return false;
    };
  }
  return {};
}();

// CLASS
SVELTE.hijax.formField = function() {

  var i=0, oForm, regexNewEntry=/:~/;
  while (oForm = document.forms[i++]) {

    // set formType based on form.element.id
    oForm.formType = (regexNewEntry.test(oForm.id))?
     SVELTE.FormType.CREATE : SVELTE.FormType.UPDATE;

    // loop form.elements
    var j=0, oFormElements = oForm.elements, oFormElement;
    while (oFormElement = oFormElements[j++]) {

      switch (oFormElement.type) {
        case 'fieldset':
          continue; // next oFormElement

        case 'submit':
        case 'reset':
          // handle submit and reset buttons
          break;

        default:
          // add default focus event
          if (oForm.formType == SVELTE.FormType.CREATE) {
            oFormElement.addEventListener('focus', function(){
              if (this.className != 'populated') { this.className = 'populated'; }
            });
          }
          oFormElement.addEventListener('change', function(){
            SVELTE.hijax.formField.check(this);
          });
      } // END switch

      oFormElement.addEventListener('blur', function(){
        var errors = this.form.getElementsByClassName('error');
        if (next = errors[0]) { next.focus(); }
      });

    } // END while form.elements
  } // END while forms

  return {

    check: function(formElement) {

      var url = formElement.id.replace(':', '/', 'g'),
          data = formElement.id + '=' + formElement.value;

      formElement.parentNode.classList.remove('error');
      formElement.parentNode.classList.remove('checked');
      if (message = formElement.parentNode.getElementsByClassName('message')[0]) {
        message.remove();
      }
      formElement.parentNode.classList.add('checking');
      formElement.setAttribute('tabindex', -1);

      var request = new XMLHttpRequest();
      if (request) {
        request.onreadystatechange = function() {
          SVELTE.hijax.formField.handleResponce(formElement, request);
        };
        request.open("POST", url, true);
        request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        request.send(data);
        return true;
      }
      return false;
    },

    handleResponce: function(formElement, request)
    {
      if (request.readyState == 4) {
        switch (request.status) {
          case 200: // Ok
          case 202: // Accepted
          case 304: // Not Modified
            if (formElement.form.formType === SVELTE.FormType.UPDATE) {
              window.setTimeout(SVELTE.hijax.formField.reset, (20 *1000), formElement.id);
            }
            var formElementId = formElement.id;
            formElement.parentNode.outerHTML = request.responseText;
            formElement = document.getElementById(formElement.id);
            formElement.addEventListener('change', function(){
              SVELTE.hijax.formField.check(this);
            });
            break;
          case 400: // Bad Request
            SVELTE.hijax.formField.setError(oThis);
            oThis.outerHTML = request.responseText;
            break;
          case 302: // Found (treating same as 303)
          case 303: // See Other (redirect)

            break;
          case 201: // Created

            break;
          default: // load in lightbox

            break;
        }
      }
    },

    reset: function(formElementId)
    {
      var formElement = document.getElementById(formElementId);
      formElement.parentNode.classList.remove('checked');
      formElement.setAttribute('tabindex', 0);
    }
  };

}();
