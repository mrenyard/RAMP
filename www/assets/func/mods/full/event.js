/**
 * FUNC.js - Frontend Utilities for Networked Client (js).
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package func.event
 * @version 0.1;
 */
var FUNC = window.FUNC || {};
FUNC.version = FUNC.version || .1;

FUNC.event = function()
{
  var _a = [], _eActive = false,
      _lT = null, _sE = null, _sX, _sY,
      _b = document.getElementsByTagName('body')[0];

  var _ppi = function()
  {
    var elm = document.body.appendChild(document.createElement('DIV'));
    elm.style.width = '1in'; elm.style.padding = '0';
    var ppi = elm.offsetWidth;
    elm.parentNode.removeChild(elm);
    return ppi;
  }();

  var _touchMove = function(e)
  {
    var m = (_ppi * 0.25);
    return ((e.X>(_sX+m))||(e.X<(_sX-m))||(e.Y>(_sY+m))||(e.Y<(_sY-m)));
  };

  var _addListeners = function(elm, type, func)
  {
    if (!(elm instanceof HTMLElement)) { return false; }
    var i=0;
    while (type[i] != null)
    {
      elm.addEventListener(type[i], function(e)
      {
        e.X = ((e.touches) && e.touches[0])? e.touches[0].pageX:
          ((e.changedTouches) && (e.changedTouches[0]))? e.changedTouches[0].pageX: e.pageX;
        e.Y = ((e.touches) && e.touches[0])? e.touches[0].pageY :
          ((e.changedTouches) && e.changedTouches[0])? e.changedTouches[0].pageY : e.pageY;
        _lT = document.elementFromPoint(e.X, e.Y);
        func(e);
      }, true);
      i++;
    }
    return true;
  };

  _addListeners(_b, {0:'touchend',1:'mouseup'}, function(e)
  {
    _eActive = false;
    var t = _sE;
    while (t) { for (var i=0, j=_a.length; i < j; i++)
    {
      var a = _a[i];
      if (t == a.startElement)
      {
        if (a.endAction) { a.endAction(e, t, _lT); }
        _lT = null;
      }
    } t = t.parentNode; }
    _sE = null;
  });

  _addListeners(_b, {0:'touchmove',1:'mousemove'}, function(e)
  {
    if (_eActive)
    {
      var t = _sE;
      while (t) { for (var i=0, j=_a.length; i < j; i++)
      {
        var a = _a[i];
        if (t == a.startElement) { if (a.moveAction) { a.moveAction(e, t, _lT); } return; }
      } t = t.parentNode; }
    }
  });

  /**
   * FUNC.event.register(startElement, startAction, moveAction, endAction);
   * @example
   * FUNC.event.register(startElement,
   *   function(e, activeElement) { // startAction
   *     ...
   *   },
   *   function(e, activeElement, touch) { // moveAction
   *     ...
   *   },
   *   function(e, activeElement, touch) { // endAction
   *     ...
   *   }
   * );
   */
  var _register = function(sE, sA, mA, eA)
  {
    _a[_a.length] = { 'startElement':sE, 'startAction':sA, 'moveAction':mA, 'endAction':eA};
    _addListeners(sE, {0:'touchstart',1:'mousedown'}, function(e)
    {
      _eActive = true; _lT = sE; _sE = sE;
      var t = _sE;
      while (t) { for (var i=0, j=_a.length; i < j; i++)
      {
        var a = _a[i];
        if (t == a.startElement)
        {
          _sX = e.X; _sY = e.Y;
          if (a.startAction) { a.startAction(e, t); }
          return;
        }
      } t = t.parentNode; }
    });
  };

  /**
   * FUNC.event.dragNdrop(dragableElement, dropZones, dropHandler);
   * @example
   * FUNC.event.dragNdrop(dragableElement, [FUNC.Collection],
   *   function(targetZone, activeElement) { // dropHandler
   *     ...
   *   }
   * );
   */
  var _dragNdrop = function(dE, dZs, dH)
  {
    var _snap = null;
    dE.draggable = false;
    if (dZs.type != 'FUNC.Collection')
    {
      throw new FUNC.Exception(FUNC.exceptions.UNEXPECTEDARGUMENT, 'Argument MUST be FUNC.Collection');
    }
    FUNC.event.register(dE,
      function(e, activeElement) // EventStart
      {
        _snap = window.setTimeout(function()
        {
          e.stopPropagation();
          activeElement.draggable = true;
          activeElement.style.zIndex = '2147483583';
          activeElement.style.position = 'fixed';
          activeElement.style.top = (e.Y) + 'px';
          activeElement.style.left = (e.X) + 'px';
          activeElement.classList.add('dragging');
          window.clearTimeout(_snap); _snap = null;
        }, (.5 * 1000));
      },
      function(e, activeElement) // EventMove
      {
        if (_touchMove(e))
        {
          e.stopPropagation();
          if (_snap != null) { window.clearTimeout(_snap); _snap = null; }
          if (activeElement.draggable)
          {
            activeElement.style.top = (e.Y) + 'px';
            activeElement.style.left = (e.X) + 'px';
          }
        }
      },
      function(e, activeElement) // EventEnd
      {
        if (_snap != null) { window.clearTimeout(_snap); _snap = null; }
        if (activeElement.draggable)
        {
          activeElement.draggable = false;
          activeElement.removeAttribute('style');
          activeElement.classList.remove('dragging');
          var dZ = document.elementFromPoint(e.X, e.Y);
          while (dZ)
          {
            if (dZs.count() > 0) { while (dZs.next())
            {
              var _dZ = dZs.current();
              if (dZ == _dZ.el)
              {
                e.stopPropagation();
                dH(dZ, activeElement);
                return;
              }
            } dZs.rewind(); }
            dZ = dZ.parentNode;
          }
        }
      }
    );
  };

  return {
    register: _register,
    dragNdrop: _dragNdrop,
    touchMove: _touchMove
  };
}();
