/**
 * FUNC.js - Frontend Utilities for Networked Client (js).
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package func.core
 * @version 0.1;
 */
var FUNC = window.FUNC || {};
FUNC.version = FUNC.version || .1;

/**
 * Enum Constructor.
 * Creates new Enum from provided arguments.
 * @example pets = new Enum('Cat', 'Dog', 'Fish', 'Hamster', 'Cat Fish');
 */
FUNC.Enum = function() {
  this.properties = {};
  for (var i=0, j=arguments.length; i < j; i++) {
    var _itm = arguments[i];
    this[_itm.replace('-','_').replace(' ','_').toUpperCase()] = i;
    this.properties[i] = { name:_itm, value:i };
  }
  this.length = i;
};

/**
 * Exception Super Class and Type Enum
 */
FUNC.exceptions = new FUNC.Enum('BadMethodCall', 'OutOfBounds', 'UnexpectedArgument', 'UndeclaredClass');
/**
 * 
 * @param {*} type 
 * @param {*} message 
 */
FUNC.Exception = function(type, message, e) {
  this.toString = function() { return FUNC.exceptions.properties[type].name + 'Exception: ' + message; }
};

/**
 * Namespace for core libary.
 */
FUNC.core = function()
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
   * @param {string} title - Heading title for new diagram section (= id attribute-name).
   * @param {sting[]} type - Additional classList values beyond module.
   * @param {string} moduleName - Optional FUNC.[moduleName] to be executed on new HtmlElement:section.
   */
  var addSection = function(title, type, moduleName)
  {
    let i = 0, o = document.createElement('section'),
        h = document.createElement('header'),
        h3 = document.createElement('h3');
    o.setAttribute('id', title.replace(' ','-').toLocaleLowerCase());
    o.classList.add(moduleName);
    while (type[i]) { o.classList.add(type[i++]); }
    h3.append(title); h.appendChild(h3); o.appendChild(h);
    FUNC.domMain.appendChild(o);
    if (moduleName !== undefined) {
    fn = eval('FUNC.' + moduleName);
    FUNC.my[moduleName][FUNC.my[moduleName].length] = fn(o);
    }
  }

  /**
   * Appends a space to non trivial strings.
   * @param {string} v - Value to nominally append space 
   * @returns String value with required appended space.
   */
  var sp = function(v) { return (v === null || v === '' || v === 'undefined' ) ? '' : v + ' '; };

  //- PUBLIC ACCESS
  return { 
    addSection,
    sp
  };
}();

/**
 * FUNC.init, Mechanism to register added functionality to a
 * HTMLCollection (through a common .class-name) with a particular
 * FUNC package. Registered Collections added to DOM under FUNC.my.package[i];
 * @example FUNC.init.register('class-name', FUNC.package);
 * @example ...
 *     <script src="/assets/func/svelte/core.js"></script>
 *     <script>FUNC.init.run();</script>
 *   </body>
 * </html>
 */
FUNC.init = function()
{
  //- LOCAL VARIBLES
  var i=0, inits = [], lock = true,
      dev = (window.location.hostname.split('.')[0] == 'dev');

  //- GLOBAL OBJECT
  FUNC.my = FUNC.my || {};
  FUNC.domMain = document.getElementById('main');
  FUNC.modsPath = FUNC.modsPath || '/assets/func/mods/' + ((dev) ? 'full/' : '');

  //- LOCAL METHODS
  /**
   * Register potentual FUNC modules needed site wide, only loads those scripts actually need per page
   * @param {srting} className -
   * @param {string} fn -  
   */
  var register = function(className, fn) {
    var e = document.getElementsByClassName(className)
    if (e.length > 0) {
        lock = true;
        var o = document.createElement('script');
        o.setAttribute('id', 'script-' + className)
        o.setAttribute('src', FUNC.modsPath + className + '.js')
        document.body.append(o);
        (function exists() {
          if (eval(fn) == undefined) { return setTimeout(exists, 100); }  
          inits[i++] = { name: className, instances: e, action: eval(fn) };
          lock = false;
        }());
    }
  };

  /**
   * 
   */
  var run = function() 
  {
    if (lock) { return setTimeout(run, 100); }
    inits.forEach((init) => {
        FUNC.my[init.name] = [];
        var insts = Array.prototype.slice.call(init.instances), j = 0;
        insts.forEach((inst) => {
          try {
            FUNC.my[init.name][j++] = init.action(inst);
          } catch (e) {
            if (dev) { console.error(e.toString()); }
          }
        });
    });
  };

  //- PUBLIC ACCESS
  return { register, run };
}();

/**
 * Create a Collection of Objects.
 * Uses provided constructor to create Objects based on corresponding
 * symantic class names within the Document Object Model
 */
FUNC.createCollection = function(className, factory)
{
  var thiz = {}, _idList = [], _currentIndex = -1;

  thiz.type = 'FUNC.Collection';
  thiz.referers = [];
  thiz.count = function() {
    return _idList.length;
  };

  thiz.current = function() {
    return thiz[_idList[_currentIndex]];
  };

  thiz.next = function() {
    if (thiz.count() == 0) {
      throw new FUNC.Exception(
        FUNC.exceptions.OUT_OF_BOUNDS, 'Please check count() > 0 (line 63 cvt/core.js)'
      );
    }
    return (_currentIndex++ < (thiz.count()-1));
  };

  thiz.rewind = function() {
    _currentIndex = -1;
  };

  thiz.add = function(o) {
    if (thiz[o.el[className + 'id']] == null) { _idList[_idList.length] = o.el[className + 'id']; }
    thiz[o.el[className + 'id']] = o;
  };

  thiz.updateFromDom = function(elms) {
    var id, el, hasData;
    for (var i=0,j=elms.length; i<j; i++) { el = elms[i];
      hasData = el.hasAttribute('data-' + className + 'id');
      if ((hasData) || (el.hasAttribute('id') && el.id.split(':')[0] == className))
      {
        el[className + 'id'] = (hasData)? el.getAttribute('data-' + className + 'id') : el.id.split(':')[1];
        thiz.add(factory(el));
      } else if (el.hasAttribute('href')) {
        id = (el.href.split('#')[1]).split(':')[1];
        thiz.referers[thiz.referers.length] = el;
      }
    }
  };

  return thiz;
};
