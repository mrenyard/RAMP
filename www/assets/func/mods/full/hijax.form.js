/**
 * FUNC.js - Frontend Utilities for Networked Client (JavaScript).
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package func.hijax.form
 * @version 0.1;
 */
var FUNC = window.FUNC || {};
FUNC.version = FUNC.version || .1;

FUNC.hijax.form = function() {

  var _type = { 'CREATE':0, 'UPDATE':1, 'FILTER':3 };

  var _field = {

    check: function(el)
    {
      el.addEventListener('change', function(){
        var message,
            elId = this.getAttribute('id'),
            form = this.form,
            url  = '/' + (elId.replace(/:/g, '/')),
            data = elId + '=' + this.value,
            request = new XMLHttpRequest();
        if (request) {

          request.onreadystatechange = function() {
            var el;
            if (el = FUNC.hijax.handleResponse(request)) {

              var form = el.form;
              switch (form.formType) {
                case 0: // CREATE
                  if (form.getElementsByClassName('checked').length == (form.length-1)) {
                    sbmt = form.elements[form.elements.length-1];
                    sbmt.value = 'Confirm & Create';
                  }
                  break;
                case 1: // UPDATE
                  window.setTimeout(FUNC.hijax.form.field.reset, (10 *1000), el);
                  break;
              }
              FUNC.hijax.form.field.check(el);
            }
          };

          request.open(form.method.toUpperCase(), url, true);
          request.setRequestHeader('Content-Type', form.enctype);
          request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
          request.send(data);

          this.parentNode.classList.remove('error');
          this.parentNode.classList.remove('checked');
          if (message = this.parentNode.getElementsByClassName('message')[0]) {
            message.parentNode.removeChild(message);
          }
          this.parentNode.classList.add('checking');
          this.setAttribute('tabindex', '-1');
        }
      });

      el.addEventListener('blur', function(){ FUNC.hijax.form.field.next(this); });

    },

    reset: function(el)
    {
      el.parentNode.classList.remove('checked');
      el.setAttribute('tabindex', '0');
    },

    next: function(el)
    {
      var form = el.form, errors = form.getElementsByClassName('error');
      if (errors && errors.length > 0) { errors[0].focus(); }
    }

  };

  var _init = function(regex) {
    var i=0,f,reg=/:new/;
    while (f = document.forms[i++]) {
      // f.setAttribute('novalidate');
      // set formType based on form.element.id
console.log(regex, 'from form')
      if (regex == undefined) { f.formType = ((reg.test(f.id)))? _type.CREATE : _type.UPDATE; }
console.log(f.formType, 'formtype');
console.log(f.id);
console.log(regex.test(f.id), 'regex.test(f.id)');
      // loop form.elements
      var j=0, fel;
      while (fel = f.elements[j++]) {
        switch (fel.type) {
          case 'fieldset':
            continue; // next formElement
          case 'submit':
            if (f.formType === _type.CREATE) {
              fel.value = 'Create';
              f.action = '/' + (f.id.replace(/:/g, '/'));
            }
            // full throught!
          case 'reset':
            if (f.formType === _type.UPDATE || (regex && regex.test(f.id))) {

              fel.parentNode.removeChild(fel); continue;
            }
            break;
          default:
            window.setTimeout(_field.reset, (2 *1000), fel);
            // add default focus event
            if (f.formType == _type.CREATE) {
              fel.addEventListener('focus', function(){
                if (this.className != 'populated') { this.className = 'populated'; }
              });
            }
            _field.check(fel);
            break;
        } // END switch
        fel.addEventListener('blur', function(){ _field.next(this); });
      } // END while form.elements
    } // END while forms
  };

  return {
    init: _init,
    type: _type,
    field: _field
  };
}();
