/**
 * FUNC.js - Frontend Utilities for Networked Client (js).
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package func.site
 * @depends func.dom, func.event 
 * @version 0.1;
 * @example
 * FUNC.init.register('#domain-name-org', 'FUNC.site', ['FUNC.dom', 'FUNC.event]);
 */
var FUNC = window.FUNC || {};
FUNC.version = FUNC.version || .1;

/**
 * [Description of module]. 
 */
FUNC.site = function(elm)
{
  //- USE (dependant namespaces)
  // var _ = FUNC.module,
  //     N = FUNC.Enum,
  //     Ex = FUNC.Exception,
  //     lib1 = FUNC.lib1;

  //- NAMESPACE PROPERTIES
  // _.property = '...';

  //- SHARED PRIVATE VARABLES
  // var _varOne = [], _varTwo;

  //- NAMESPACE METHODS
  // _.method = function(param1, param2) {
  //   // do something.   
  // };

  //- LOCAL CLASSES.
  // const ClassName = class extends FUNC.Base
  // {
  //   #privateProperty;
  //   constructor() { super(); this.abstract('className');
  //     this.#privateProperty = [];
  //   }
  //   get property() { return this.#privateProperty; }
  //   set property(v) { this.#privateProperty = v; }
  //   method() {
  //     // ...do something;
  //   }
  // };

  //- LOCAL METHODS.
  // var method = function() {
  //   // ...do something
  // };

  //- INITIALISE
  // [code to run against provided 'elm'].
  let quickLinks = document.getElementById('quick-links');
  if (quickLinks != null) {
    document.addEventListener('focusin', (e) => {
      if (location.hash == '#top') {
        location.href = '#';
        return;
      }
    });
    document.addEventListener('click', (e) => {
      if (location.hash == '#top') {
        if (e.target.id == 'quick-links') { return; }
        location.href = '#';
        document.body.classList.remove('kill-transition');
      }
    });
    document.addEventListener('scroll', (e) => {
      if (location.hash == '#top' && window.scrollY > 0) {
        document.body.classList.add('kill-transition');
      }
      if (document.body.classList.contains('kill-transition') && window.scrollY >= quickLinks.offsetHeight) {
        location.href = '#';
        window.scroll(-(quickLinks.offsetHeight), 0);
        document.body.classList.remove('kill-transition');
      }
    });
  }
  
  //- PUBLIC ACCESS, (.my)
  // return {
  //   method,
  //   get property() { return _property},
  //   set property(v) { _property = v; }
  // };
};
