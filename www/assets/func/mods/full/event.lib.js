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
FUNC.event = function(elm)
{
  //- USE (dependant namespaces)
  var _ = FUNC.event,
      N = FUNC.Enum,
      Ex = FUNC.Exception,
      List = FUNC.Collection;

  //- SHARED PRIVATE VARABLES
  var actions = [], eActive = false,
    lastTouch, moving, target,
    // Set pixels per inch (PPI)
    ppi = (function() {
      let elm = document.body.appendChild(document.createElement('div'));
      elm.style.width = '1in'; elm.style.padding = '0';
      let ppi = elm.offsetWidth;
      elm.parentNode.removeChild(elm);
      return ppi;
    }());
    
  //- LOCAL METHODS.
  /**
   * Registers a minmum 1/4" move.
   * @param {event} e event.
   * @returns {boolean} true|false.
   */
  var touchMove = function(e)
  {
    var m = (ppi * 0.25);
    return ((e.X>(startX+m))||(e.X<(startX-m))||(e.Y>(startY+m))||(e.Y<(startY-m)));
  };

  /**
   * Returns any relevant dropzone under x,y cords.
   * @param {HTMLElement} activeElement
   * @param {FUNC.Collection} dropZones 
   * @param {int} x X coordinate.
   * @param {int} y Y coordinate.
   * @returns {HTMLElement|null} dropzone or null where relevant.
   */
  var getActiveDropzone = function(zones, x, y)
  {
    y = (y - window.scrollY); // adjust for scroll position.
    zones.rewind();
    while(zones.next) { let o = zones.current
      let zR = o.getBoundingClientRect();
      if ((x > zR.left && x < zR.right) && (y > zR.top && y < zR.bottom)) {
        let s = o.firstChild;
        if (s.tagName = 'A') { s = s.nextElementSibling; }
        while(s) {
          let iR = s.getBoundingClientRect();
          if ((x > iR.left && x < iR.right) && (y > iR.top && y < iR.bottom)) { break; }
          s = s.nextElementSibling;
        }
        return {'zone':o, 'sibling':s};
      }
    }
    return null;
  };

  /**
   * 
   * @param {HTMLElement} elm Element which to add eventListener. 
   * @param {{n:string, n:string}} type Type of event trigers.
   * @param {function} func Action to enact. 
   * @returns {boolean} success.
   */
  var addListeners = function(elm, type, func)
  {
    if (!(elm instanceof HTMLElement)) { return false; }
    let i=0;
    while (type[i] != null)
    {
      elm.addEventListener(type[i], function(e)
      {
        e.X = ((e.touches) && e.touches[0])? e.touches[0].pageX:
          ((e.changedTouches) && (e.changedTouches[0]))? e.changedTouches[0].pageX: e.pageX;
        e.Y = ((e.touches) && e.touches[0])? e.touches[0].pageY :
          ((e.changedTouches) && e.changedTouches[0])? e.changedTouches[0].pageY : e.pageY;
        func(e);
      }, true);
      i++;
    }
    return true;
  };

  /**
   * 
   * @param {HtmlElement} startElement 
   * @param {function(e, activeElement)} startAction 
   * @param {function(e, activeElement)} moveAction 
   * @param {function(e, activeElement)} endAction 
   */
  var register = function(startElement, startAction, moveAction, endAction)
  {
    actions[actions.length] = {'startElement':startElement, 'startAction':startAction, 'moveAction':moveAction, 'endAction':endAction};
    addListeners(startElement, {0:'mousedown', 1:'touchstart'}, function(e)
    {
      e.preventDefault();
      if (e.touches && e.touches.length !== 1) { return; }
      let t = e.target;
      while(t) { for (let i=0, j=actions.length; i<j; i++) { let a = actions[i];
        if (t == a.startElement) {
          startX = e.X; startY = e.Y;
          if (a.startAction) {
            lastTouch = a.startElement;
            a.startAction(e, a.startElement);
            eActive = true;
        }
          return;
        }
      } t = t.parentNode; }
    });
  };

  var dragNdrop = function(draggableElement, dropZones, dropHandler, clickEvent = null)
  {
    let snap = null, oX, oY;
    draggableElement.draggable = false;
    if (!(dropZones instanceof List)) {
      throw new Ex(Ex.types.at('UnexpectedArgument'), 'Argument MUST be instanceof FUNC.Collection');
    }
    if (clickEvent) { draggableElement.addEventListener('click', clickEvent); }
    register(draggableElement,
      function(e, activeElement) // EventStart
      {
        e.stopPropagation();
        snap = window.setTimeout(function()
        {
          e.stopImmediatePropagation();
          oY = (activeElement.clientHeight/2);
          oX = (activeElement.clientWidth/2);
          moving = activeElement.cloneNode(true);
          moving.id = 'moving';
          moving.style.zIndex = '2147483583';
          moving.style.position = 'fixed';
          moving.style.top = (e.Y - window.scrollY - oY) + 'px';
          moving.style.left = (e.X - oX) + 'px';
          activeElement.draggable = true;
          activeElement.classList.add('placeholder');
          activeElement.parentNode.appendChild(moving);
          window.clearTimeout(snap); snap = null;
        }, (.25 * 1000));
      },
      function(e, activeElement) // EventMove
      {
        e.stopPropagation();
        if (!target || target.tagName != activeElement.tagName) {
          target = document.createElement(activeElement.tagName);          
          target.setAttribute('id', 'target');
        }
        target.classList = activeElement.classList;
        target.classList.replace('placeholder', 'target');
        if (touchMove(e)) {
          if (activeElement.draggable) {
            e.stopImmediatePropagation();
            if (snap != null) { window.clearTimeout(snap); snap = null; }
            let o = getActiveDropzone(dropZones, e.X, e.Y);
            if (o != null) {
              if (o.sibling === null) {
                o.zone.appendChild(target);
              } else {
                o.sibling.before(target);
              }
            } else if (document.getElementById('target') !== null) {
              target.parentNode.removeChild(target);
            }
            if (activeElement.draggable) {
              moving.style.top = (e.Y - window.scrollY - oY) + 'px';
              moving.style.left = (e.X - oX) + 'px';
            }
          }
        }
      },
      function(e, activeElement) // EventEnd
      {
        e.stopPropagation();
        if (snap != null) { window.clearTimeout(snap); snap = null; }
        if (activeElement.draggable) {
          e.stopImmediatePropagation();
          let o = getActiveDropzone(dropZones, e.X, e.Y);
          let space = ((s = activeElement.nextSibling) && s.nodeType == 3) ? activeElement.nextSibling : null;
          if (o !== null) {
            if (o.sibling === null) {
              o.zone.appendChild(activeElement);
            } else {
              o.sibling.before(activeElement);
            }
          }
          if (space) { activeElement.after(space); }
          if (document.getElementById('target') !== null) {
            target.parentNode.removeChild(target);
          }
          activeElement.classList.remove('placeholder');
          activeElement.draggable = false;
          moving.parentNode.removeChild(moving);
          if (o != null || activeElement == null) { dropHandler(o.zone, activeElement); }
        } else if (clickEvent) { clickEvent(e); }
      }
    );
  };

  //- INITIALISE
  addListeners(document.body, {0:'mouseup', 1:'touchend'}, function(e)
  {
    let t = lastTouch;
    while (t) { for(let i=0, j=actions.length; i<j; i++) { a = actions[i];
      if (t == a.startElement) {
        if (a.endAction) {
          a.endAction(e, t, lastTouch);
          eActive = false;
          lastTouch = null;
          return;
        }
      }
    } t = t.parentNode; }
  });

  addListeners(document.body, {0:'mousemove', 1:'touchmove'}, function(e)
  {
    if (eActive) {
      let t = lastTouch;
      while(t) { for (let i=0, j=actions.length; i<j; i++) { a = actions[i];
        if (t == a.startElement) {
          if (a.moveAction) { a.moveAction(e, a.startElement, lastTouch); }
          return;
        }
      } t = t.parentNode; }
    }
  })

  //- PUBLIC ACCESS
  return {addListeners, register, dragNdrop, getActiveDropzone};
}();