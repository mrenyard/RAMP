/**
 * Author: renyard.m
 */
var SVELTE = window.SVELTE || {};

SVELTE.dom = function() {

  var _build = function(type,h,t,cN,id)
  {
    var _e = document.createElement(type);
    var _h1 = document.createElement('h1');
    _h1.title = t; _h1.appendChild(document.createTextNode(h));
    _e.className = cN; _e.appendChild(_h1);
    _e.setAttribute('id', id);
    return _e;
  }

  var _buildSection = function(h,t,cN,id)
  {
    var _s = _build('section',h,t,cN,id);
    return {
      getNode: _s,
      add: function(childNode) { _s.appendChild(childNode); }
    };
  };

  var _buildList = function(ordered) {

    var _l = (! ordered)? document.createElement('ul') : document.createElement('ol');
    var _add = function(e) {
      var li = document.createElement('li');
      li.appendChild(e);
      _l.appendChild(li);
    };
    return {
      getNode: _l,
      add: function(itemElement) { _add(itemElement); }
    };
  };

  return {
    Section: function(heading, title, className, id) {
      return _buildSection(heading, title, className, id);
    },
    List: function(ordered) { return _buildList(ordered); }
  };
}();
