FUNC.core.js
==================================================================
Library of commonly used Functionality across FUNC modules
==================================================================

**FUNCs core library includes definitions for its commonly used
Base, Enum, and Exception Classes as well common functions such as
`string` manipulation and *[DOM]: Document Object Model* addition.**

 * @author Matt Renyard (twitter: @mrenyard)
 * @package func.core
 * @package func.Base (Base Class)
 * @package func.ENUM
 * @package func.Exception

POSIBLE RELEVANT READING
--------------------------------------------------
 - [Using Namspace placeholders](./my-code.md#namespace-placeholders).

FUNC.Base
--------------------------------------------------

FUNC.ENUM
--------------------------------------------------

FUNC.Exception
--------------------------------------------------

FUNC.core - String Formating and Manipulation
--------------------------------------------------

### FUNC.core.sp
Appends a space to non trivial strings.
 * @param `{string}` `v` - Value to nominally append space.
 * @returns `{string}` - Value with required appended space.
```javascript
  FUNC.core.sp(value:string) : string
```

FUNC.core - DOM Addition, Manipulation and Removal
--------------------------------------------------

### FUNC.core.addSection
Create new `[HtmlElement:section]` within `[HtmlElement:#main]` optionally load dynamic `FUNC.[module]` on it.
```html
    <div id="main" role="main">
      ...
      <section id="[attrabute-id:title]" class="[moduleName] [classList:values]">
        <header><h2>[AttrabuteId:title]</h2></header>
        <div class="canvas">
          ...
        </div>
      </section>
      ...
    </div>
```
 * @param `{string}` `title` - Heading title for new diagram section (`id="title"`).
 * @param `{sting[]}` `type` - Additional `[classList:values]` (excluding module).
 * @param `{string}` `moduleName` - Optional `FUNC.[moduleName]` to be executed on new `[HtmlElement:section]`.
```javascript
FUNC.core.addSection(title, type, moduleName) : void
```
