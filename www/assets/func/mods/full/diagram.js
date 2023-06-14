/**
 * FUNC.js - Frontend Utilities for Networked Client (js).
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package func.diagram
 * @depends func.core
 * @version 0.1;
 */
var FUNC = window.FUNC || {};
FUNC.version = FUNC.version || .1;

/**
 * Build, edit and arrange common diagrams. 
 * UML (Class, Sequence, Activity, Use Case), Database (Entity Relationship)
 */
FUNC.diagram = function(elm)
{
  //- USE (dependant namespaces)
  var _ = FUNC.diagram,
      N = FUNC.Enum,
      my = FUNC.my.diagram,
      ex = FUNC.exceptions,
      Ex = FUNC.Exception,
      core = FUNC.core,

  //- SHARED PRIVATE VARABLES
      _calc = [], _draw = [], _s = [], _c = [],
      _type = elm.classList[1], _view = elm.classList[2].replace('view-', ''),
      _annotate = document.createElement('ul');

  if (elm.getElementsByClassName('canvas').length !== 1) {
    let o = document.createElement('div');
    o.setAttribute('class', 'canvas');
    elm.appendChild(o);
  }
  var _canvas = elm.getElementsByClassName('canvas')[0];
    
  //- NAMESPACE PROPERTIES
  _.types = new N('uml-class','uml-sequance','uml-usecase','uml-action','erd-database');

  // - SHARED PRIVATE DYNAMIC VARABLES
  var _allViews = function() { let o = [], i=0;
    while ( i < _.types.length) {
      if (i == 0) { o[_.types.properties[i++].name] = new N('detail','comparision','abstract','compact'); continue; }
      o[_.types.properties[i++].name] = new N('detail');
    }
    return o;
  }();
  
  //- NAMESPACE METHODS
  /**
   * Create new diagram section within #main
   * @param {string} title - Name/ID of new diagram section
   * @param {FUNC.Enum} typeN - Diagram Type (enum) of diagram section (.class-name) 
   */
  _.add = function(title, typeN) {
    core.addSection(title, [_.types.properties[typeN].name, 'view-' + _allViews[_type].properties[0].name], 'diagram');
  }

  //- LOCAL CLASSES.
  /**
   * Base class for all diagram components <<abstract>>.
   * @param {string} id - Unque identifier of corresponding HTMLElement 
   * @param {HTMLElement} e - Represention of this (Component) on DOM
   */
  const Component = class
  {
    constructor(id, e) { this.abstract('Component');
      this.type = this.constructor.name.toLocaleLowerCase();
      this.id = id; this.e = e;
    }
    get description() { return (core.sp(this.type) + core.sp(this.variant)).trim(); }
    abstract(n) { if (this.constructor.name == n) { throw Error('is Abstract Class!'); } }
    update() { this.e.setAttribute('class', this.description); }
  };

  /**
   * Representation of a generic Shape <<abstract>> .
   * @param {string} id - Unque identifier of corresponding HTMLElement 
   * @param {HTMLElement} e - Represention of this (Shape) on DOM
   */
  const Shape = class extends Component
  {
    #variant;
    constructor(id, e) { super(id, e); this.abstract('Shape');
      this.#variant = (this.e.classList && this.e.classList[1]) ? this.e.classList[1].trim() : null;
    }
    get variant() { return this.#variant; }
    makeConnections() { throw Error('is Abstract Method!'); }
  };

  /**
   * Representation of a connection from one Shape to another <<abstract>>.
   * @param {string} id - Unque identifier of corresponding HTMLElement 
   * @param {HTMLElement} e - Represention of this (Connection) on DOM
   * @param {Shape} cF - Referance for 'from' object.
   */
  const Connection = class extends Component
  {
    #to; #from;
    constructor(id, e, cF) { super(id, e); this.abstract('Connection');
      this.variant = (this.e.classList && this.e.classList[0]) ? this.e.classList[0].trim() : null;
      this.#to = _s.find((sT) => sT.id == this.e.childNodes[0].hash.replace('#', ''));
      this.#from = cF;
      this.orientation = this.e.dataset.orientation || 'h';
    }
    get to() { return this.#to; }
    get from() { return this.#from; }
  }

  /**
   * Representation of a database Entity.
   * @param {string} id - Unque identifier of corresponding HTMLElement 
   * @param {HTMLElement} e - Represention of this (Entity (db)) on DOM
   */
  const Entity = class extends Shape
  {
    constructor(id, e) { super(id, e); }
    makeConnections() { }
  };

  /**
   * Representation of Class in UML.
   * @param {string} id - Unque identifier of corresponding HTMLElement 
   * @param {HTMLElement} e - Represention of this (Class (UML)) on DOM
   */
  const Class = class extends Shape
  {
    #parent; #children = [];
    constructor(id, e) { super(id, e); }
    makeConnections() { 
      Array.prototype.slice.call(this.e.getElementsByClassName('associations')[0].getElementsByTagName('li')).forEach((e) => {
        var i = _c.length, id = (e.id == '') ? 'association-' + (i) : e.id;
        _c[i] = new Association(id, e, this);
        if (_c[i].variant == 'inheritance') { this.#parent = _c[i].to; this.#parent.addChild(_c[i].from); }
      });
    }
    get parent() { return this.#parent; }
    get children() { return this.#children; }
    addChild(v) { this.#children[this.#children.length] = v; }
  }

  /**
   * Representation of an Association in UML.
   * @param {string} id - Unque identifier of corresponding HTMLElement 
   * @param {HTMLElement} e - Represention of this (Class (UML)) on DOM
   * @param {Shape} cF - Referance for 'from' object (Class (UML)).
   */
  const Association = class extends Connection
  {
    constructor(id, e, cF) { super(id, e, cF);
      if (this.variant == 'inheritance') { this.orientation = this.e.dataset.orientation || 'v'; }
    }
    get description() { return (core.sp(super.description) + core.sp(this.orientation) + core.sp(this.direction) + core.sp(this.vflow)).trim(); }
  }

  // Prepare annotation area.
  _annotate.className = 'annotate';
  _annotate.style.top = (_canvas.offsetTop/16) + 'rem';
  _annotate.style.left = (_canvas.offsetLeft/16) + 'rem';
  _annotate.style.width = (_canvas.offsetWidth/16) + 'rem';
  _annotate.style.bottom = 0;
  elm.appendChild(_annotate);

  //- LOCAL METHODS.
  /**
   * 
   */
  var save = function() {
    alert("TODO:mrenyard: SAVE to SERVER");
  };

  var add = function(type, title) {

  };

  //- INITIALISE
  // Read and add diagram components from avalible Shapes in DOM.
  // UML (Class, Object, Actor, Task, System, Action, Swimlane, Decision, Fork, Merge) ERD (Entity)
  Array.prototype.slice.call(elm.getElementsByTagName('article')).forEach((e) => { 
    try {
      let CLASS = eval(e.classList[0].charAt(0).toUpperCase() + e.classList[0].slice(1));
      _s[_s.length] = new CLASS(e.id, e);
    } catch {
      throw new Ex(ex.UNDECLAREDCLASS, 'HtmlAttribute:class @index 1 (' + e.classList[0] + ') does NOT matche avalible Shapes')
    }
  });

  _s.forEach((s) => { s.makeConnections(); });
  // while(thiz.calculateNext()) {
  //   thiz.drawNext();
  // }

  //- PUBLIC ACCESS
  return {
    get type() { return _type; },
    get view() { return _view; },
    get views() { return _allViews[_type]; },
    get shapes() { return _s; },
    get connections() { return _c; },
    get components() { return _s.concat(_c); },
    set view(n) {
      elm.classList.replace('view-' + _view, 'view-' + _allViews[_type].properties[n].name);
      _view = _allViews[_type].properties[n].name;
    }
  };
};
/*
          setTimeout(() => {

          var rF = core.getDocRect(this.from.e), rW = core.getDocRect(this.with.e); 
          // console.log(id, rF.container);
          this.direction = this.direction || ((rF.x + (rF.width/2)) < (rW.y + (rW.width/2))) ? 'ltr' : 'rtl';
          this.vflow = this.vflow || (rF.y > rW.y) ? 'up' : 'down';
          this.start = //(this.vflow == 'up') ?
            { x:(rF.x + (rF.width/2)), y:(this.from.e.offsetTop) }; // : null;
            // { x:(rF.x + (rF.width/2)), y:(rF.y + rF.height) };
          // this.end = (this.vflow == 'up') ?
          //   { x:(rW.x + (rW.width/2)), y:(rW.y + rW.height) } : null;
            // { x:(rW.x + (rW.width/2)), y:(rW.y) };
              // var s = document.createElement('span');
              // s.setAttribute('class', 'start');
              // s.setAttribute('id', this.id + '-start');
              // s.style.left = ((this.from.e.offsetLeft + (this.from.e.offsetWidth/2)))/16 + 'rem';
              // s.style.top = (this.from.e.offsetTop-6)/16 + 'rem';
              // annotate.appendChild(s);
              // var end = document.createElement('span');
              // end.setAttribute('class', 'end');
              // end.setAttribute('id', this.id + '-end');
              this.e.style.top = ((this.with.e.offsetTop + this.with.e.offsetHeight))/16 + 'rem';
              this.e.style.left = ((this.with.e.offsetLeft + (this.with.e.offsetWidth/2)))/16 + 'rem';
              this.e.style.width = ((this.from.e.offsetLeft - (this.from.e.offsetWidth/2)) - this.with.e.offsetLeft)/16 + 'rem';
              this.e.style.height = (this.from.e.offsetTop - (this.with.e.offsetTop + this.with.e.offsetHeight))/16 + 'rem';
              annotate.appendChild(this.e);
              this.update();
            }, 500);

  /**
   * 
   * @param {*} id 
   *
  const Class = class extends Shape
  {
    // static #levels = [];
    // #sI;
    // #children = [];
    // #parent;
    constructor(id, e) { super(id, e);
      // if (Class.#levels[0] == undefined) { Class.#levels[0] = { value: 1, count: 1 }; }
      // this.siblings = { index: 0, count: 0 };
      // this.update();
    }
    // setChild(value) {
    //   this.#children[this.#children.length] = value;
    //   return this.#children.length;
    // }
    // set parent(value) {
    //   this.#parent = value;
    //   this.#sI = value.setChild(this);
    //   if (this.parent.e.dataset.level == undefined) { this.parent.e.dataset.level = 1; }
    //   this.e.dataset.level = parseInt(this.parent.e.dataset.level) + 1
    //   if (Class.#levels[parseInt(this.parent.e.dataset.level)] == undefined) {
    //     Class.#levels[parseInt(this.parent.e.dataset.level)] = { value:parseInt(this.e.dataset.level), count:0 };
    //   }
    //   Class.#levels[this.parent.e.dataset.level].count++;
    // }
    // // static get levels() { return Class.#levels; }
    // static get levelMax() {
    //   var i=0, o = Class.#levels[i], max = Class.#levels[i];
    //   do {
    //     max = (o.count > max.count) ? o : max;
    //   } while (o = Class.#levels[++i]);
    //   return max;
    // }
    // get level() { return Class.#levels[this.e.dataset.level-1]; }
    // get childrenCount() { return this.#children.length; }
    // get siblingIndex() { return (this.parent === null) ? { i:1, of:1 } : { value:this.#sI, of:this.parent.childrenCount }; }
    // get parent() { return this.#parent || null; }
  };
*/