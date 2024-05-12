/**
 * FUNC.js - Frontend Utilities for Networked Client (js).
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package func.libraryName
 * @version 0.1;
 */
var FUNC = window.FUNC || {};
FUNC.version = FUNC.version || .1;

/**
 * [Description of library].
 */
FUNC.libraryName = function(elm)
{
  //- USE (dependant namespaces)
  var N = FUNC.Enum,
      Ex = FUNC.Exception;

  //- SHARED PRIVATE VARABLES
  var _varOne = [], _varTwo;

  //- LOCAL METHODS.
  /**
   * Does some action.
   * @example
   * FUNC.library.actionOne(
   *   '...',
   *   0
   * );
   * @param {string} param1 
   * @param {int} param2 
   */
  var actionOne = function(param1, param2) {
    // ...do somthing
  };

  /**
   * Does some other action.
   * @example
   * FUNC.library.actionTwo(
   *   '...',
   *   0
   * );
   * @param {string} param1 
   * @param {string} param2 
   */
  var actionTwo = function(param1, param2) {
    // ...do somthing
  };

  //- INITIALISE
  // [(if) any single (HTML) document initialisation].

  //- PUBLIC ACCESS
  return {
    actionOne,
    actionTwo
  };
}();