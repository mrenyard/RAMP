FUNC.init (`init.js`|`init-full.js`)
==================================================================
Base Classes (Base, Enum, Exception, Collection) and the INIT System.
==================================================================

**FUNC init file includes definitions for its commonly used Base,
Enum, Exception and Collection Classes as well as the init system.
A system that ensure per page, dom spesific, modules are requested
and loaded along with any required libaraies on a per nessasary bases.**

 * @author Matt Renyard (twitter: @mrenyard)
 * @package func.Base (Base Class)
 * @package func.ENUM
 * @package func.Exception
 * @package func.Collection
 * @package func.init

FUNC.Base Abstract Class Inherited Universally.
--------------------------------------------------

FUNC.Enum Created from List of Arguments.
--------------------------------------------------

FUNC.Exception Types; Throw, Catch and Handling 
--------------------------------------------------

FUNC.Collection Interation, Addition inc from DOM. 
--------------------------------------------------

Using Namespaces within your local namespace.
--------------------------------------------------
```javascript
FUNC.[newModule] = function(elm)
{
  //- USE (dependant namespaces)
  var _ = FUNC.[newModule:current],
      N = FUNC.Enum,
      Ex = FUNC.Exception,
      List = FUNC.Collection,
      core = FUNC.core;
  ...

  _.[globalNamespace:function] = function(param, ...) {
    ...
  }

  var type = new N('{:string}', '{:string}', '{:string}', '{:string}')

  try {
    ...
  } catch () {
    throw new Ex(Ex.BADMETHODCALL, '{message:string}')
  }

  core.addSection({title:string}, [{type:string}, {type:string}, ...], {moduleName:string});

  ...
}
```
Useful and Related Content
--------------------------------------------------

