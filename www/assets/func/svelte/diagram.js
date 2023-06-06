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
 * Build, edit and arrange common diagrams like 
 * UML (Class, Sequence, (?Activity, Use Case)), Database (Entity Relationship)
 * and more... 
 */
FUNC.diagram = function(elm)
{
  //- USE (namespaces)
  var _ = FUNC.diagram,
      ex = FUNC.exceptions,
      Ex = FUNC.Exception,
      core = FUNC.core,

  //- LOCAL VARABLES.
      i=0, Ai=1, components = [],
      canvas = elm.getElementsByClassName('canvas')[0],
      annotate = document.createElement('footer');

  // underlay.className = 'underlay';
  elm.appendChild(annotate);

      // scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
      // scrollTop = window.pageYOffset || document.documentElement.scrollTop;

  //- LOCAL CLASSES.
  /**
   * Base class (abstract) for all diagram components.
   * @param {string Unque ID of coresponding element} id 
   */
  const Component = class
  {
    constructor(id, e) {
      this.id = id;
      this.e = e; //document.getElementById(this.id);
      this.type = this.constructor.name.toLocaleLowerCase();
      // Object.assign(this, data);
    }
    get description() { return (sp(this.type) + sp(this.variant)).trim(); }
    update() { this.e.setAttribute('class', this.description); }
  };

  /**
   * 
   * @param {*} id 
   */
  const Shape = class extends Component
  {
    constructor(id, e) {
      super(id, e);
      this.variant = (this.e.classList && this.e.classList[1]) ? this.e.classList[1].trim() : null;
      // this.e.dataset.column = this.data.column || 
    }
  };

  /**
   * 
   * @param {*} id 
   */
  const Association = class extends Component
  {
    constructor(id, e) {
      super(id, e);
      this.variant = (this.e.classList && this.e.classList[0]) ? this.e.classList[0].trim() : null;
      this.from = components.find((c) => c.id == this.e.parentElement.parentElement.id);
      this.with = components.find((c) => c.id == this.e.childNodes[0].hash.replace('#', ''));
      switch (this.variant) {
        case 'inheritance':
          this.orientation = this.orientation || 'v';
          if (this.from.type == 'class') { this.from.parentClass = this.with; }
          var rF = core.getDocRect(this.from.e), rW = core.getDocRect(this.with.e); 
          console.log(id, rF.container);
          this.direction = this.direction || ((rF.x + (rF.width/2)) < (rW.y + (rW.width/2))) ? 'ltr' : 'rtl';
          this.vflow = this.vflow || (rF.y > rW.y) ? 'up' : 'down';
          this.start = //(this.vflow == 'up') ?
            { x:(rF.x + (rF.width/2)), y:(this.from.e.offsetTop) }; // : null;
            // { x:(rF.x + (rF.width/2)), y:(rF.y + rF.height) };
          // this.end = (this.vflow == 'up') ?
          //   { x:(rW.x + (rW.width/2)), y:(rW.y + rW.height) } : null;
            // { x:(rW.x + (rW.width/2)), y:(rW.y) };
            setTimeout(() => {
              var o = document.createElement('span');
              o.setAttribute('class', 'fixed');
              o.setAttribute('id', this.id);
              o.style.left = ((this.from.e.offsetLeft + (this.from.e.offsetWidth/2))-3) + 'px';
              o.style.top = (this.from.e.offsetTop-3) + 'px';
              annotate.appendChild(o);
            }, 500);
            break;
        case 'relation':
        case 'aggregation':
        case 'composition':
        default:
          this.orientation = this.orientation || 'h';
          break;
      }
    }
    get description() { return (sp(this.variant) + sp(this.orientation) + sp(this.direction) + sp(this.vflow)).trim(); }
  };

  /**
   * 
   * @param {*} id 
   */
  const Entity = class extends Shape
  {
    constructor(id) {
      super(id);
    }
  };

  /**
   * 
   * @param {*} id 
   */
  const Class = class extends Shape
  {
    #parent;
    constructor(id, e) {
      super(id, e);
      this.e.dataset.level = this.e.dataset.level || 1;
    }    
    set parentClass(value) { this.#parent = value; this.e.dataset.level = parseInt(this.#parent.e.dataset.level) + 1; }
    get parentClass() { return this.#parent; }
  };

  //- LOCAL METHODS.
  /**
   * 
   * @param {*} v 
   * @returns 
   */
  var sp = function(v) { return (v === null || v === '' || v === 'undefined' ) ? '' : v + ' '; };

  /**
   * 
   */
  var save = function() {
    alert("TODO:mrenyard: SAVE to SERVER");
  }

  // BUILD INITIALISATION
  // Build and add Shapes to components ref:TagName('article')
  var eA = Array.prototype.slice.call(elm.getElementsByTagName('article'));
  eA.forEach((e) => { 
    if (e.id == '') { e.setAttribute('id', _id); }
    switch (e.classList[0].toUpperCase()) {
      case 'ENTITY':
        components[i++] = new Entity(e.id, e);
        break;
      case 'CLASS':
        components[i++] = new Class(e.id, e);
        break;
    }
  });
  // From each Shape build and add Associations.
  // var buildA = function() {
  components.forEach((e) => {
    var _asoc = document.getElementById(e.id).getElementsByClassName('associations');
    if (_asoc.length == 1) {
      var eA = Array.prototype.slice.call(_asoc[0].getElementsByTagName('li'));
      eA.forEach((e) => {
        var _id = (e.id == '') ? 'association-' + (Ai++) : e.id;
        components[i++] = new Association(_id, e);
      });
    }
  });
  // }
  // setTimeout(buildA, 2500);

  //- PUBLIC ACCESS
  return {
    components,
    // annotate,
    save
  };
};

FUNC.init.register('diagram', FUNC.diagram);
