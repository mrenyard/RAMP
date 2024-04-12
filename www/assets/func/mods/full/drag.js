/**
 * FUNC.js - Frontend Utilities for Networked Client (js).
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package func.sort
 * @depends funct.event 
 * @version 0.1;
 * @example
 * FUNC.init.register('sort', 'FUNC.sort', ['core']);
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
      evt = FUNC.event;

  //- SHARED PRIVATE VARABLES
  var dragElms = elm.getElementsByClassName('draggable'),
    dropList = List.createFrom(elm.getElementsByClassName('dropzone'));

  //- INITIALISE
  for (let i=0, j=dragElms.length; i<j; i++) { let o = dragElms[i];
    evt.dragNdrop(o, dropList,
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
};