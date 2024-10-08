/**
 * FUNC.js - Frontend Utilities for Networked Client (js).
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package func, func.init
 * @version 0.1;
 */
var FUNC = window.FUNC || {};
FUNC.version = FUNC.version || .1;

/**
 * Base Class <<abstract>>.
 */
FUNC.Base = class
{
  abstract(s) {
    if (eval(this[s]) !== undefined) { throw new Error('\"' + s + '()" METHOD' + ' is Abstract!'); }
    if (this.constructor.name == s) { throw new Error('":' + s + '" CLASS is Abstract!'); }
  }
  constructor() { this.abstract('Base'); }
};

/**
 * Enum Constructor.
 * Creates new Enum from provided arguments.
 * @example pets = new Enum('Cat', 'Dog', 'Fish', 'Hamster', 'Cat Fish');
 */
FUNC.Enum = class extends FUNC.Base
{
  #properties;
  constructor() { super();
    this.#properties = [];
    for (let i=0, j=arguments.length; i < j; i++) {
      let _itm = arguments[i];
      this[_itm.replace('-','_').replace(' ','_').toUpperCase()] = i;
      this.#properties[i] = { name:_itm, value:i };
    } 
  }
  at(v) { return (isNaN(v)) ? this.#properties.find((o) => o.name == v) : this.#properties[v]; }
  get count() { return this.#properties.length; }
}

/**
 * Exception. 
 * @param {number} typeN 
 * @param {string} message 
 */
FUNC.Exception = class extends FUNC.Base
{
  static #n;
  static get types() {
    this.#n = this.#n || new FUNC.Enum(
      'BadMethodCall',
      'OutOfBounds',
      'UnexpectedArgument',
      'UndeclaredClass'
    );
    return this.#n;
  }
  #typeN;
  #message;
  constructor(typeN, message) { super();
    this.#typeN = typeN;
    this.#message = message;
  }
  toString() { return FUNC.Exception.types.at(this.#typeN).name + 'Exception: ' + this.#message; }
};

/**
 * Iterable Collection.
 */
FUNC.Collection = class extends FUNC.Base
{
  /**
   * Create from 
   * @param {HtmlCollection} elms 
   * @returns {Collection}
   */
  static createFrom(elms) {
    let o = new FUNC.Collection();
    for (let i=0,j=elms.length; i<j; i++) { o.add(elms[i]); }
    return o;
  }
  #idList = [];
  #currentIndex = -1;  
  get length() { return this.#idList.length; }
  get current() { return this[this.#idList[this.#currentIndex]]; }
  get next() {
    if (this.length == 0) { throw new FUNC.Exception(FUNC.Exception.OUTOFBOUNDS, 'length MUST be greater then 0.'); }
    return (this.#currentIndex++ < (this.length-1));
  }
  rewind() { this.#currentIndex = -1; }
  add(el) {
    if (this[el.id] == null) { this.#idList[this.#idList.length] = el.id; }
    this[el.id] = el;
  }
  updateFrom(elms) { for (let i=0, j=elms.length; i<j; i++) { this.add(elms[i]); } }
}

/**
 * FUNC.init, Mechanism to register added functionality to a
 * HTMLCollection (through a common .class-name) with a particular
 * FUNC modual. Registered Collections added to DOM under FUNC.my.modual[i];
 * @example ...
 *     <script src="/assets/func/init.js"></script>
 *     <script defer>
 * FUNC.init.register('class-name', FUNC.modual);
 * FUNC.init.run();
 *     </script>
 *   </body>
 * </html>
 */
FUNC.init = function()
{
  //- LOCAL VARIBLES
  var inits = [], dList = [], mList = [],
      libsLock, lock, dev = (window.location.hostname.split('.')[0] == 'dev');

  //- GLOBAL OBJECT
  FUNC.my = FUNC.my || {};
  FUNC.modsPath = FUNC.modsPath || '/assets/func/mods/' + ((dev) ? 'full/' : '');

  //- LOCAL METHODS
  var loadLibs = function(ds)
  {
    for (let i=0, j=ds.length; i<j; i++) { let d = ds[i]
      if (dList[d] == undefined) {
        let o = document.createElement('script');
        o.setAttribute('id', 'script-' + d)
        o.setAttribute('src', FUNC.modsPath + d + '.lib.js')
        document.body.append(o);
        dList[d] = true;
      }
    }
  }

  var loadModule = function(mN)
  {
    if (libsLock) { return setTimeout(function() { loadModule(mN); }, 100); }
    if (mList[mN] == undefined) {
      let o = document.createElement('script');
      o.setAttribute('id', 'script-' + mN)
      o.setAttribute('src', FUNC.modsPath + mN + '.js')
      document.body.append(o);
      mList[mN] = true;
    }
  }

  /**
   * Register potentual FUNC modules needed site wide, only loads those scripts actually need per page.
   * @param {srting} {qS: querySelector:string} - Identifing HtmlClass:name for modual use. 
   * @param {string} {mN: {moduleName]} Modual name to be exacuted against each relevant HtmlEntity (fragment).
   * @param {string[]} {ds: dependencies} - FUNC[library][:array] List of dependent library names ordered in loading propriety.
   */
  var register = function(qS, mN, ds)
  {
    let e = document.querySelectorAll(qS);
    if (e.length > 0) {
      lock = true;
      libsLock = true;
      if (ds !== undefined) {
        loadLibs(ds);
        (function libsExist() {
          for (let i=0, j=ds.length; i<j; i++) { let d = ds[i];
            if (eval('FUNC.' + d) == undefined) { return setTimeout(libsExist, 100); }
          }
          libsLock = false;
        }());
      }
      loadModule(mN);
      (function modExists() {
        if (eval('FUNC.' + mN) == undefined) { return setTimeout(modExists, 100); }  
        inits[inits.length] = { name: mN, instances: e, action: eval('FUNC.' + mN) };
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
        let insts = Array.prototype.slice.call(init.instances), j = 0;
        insts.forEach((inst) => {
          FUNC.my[init.name][j++] = init.action(inst);
        });
    });
  };

  //- PUBLIC ACCESS
  return { register, run };
}();
