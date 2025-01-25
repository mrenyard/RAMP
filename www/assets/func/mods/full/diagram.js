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
      Ex = FUNC.Exception,
      core = FUNC.core;

  //- NAMESPACE PROPERTIES
  _.types = new N('uml-class','uml-sequance','uml-usecase','uml-action','erd-database');      

  //- SHARED PRIVATE VARABLES
  var _diagram, _s = [], _c = [], 
      _allViews = {
        'uml-class': new N('detail','comparison','abstract','compact'),
        'uml-sequance': new N('detail'),
        'uml-usecase': new N('detail'),
        'uml-action': new N('general', 'swinlane'),
        'erd-database': new N('detail')
      },
      _allShapes = {
        'uml-class': new N('class'),
        'uml-sequance': new N('Object', 'Activation', 'Actor'), // Fragment('alt', 'opt', 'par', 'loop', 'region', 'neg', 'ref', 'sd');
        'uml-usecase': new N('Actor', 'Task', 'Boundary'),
        'uml-action': new N('Action', 'Decision', 'Fork', 'Merge', 'start', 'end'),
        'erd-database': new N('Entity')
      },
      _type = _.types.at(elm.classList[1]),
      _view = _allViews[_type.name].at(elm.classList[2].replace('view-', '')),
      _annotate = document.createElement('ul');

  if (elm.getElementsByClassName('sheet').length !== 1) {
    let o = document.createElement('div');
    o.setAttribute('class', 'sheet');
    elm.appendChild(o);
  }
  var _sheet = elm.getElementsByClassName('sheet')[0];

  // Prepare annotation area.
  _annotate.className = 'annotate';
  _annotate.style.top = (_sheet.offsetTop/16) + 'rem';
  _annotate.style.left = (_sheet.offsetLeft/16) + 'rem';
  _annotate.style.width = (_sheet.offsetWidth/16) + 'rem';
  _annotate.style.bottom = 0;
  elm.appendChild(_annotate);

  //- NAMESPACE METHODS
  /**
   * Create new diagram section within #main
   * @param {FUNC.Enum} typeN - Diagram Type (enum) of diagram section (.class-name) 
   * @param {string} title - Name/ID of new diagram section
   */
  _.add = function(typeN, title) {
    core.addSection(title, [_.types.at(typeN).name, 'view-' + _allViews[_type.name].at(0).name], 'diagram');
  };

  //- LOCAL CLASSES.
  const Diagram = class extends FUNC.Base
  {
    #i = 0; #draw;
    constructor() { super(); this.abstract('Diagram');
      this.#draw = [];
      this.updateDraw();
    }
    get hasDrawNext() { return (this.#i < this.#draw.length); }
    set draw(v) { this.#draw = v; }
    drawReset() { this.#i = 0; }
    drawNext() { this.#draw[this.#i++](); }
    updateDraw() { throw core.abstract(); }
  };

  const ClassDiagram = class extends Diagram
  {
    #j = 0;
    #levels = [];
    // get levelHasDown() { return (this.#j < (this.#levels.length - 1)); }
    // get levelHasUp() { return (this.#j > 0); }
    get levelCurrent() { return this.#levels[this.#j]; }
    resetLevelMax() { 
      this.resetLevelTop();
      let o = this.levelCurrent;
      while (this.levelDown()) {
        o = (this.levelCurrent.shapes.length >= o.shapes.length) ? this.levelCurrent : o;
      }
      this.#j = (o.value - 1)
     }
    resetLevelTop() { this.#j = 0; }
    levelDown() { let r = (this.#j < (this.#levels.length - 1)); if (r) { this.#j++; } return r; }
    levelUp() { let r = (this.#j > 0); if (r) { this.#j--; } return r; }
    getLevel(n) {
      if (this.#levels[(n-1)] != undefined) { return this.#levels[(n-1)]; }
      this.#levels[(n-1)] = { value:n, shapes:[] };
      this.#levels[(n-1)].addToLevel = function(c) { return this.shapes[this.shapes.length] = c; };
      return this.#levels[(n-1)];
    }
    updateDraw() {
      switch (_view.value) {
        case 0:
          this.draw = [
            (() => { _s.forEach((s) => { s.makeConnections(); } )}),
            (() => {
              this.resetLevelMax(); let i = 1;
              this.levelCurrent.shapes.forEach((s) => {
                s.e.dataset.column = i++;
                if (
                  ((s.syblings.length % 2) === 0) &&
                  (
                    (s === s.syblings[((s.syblings.length/2) - 1)]) || 
                    ((s === s.syblings[0]) || (i != 1))
                  )
                ) { i++; }
              });
              // while (this.levelUp()) {
                this.levelUp();
                this.levelCurrent.shapes.forEach((s) => {
                  if (s.hasSyblings) {
                    let sy = s.syblings, syF = parseInt(sy[0].e.dataset.column), syL = parseInt(sy[(sy.length-1)].e.dataset.column);
                    console.log('first',syF);
                    console.log('last',syL);
                    let sum = (((syL - syF)%2) == 0) ? (((syL - syF)/2) + syF) : 1;
                    console.log('sum', sum);
                    s.e.dataset.column = sum;
                    console.log(s.e);
                    return;
                  }
                  if (!s.hasParent) { s.e.dataset.column = 1; return; }
                  s.e.dataset.column = parseInt(s.parent.e.dataset.column);
                });
              // } 
            })
            // (() => { _s.forEach((s) => { console.log(s.e); } )}),
          ];
          break;
        case 1:
        case 2:
        case 3:
          (() => { _s.forEach((s) => { s.makeConnections(); } )}),
          (() => { _s.forEach((s) => { s.update(); } )}),
          this.draw = [(() => { alert('Step B1'); })];
          break;
      }
    }
  };

  const SequanceDiagram = class extends Diagram
  {
    updateDraw() {
      switch (_view.value) {
        case 0:
          return [
            (() => { _s.forEach((s) => { s.makeConnections(); } )}),
            (() => { _s.forEach((s) => { s.update(); } )}),
            (() => { alert('Step C1'); } )
          ];
          break;
      }
    }
  }
  
  const UsecaseDiagram = class extends Diagram
  {
    updateDraw() {
      switch (_view.value) {
        case 0:
          return [
            (() => { _s.forEach((s) => { s.makeConnections(); } )}),
            (() => { _s.forEach((s) => { s.update(); } )}),
            (() => { alert('Step E1'); }),
            (() => { alert('Step E2'); })
          ];
          break;
      }
    }
  }

  const ActionDiagram = class extends Diagram
  {
    updateDraw() {
      switch (_view.value) {
        case 0:
        case 1:
          return [
            (() => { _s.forEach((s) => { s.makeConnections(); } )}),
            (() => { _s.forEach((s) => { s.update(); } )}),
            (() => { alert('Step F1'); }),
            (() => { alert('Step F2'); })
          ];
          break;
      }
    }
  }

  const DatabaseDiagram = class extends Diagram
  {
    updateDraw() {
      switch (_view.value) {
        case 0:
          return [
            (() => { alert('Step D1'); }),
            (() => { alert('Step D2'); })
          ];
          break;
      }
    }
  }

  /**
   * Base class for all diagram components <<abstract>>.
   * @param {string} id - Unque identifier of corresponding HTMLElement 
   * @param {HTMLElement} e - Represention of this (Component) on DOM
   */
  const Component = class extends FUNC.Base
  {
    constructor(id, e) { super(); this.abstract('Component');
      this.type = this.constructor.name.toLocaleLowerCase();
      this.id = id; this.e = e;
    }
    get description() { return (core.sp(this.type) + core.sp(this.variant)).trim(); }
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
    makeConnections() { this.abstract('makeConnections'); }
  };

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
  /**
   * Representation of Class in UML.
   * @param {string} id - Unque identifier of corresponding HTMLElement 
   * @param {HTMLElement} e - Represention of this (Class (UML)) on DOM
   */
  const Class = class extends Shape
  {
    #parent; #children = [];
    constructor(id, e) { super(id, e);
      if (this.#parent == undefined && this.e.dataset.level == undefined) { this.e.dataset.level = 1; }
    }
    makeConnections() { 
      _diagram.resetLevelTop();
      Array.prototype.slice.call(this.e.getElementsByClassName('associations')[0].getElementsByTagName('li')).forEach((e) => {
        var i = _c.length, id = (e.id == '') ? 'association-' + (i) : e.id;
        _c[i] = new Association(id, e, this);
        if (_c[i].variant == 'inheritance') {
          this.#parent = _c[i].to;
          this.#parent.addChild(_c[i].from);
          this.e.dataset.level = (parseInt(this.#parent.e.dataset.level) + 1);
          this.level.addToLevel(this);
        }
      });
      if (this.#parent == undefined) {
        this.#parent = { children: [] };
        _diagram.getLevel(1).addToLevel(this);
      }
    }
    get level() { return _diagram.getLevel(parseInt(this.e.dataset.level)); }
    get parent() { return this.#parent; }
    get hasParent() { return (this.#parent instanceof Class); }
    get children() { return this.#children; }
    get hasChildren() { return (this.children.length > 0); }
    get syblings() { return this.parent.children; }
    get hasSyblings() { return (this.syblings.length > 0); }
    addChild(v) { this.#children[this.#children.length] = v; }
  };

  /**
   * Representation of an Object (Class:Object) ina Sequance Diagram.
   * @param {string} id - Unque identifier of corresponding HTMLElement 
   * @param {HTMLElement} e - Represention of this (Object (UML)) on DOM
   */
  const Object = class extends Shape
  {
    #activation; // (startup|runtime)
    constructor(id, e) { super(id, e); }
    makeConnections() { }
  };

  /**
   * Representation of an Actor in a UML Use Case Diagram.
   * @param {string} id - Unque identifier of corresponding HTMLElement 
   * @param {HTMLElement} e - Represention of this (Actor (UML)) on DOM
   */
  const Actor = class extends Shape
  {
    constructor(id, e) { super(id, e); }
    makeConnections() { }
  };

  /**
   * Representation of a Task in a UML Use Case Diagram.
   * @param {string} id - Unque identifier of corresponding HTMLElement 
   * @param {HTMLElement} e - Represention of this (Task (UML)) on DOM
   */
  const Task = class extends Shape
  {
    constructor(id, e) { super(id, e); }
    makeConnections() { }
  };

  /**
   * Representation of a Subject, System Boundary in a UML Use Case Diagram.
   * @param {string} id - Unque identifier of corresponding HTMLElement 
   * @param {HTMLElement} e - Represention of this (System Boundary (UML)) on DOM
   */
  const Boundary = class extends Shape
  {
    constructor(id, e) { super(id, e); }
    makeConnections() { }
  };

  /**
   * Representation of an Action in a UML Action Diagram.
   * @param {string} id - Unque identifier of corresponding HTMLElement 
   * @param {HTMLElement} e - Represention of this (Action (UML)) on DOM
   */
  const Action = class extends Shape
  {
    constructor(id, e) { super(id, e); }
    makeConnections() { }
  };

  /**
   * Representation of a Decision in a UML Action Diagram.
   * @param {string} id - Unque identifier of corresponding HTMLElement 
   * @param {HTMLElement} e - Represention of this (Decision (UML)) on DOM
   */
  const Decision = class extends Shape
  {
    constructor(id, e) { super(id, e); }
    makeConnections() { }
  };

  /**
   * Representation of a Fork Node in a UML Action Diagram.
   * @param {string} id - Unque identifier of corresponding HTMLElement 
   * @param {HTMLElement} e - Represention of this (Fork (UML)) on DOM
   */
  const Fork = class extends Shape
  {
    constructor(id, e) { super(id, e); }
    makeConnections() { }
  };

  /**
   * Representation of a Merge Node in a UML Action Diagram.
   * @param {string} id - Unque identifier of corresponding HTMLElement 
   * @param {HTMLElement} e - Represention of this (Merge (UML)) on DOM
   */
  const Merge = class extends Shape
  {
    constructor(id, e) { super(id, e); }
    makeConnections() { }
  };

  /**
   * Representation of a Start Node in a UML Action Diagram.
   * @param {string} id - Unque identifier of corresponding HTMLElement 
   * @param {HTMLElement} e - Represention of this (Start (UML)) on DOM
   */
  const Start = class extends Shape
  {
    constructor(id, e) { super(id, e); }
    makeConnections() { }
  };

  /**
   * Representation of an End Node in a UML Action Diagram.
   * @param {string} id - Unque identifier of corresponding HTMLElement 
   * @param {HTMLElement} e - Represention of this (End (db)) on DOM
   */
  const End = class extends Shape
  {
    constructor(id, e) { super(id, e); }
    makeConnections() { }
  };

  /**
   * Representation of a connection from one Shape to another <<abstract>>.
   * @param {string} id - Unque identifier of corresponding HTMLElement 
   * @param {HTMLElement} e - Represention of this (Connection) on DOM
   * @param {Shape} cF - Referance for 'from' object.
   */
  const Connection = class extends Component
  {
    #from; #to;
    constructor(id, e, cF) { super(id, e); this.abstract('Connection');
      this.variant = (this.e.classList && this.e.classList[0]) ? this.e.classList[0].trim() : null;
      this.#to = _s.find((sT) => sT.id == this.e.childNodes[0].hash.replace('#', ''));
      this.#from = cF;
      this.e.dataset.orientation = this.e.dataset.orientation || 'h';
    }
    get from() { return this.#from; }
    get to() { return this.#to; }
    get startPoint() { this.abstract('startPoint'); }
    get endPoint(){ this.abstract('endPoint'); }
  };

  // Association (inheritance, association, composition), Flow, EntityRelation, UsecaseRelation(Association, Include, Extend) Message (Call, Create, Return, Self, Recursive) 
  /**
   * Representation of an Association in UML.
   * @param {string} id - Unque identifier of corresponding HTMLElement 
   * @param {HTMLElement} e - Represention of this (Class (UML)) on DOM
   * @param {Shape} cF - Referance for 'from' object (Class (UML)).
   */
  const Association = class extends Connection
  {
    constructor(id, e, cF) { super(id, e, cF);
      if (this.variant == 'inheritance') { this.e.dataset.orientation = this.e.dataset.orientation || 'v'; }
    }
    get startPoint() { return {x: 0, y: 0}; }
    get endPoint(){  return {x: 0, y: 0}; }
    get orientation() { return; } // (h|v)
    get direction() { return ((rF.x + (rF.width/2)) < (rW.y + (rW.width/2))) ? 'ltr' : 'rtl'; }
    get vflow() { return (rF.y > rW.y) ? 'up' : 'down'; }
    get description() { return (core.sp(super.description) + core.sp(this.orientation) + core.sp(this.direction) + core.sp(this.vflow)).trim(); }
  };

  //- LOCAL METHODS.
  /**
   * 
   */
  var save = function() {
    alert("TODO:mrenyard: SAVE to SERVER");
  };

  var addShape = function(typeN, label) {
    alert("TODO:mrenyard: ADD Shape");
  };

  //- INITIALISE
  // Read and add diagram components from available Shapes in DOM.
  // UML (Class, Object, Actor, Task, System, Action, Swimlane, Decision, Fork, Merge) ERD (Entity)
  Array.prototype.slice.call(elm.getElementsByTagName('article')).forEach((e) => { 
    let CLASS = eval(e.classList[0].charAt(0).toUpperCase() + e.classList[0].slice(1));
    // try {
      _s[_s.length] = new CLASS(e.id, e);
    // } catch {
    //   throw new Ex(
    //     Ex.types.UNDECLAREDCLASS,
    //     'HtmlAttribute:classList @index 0 (' + CLASS.name + ') does NOT match available Shapes. (diagram.js:453)'
    //   );
    // }
  });
  // Read and add diagram from available diagram classes.
  let DIAGRAM = eval((((s = _type.name.split('-')[1]).charAt(0).toUpperCase() + s.slice(1))) + 'Diagram');
  try {
    _diagram = new DIAGRAM();
  } catch {
    throw new Ex(
      Ex.types.UNDECLAREDCLASS,
      'HtmlAttribute:classList @index 0 (' + DIAGRAM.name + ') does NOT match available Diagrams. (diagram.js:464)'
    );
  }

  _diagram.drawReset();
  while(_diagram.hasDrawNext) {
    _diagram.drawNext();
  }

  //- PUBLIC ACCESS, (.my)
  return {
    addShape,
    save,
    get o() { return _diagram; },
    get type() { return _type.name; },
    get view() { return _view.name; },
    get viewTypes() { return _allViews[_type.name]; },
    get shapeTypes() { return _allShapes[_type.name]; },
    get shapes() { return _s; },
    get connections() { return _c; },
    get components() { return _s.concat(_c); },
    set view(n) {
      elm.classList.replace('view-' + _view.name, 'view-' + _allViews[_type.name].at(n).name);
      _view = _allViews[_type.name].at(n);
      _diagram.updateDraw();
      _diagram.drawReset();
      while(_diagram.hasDrawNext) { _diagram.drawNext(); }    
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
*/