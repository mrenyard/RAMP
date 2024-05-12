/**
 * FUNC.js - Frontend Utilities for Networked Client (js).
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package func.drag
 * @depends funct.dom
 * @depends funct.event 
 * @version 0.1;
 * @example
 * FUNC.init.register('sort', 'sort', ['event']);
 */
var FUNC = window.FUNC || {};
FUNC.version = FUNC.version || .1;

/**
 * [Description of module]. 
 * @example .html
 * <section class="sort">
 *   ...
 * </section>
 */
FUNC.drag = function(elm)
{
  //- USE (dependant namespaces)
  var List = FUNC.Collection,
      dom = FUNC.dom,
      evt = FUNC.event;

  //- SHARED PRIVATE VARABLES
  var items = elm.getElementsByClassName('draggable'),
    dropZones = List.createFrom(elm.getElementsByClassName('dropzone'));

  //- INITIALISE
  for (let i=0, j=items.length; i<j; i++) { let o = items[i];
    evt.dragNdrop(o, dropZones,
      function(targetZone, activeElement) {
        console.log('DROPED: ' + activeElement.id + ' in: ' + targetZone.id);
        console.group('ORDERED:');
        targetZone.childNodes.forEach((o) => {
          if (o.nodeType == 1) { console.log(o); }
        });
        console.groupEnd();
      }
    );
  }

  return { items, dropZones };
};