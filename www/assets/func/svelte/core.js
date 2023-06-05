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
 * @example: pets = new Enum('Cat', 'Dog', 'Fish', 'Hamster', 'Cat Fish');
 */
FUNC.Enum = function()
{
  this.properties = {};
  for (var i=0, j=arguments.length; i < j; i++) {
    var _itm = arguments[i];
    this[_itm.toUpperCase()] = i;
    this.properties[i] = { name:_itm, value:i };
  }
};

/**
 * Exception Super Class and Type Enum
 */
FUNC.exceptions = new FUNC.Enum('BadMethodCall', 'OutOfBounds', 'UnexpectedArgument');
FUNC.Exception = function(type, message)
{
  this.toString = function()
  {
    return FUNC.exceptions.properties[type].name + 'Exception: ' + message;
  }
};

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
  thiz.count = function()
  {
    return _idList.length;
  };

  thiz.current = function()
  {
    return thiz[_idList[_currentIndex]];
  };

  thiz.next = function()
  {
    if (thiz.count() == 0)
    {
      throw new FUNC.Exception(
        FUNC.exceptions.OUTOFBOUNDS, 'Please check count() > 0 (line 63 cvt/core.js)'
      );
    }
    return (_currentIndex++ < (thiz.count()-1));
  };

  thiz.rewind = function()
  {
    _currentIndex = -1;
  };

  thiz.add = function(o)
  {
    if (thiz[o.el[className + 'id']] == null) { _idList[_idList.length] = o.el[className + 'id']; }
    thiz[o.el[className + 'id']] = o;
  };

  thiz.updateFromDom = function(elms)
  {
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
