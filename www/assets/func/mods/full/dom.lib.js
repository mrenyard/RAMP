/**
 * FUNC.js - Frontend Utilities for Networked Client (js).
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package func.dom
 * @version 0.1;
 */
var FUNC = window.FUNC || {};
FUNC.version = FUNC.version || .1;

/**
 * HTML - Document Object Manipulation (DOM) libary.
 */
FUNC.dom = function()
{
  /**
   * Create new HtmlElement:section within HtmlElement:#main
   * optionally load dynamic FUNC.[module] on it.
   * @template
   * HtmlElement:section
   *  HtmlElement:header
   *  HtmlElement:div[content]
   * @example
   * FUNC.core.addSection(
   *   'Class Model for RAMP (Model, View, controller)',
   *   (document.createElement('div')).setAttribute('class','canvas'),
   *   ['diagram', 'uml-class', 'view-detail'],
   *   FUNC.diagram
   * );
   * @param {string} title - Heading title for new diagram section (`id="attribute-name"`).
   * @param {string[]} type - Additional `[classList:values]` (excluding module).
   * @param {string} moduleName - Optional `FUNC.[moduleName]` to be executed on new `[HtmlElement:section]`.
   */
  var addSection = function(title, type, moduleName)
  {
    let i = 0, o = document.createElement('section'),
        h = document.createElement('header'),
        h2 = document.createElement('h2');
    o.setAttribute('id', title.replace(' ','-').toLocaleLowerCase());
    if (moduleName !== undefined) { o.classList.add(moduleName); }
    while (type[i]) { o.classList.add(type[i++]); }
    h2.append(title); h.appendChild(h2); o.appendChild(h);
    FUNC.domMain.appendChild(o);
    if (f = FUNC.domMain.getElementsByTagName('footer')[0]) { FUNC.domMain.appendChild(f); }
    if (moduleName !== undefined) {
      fn = eval('FUNC.' + moduleName);
      FUNC.my[moduleName][FUNC.my[moduleName].length] = fn(o);
    }
  }

  //- PUBLIC ACCESS
  return { addSection };
}();
