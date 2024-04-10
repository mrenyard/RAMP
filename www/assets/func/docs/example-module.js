/**
 * FUNC.js - Frontend Utilities for Networked Client (js).
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package func.moduleName
 * @depends func.lib1, func.lib2 
 * @version 0.1;
 * @example
 * FUNC.init.register('moduleName', 'FUNC.module', ['lib1', 'lib2]);
 */
var FUNC = window.FUNC || {};
FUNC.version = FUNC.version || .1;

/**
 * [Description of module]. 
 * @example .html
 * <section class="moduleName">
 *   ...
 * </section>
 */
FUNC.moduleName = function(elm)
{
  //- USE (dependant namespaces)
  var _ = FUNC.module,
      N = FUNC.Enum,
      Ex = FUNC.Exception,
      lib1 = FUNC.lib1;

  //- NAMESPACE PROPERTIES
  _.property = '...';

  //- SHARED PRIVATE VARABLES
  var _varOne = [], _varTwo;

  //- NAMESPACE METHODS
  _.method = function(param1, param2) {
    // do something.   
  };

  //- LOCAL CLASSES.
  const ClassName = class extends FUNC.Base
  {
    #privateProperty;
    constructor() { super(); this.abstract('className');
      this.#privateProperty = [];
    }
    get property() { return this.#privateProperty; }
    set property(v) { this.#privateProperty = v; }
    method() {
      // ...do something;
    }
  };

  //- LOCAL METHODS.
  var method = function() {
    // ...do something
  };

  //- INITIALISE
  // [code to run against provided 'elm'].

  //- PUBLIC ACCESS, (.my)
  return {
    method,
    get property() { return _property},
    set property(v) { _property = v; }
  };
};