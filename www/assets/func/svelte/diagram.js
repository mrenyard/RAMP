/**
 * FUNC.js - Frontend Utilities for Networked Client (js).
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package func.diagram
 * @version 0.1;
 */
var FUNC = window.FUNC || {};
FUNC.version = FUNC.version || .1;

FUNC.diagram = function()
{
  var i=0, Si=1, Ai=1, components = [];
  var _scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
  var _scrollTop = window.pageYOffset || document.documentElement.scrollTop;
  // var _scrollLeft = function() { return window.pageXOffset || document.documentElement.scrollLeft; };
  // var _scrollTop = function() { return window.pageYOffset || document.documentElement.scrollTop; };

  const Component = class
  {
    constructor(id, data = {}) {
      this.id = id;
      this._elm = document.getElementById(this.id);
      this.type = this.constructor.name.toLocaleLowerCase();
      Object.assign(this, data);
      // Object.assign(this, document.getElementById(this.id));
    }
    get description() { return (_sp(this.type) + _sp(this.variant)).trim(); }
    update() { this._elm.setAttribute('class', this.description); }
  };

  const Shape = class extends Component
  {
    constructor(id, data = {}) {
      super(id, data);
      this.variant = (this._elm.classList && this._elm.classList[1]) ? this._elm.classList[1].trim() : null;
      // this._elm.dataset.column = this.data.column || 
    }
  };

  const Association = class extends Component
  {
    constructor(id, data = {}) {
      super(id, data);
      this.variant = (this._elm.classList && this._elm.classList[0]) ? this._elm.classList[0].trim() : null;
      this.from = components.find((e) => e.id == this._elm.parentElement.parentElement.id);
      this.with = components.find((e) => e.id == this._elm.childNodes[0].hash.replace('#', ''));
      setTimeout(function() { }, 500);

      // var rF = this.from._elm.offsetLeft,
      //     rW = this.with._elm.getClientRect();
      switch (this.variant) {
        case 'inheritance':
          this.orientation = this.orientation || 'v';
          if (this.from.type == 'class') { this.from.parentClass = this.with; }
          case 'relation':
        case 'aggregation':
        case 'composition':
        default:
          this.orientation = this.orientation || 'h';
          // this.direction = this.direction || ((this.from._elm.offsetLeft (rF.width/2)) <= (rW.x + (rW.width/2))) ? 'ltr' : 'rtl';
          // this.vflow = this.vflow || (rW.y < rF.y) ? 'up' : 'down';

          // this.start = (this.vflow == 'up') ?
          //   { x:(rF.left + _scrollLeft + (rF.width/2)), y:(rF.top + _scrollTop) } :
          //   { x:(rF.x + _scrollLeft + (rF.width/2)), y:(rF.y + _scrollTop + rF.height) };

          //   this.end = (this.vflow == 'up') ?
          //   { x:((rW.x + (rW.width/2)) - _scrollLeft), y:((rW.y + rW.height) - _scrollTop) } :
          //   { x:((rW.x + (rW.width/2)) - _scrollLeft), y:((rW.y) - _scrollTop) };
          }
    }
    update() {
    }
    get description() { return (_sp(this.variant) + _sp(this.orientation) + _sp(this.direction) + _sp(this.vflow)).trim(); }
  };

  const Entity = class extends Shape
  {
    constructor(id, data = {}) {
      super(id, data);
    }
  };

  const Class = class extends Shape
  {
    #parent;
    constructor(id, data = {}) {
      super(id, data);
      this._elm.dataset.level = this._elm.dataset.level || 1;
    }    
    set parentClass(value) { this.#parent = value; this._elm.dataset.level = parseInt(this.#parent._elm.dataset.level) + 1; }
    get parentClass() { return this.#parent; }
  };

  var _sp = function(v) { return (v === null || v === '' || v === 'undefined' ) ? '' : v + ' '; };

  var save = function() {
    alert("TODO:mrenyard: SAVE to SERVER");
  }

  // Build and add Shapes to components ref:TagName('article')
  var eA = Array.prototype.slice.call(document.getElementsByTagName('article'));
  eA.forEach((e) => { 
    if (e.id == '') { e.setAttribute('id', _id); }
    switch (e.classList[0].toUpperCase()) {
      case 'ENTITY':
        components[i++] = new Entity(e.id, e.dataset);
        break;
      case 'CLASS':
        components[i++] = new Class(e.id, e.dataset);
        break;
    }
  });

  // From each Shape build and add Associations.
  var buildA = function() {
  components.forEach((e) => {
    var _asoc = document.getElementById(e.id).getElementsByClassName('associations');
    if (_asoc.length == 1) {
      var eA = Array.prototype.slice.call(_asoc[0].getElementsByTagName('li'));
      eA.forEach((e) => {
        var _id = (e.id == '') ? 'association-' + (Ai++) : e.id;
        if (e.id == '') { e.setAttribute('id', _id); }
        components[i++] = new Association(_id, e.dataset);
      });
    }
  });
  }
  setTimeout(buildA, 500);

  return {
    components,
    save
  };
}();
