FUNC.dom (`mods/dom.lib.js`|`mods/full/dom.libs.js`)
==================================================================
Useful Document Object Model (DOM) Manipulation, optimised
to work with and tie-into the strict HTML5 style used on RAMP.
==================================================================

**FUNCs Document Object Model (DOM) library manages
section addition, ...**

 * @author Matt Renyard (twitter: @mrenyard)
 * @package func.dom

DOM Addition, Manipulation and Removal.
--------------------------------------------------

### FUNC.dom.addSection
Create new `[HtmlElement:section]` within `[HtmlElement:#main]` optionally load dynamic `FUNC.[module]` on it.
```html
    <div id="main" role="main">
      <header><h1>Page Main Heading</h1></header>
      ...
      <section id="[attrabute-id:title]" class="[moduleName] [class-list:values]">
        <header><h2>[AttrabuteId:title]</h2></header>
        ...
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

Useful and Related Content
--------------------------------------------------
 - [Using Namspace placeholders](./init.md#using-namespaces-within-your-local-namespace).
