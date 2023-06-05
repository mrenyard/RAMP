/**
 * Author: renyard.m
 *
 * @example
 * SVELTE.event.register(elm,
 *   function(e) { // EventStart
 *   },
 *   function(t) { // EventMove
 *   },
 *   function(e, elm) { // EventEnd
 *   }
 * );
 */
var SVELTE = window.SVELTE || {};

SVELTE.event = function() {

  var _a = [], _eActive = false,
      _lT = null, _sE = null,
      _b = document.getElementsByTagName('body')[0];

  var _addListeners = function(elm, type, func) {
    if (!(elm instanceof HTMLElement)) { return false; }
    var i=0;
    while (type[i] != null) {
      elm.addEventListener(type[i], function(e) {
        var touchX = ((e.touches) && e.touches[0])? e.touches[0].pageX:
          ((e.changedTouches) && (e.changedTouches[0]))? e.changedTouches[0].pageX: e.pageX;
        var touchY = ((e.touches) && e.touches[0])? e.touches[0].pageY :
          ((e.changedTouches) && e.changedTouches[0])? e.changedTouches[0].pageY : e.pageY;

        _lT = document.elementFromPoint(touchX, touchY);
        func(e);
      }, true);
      i++;
    }
    return true;
  };

  _addListeners(_b, {0:'touchend',1:'mouseup'}, function(e) {
    _eActive = false;
    var t = _sE;
    while (t) { for (var i=0, j=_a.length; i < j; i++) {
      var a = _a[i];
      if (t == a.startElement) {
        if (a.endAction) { a.endAction(e, _lT); }
        _lT = null;
        return;
      }
    } t = t.parentNode; }
    _sE = null;
  });

  _addListeners(_b, {0:'touchmove',1:'mousemove'}, function(e) {
    if (_eActive) {
      var t = _sE;
      while (t) { for (var i=0, j=_a.length; i < j; i++) {
        var a = _a[i];
        if (t == a.startElement) {
          if (a.moveAction) { a.moveAction(_lT); }
          return;
        }
      } t = t.parentNode; }
    }
  });

  var _register = function(sE, sA, mA, eA) {
    _a[_a.length] = { 'startElement':sE, 'startAction':sA, 'moveAction':mA, 'endAction':eA};
    _addListeners(sE, {0:'touchstart',1:'mousedown'}, function(e) {
      _eActive = true; _lT = sE; _sE = sE;
      var t = _sE;
      while (t) { for (var i=0, j=_a.length; i < j; i++) {
        var a = _a[i];
        if (t == a.startElement) {
          if (a.startAction) { a.startAction(e, a.startElement); }
          return;
        }
      } t = t.parentNode; }
    });
  };

  return {
    register: _register
  };
}();
