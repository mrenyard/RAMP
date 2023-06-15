FUNC.core.js
==================================================================
Library of Commonly Used Functionality Across FUNC Modules
==================================================================

**FUNCs core library includes definitions for its commonly used
Base, Enum, and Exception Classes as well as common functions such
as `string` manipulation and Document Object Model (DOM) addition.**

 * @author Matt Renyard (twitter: @mrenyard)
 * @package func.core
 * @package func.Base (Base Class)
 * @package func.ENUM
 * @package func.Exception

FUNC.Base
--------------------------------------------------

FUNC.Enum
--------------------------------------------------

FUNC.Exception
--------------------------------------------------

FUNC.core - String Formatting and Manipulation
--------------------------------------------------

### FUNC.core.sp
Appends a space to non trivial strings.
 * @param `{string}` `v` - Value to nominally append space.
 * @returns `{string}` - Value with required appended space.
```javascript
  FUNC.core.sp(v);
```

FUNC.core - DOM Addition, Manipulation and Removal
--------------------------------------------------

### FUNC.core.addSection
Create new `[HtmlElement:section]` within `[HtmlElement:#main]` optionally load dynamic `FUNC.[module]` on it.
```html
    <div id="main" role="main">
      <header><h1>Page Main Heading</h1></header>
      ...
      <section id="[attrabute-id:title]" class="[moduleName] [class-list:values]">
        <header><h2>[AttrabuteId:title]</h2></header>
      </section>
      ...
    </div>
```
 * @param `{string}` `title` - Heading title for new diagram section (`id="title"`).
 * @param `{sting[]}` `type` - Additional `[classList:values]` (excluding module).
 * @param `{string}` `moduleName` - Optional `FUNC.[moduleName]` to be executed on new `[HtmlElement:section]`.
```javascript
FUNC.core.addSection(title, type, moduleName);
```

Using Namespaces within your local namespace
--------------------------------------------------
```javascript
FUNC.[newModule] = function(elm)
{
  //- USE (dependant namespaces)
  var _ = FUNC.[newModule:current],
      N = FUNC.Enum,
      Ex = FUNC.Exception,
      core = FUNC.core;
  ...

  _.[globalNamespace:function] = function(param, ...) {
    ...
  }

  var type = new N('[:string]', '[:string]', '[:string]', '[:string]')

  try {
    ...
  } catch () {
    throw new Ex(Ex.BADMETHODCALL, '[message:string]')
  }

  core.addSection([title:string], [[type:string], [type:string], ...], [string:moduleName]);

  ...
}
```
