/**
 * FUNC.js - Frontend Utilities for Networked Client (js).
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package func.core
 * @version 0.1;
 */
var FUNC = window.FUNC || {};
FUNC.version = FUNC.version || .1;

/**
 * Core libary.
 */
FUNC.core = function()
{
  /**
   * Appends a space to non trivial strings.
   * @param {string} v - Value to nominally append space 
   * @returns {string} - Value with required appended space.
   */
  var sp = function(v) { return (v === null || v === '' || v === 'undefined' ) ? '' : v + ' '; };

  //- PUBLIC ACCESS
  return { sp };
}();
 