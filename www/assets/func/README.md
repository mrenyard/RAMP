FUNC.js - Front-end Utilities for Networked Client
==================================================

**Written to complement The RAMP platform with
front-end interactive, immersive and dynamic 
content, through the use of RAMP's serverside
Hijax-centric Platform. FUNC.js brings a range of
Javascript (ECMAScript) modules that are capable
of enhancing your web DOM (Document Object Model).**

 * @author Matt Renyard (twitter: @mrenyard)
 * @package FUNC

GETTING STARTED WITH FUNC
--------------------------------------------------

To use FUNC simply place the package folder (func)
within the root of your web directory, most commonly
within an 'assets' folder.
```
  |
  +-- public_html       (website root folder)
  |  |-- index.html      (static homepage)?
  |  +-- controller.php  (RAMP controller file)
  |  +-- assets
  |     +-- func        <-- HELLO from FUNC.js.
  |     |  +-- docs
  |     |  +-- extlibs
  |     |  +-- mods
  |     |  |  +-- full
  |     |  |     +-- core.lib.js
  |     |  |     +-- [module].js 
  |     |  |     +-- [library].lib.js
  |     |  |     +-- ...
  |     |  +-- init.js
  |     |  +-- init-full.js
  |     +-- style
  |     +-- media
  --- ramp.ini        (local initialization file)
```
Then add the below code to the bottom of any relevant
template and any or all pages on your site.

```html
      ...
    </footer>
    <script src="/assets/func/init.js"></script>
    <script defer>
FUNC.init.register('diagram', 'FUNC.diagram'), ['core',];
FUNC.init.run();
    </script>
  </body>
</html>
```
Optionally if choosing to place in a different location
within your site's root directory you may have to include
a new value for 'modsPath' before the first use of
`FUNC.init`.

The default path being '/assets/func/mods/'
```javascript
FUNC.modsPath = '/assets/func/mods/';
```

As you wish to use more modules across your website/application
you will want to register them by adding new `FUNC.init.resgister`
statements above `FUNC.init.run()` with the following paramaters:
```javascript
FUNC.init.register({HtmlClassName:string}, {moduleName:string}, {[{libraryName:string}, ...]]:array});
```
1. HtmlClassName - Identifing HtmlClass:name, this className will
need to be present with your HTML DOM to activate relevant modual.
2. ModuleName - Modual name to be exacuted against each
relevant HtmlEntity fragment (e.g. `FUNC.diagram`).
3. [libraryName, ...]{:array} List of dependent library names ordered
in loading propriety (e.g. `['core', 'event']`).


EXPLORING FUNC.js APIs in Developer Tools Console
--------------------------------------------------

*Once you open a web document with FUNC.js loaded
as described above you are ready to start playing
around with some of the exposed fuctionality.*

Open up your web document in your favourite web browser
and then its 'Web Developer Tools'.

- Firefox:     `menu -> More tools -> Web Developer Tools (F12)`
- Chrome/Edge: `menu -> More tools -> Developer Tools (F12)`

Now you can access FUNC under the Developer Tools Console tab!

Simply type: `FUNC;` to start exploring.

The current package version number.
```javascript
FUNC.version;
```

The 'init' registration system (init.js|init-full.js).
```javascript
FUNC.init;
FUNC.init.register(..., ..., [..., ...])
FUNC.init.run();
```

The 'core' library (mods/core.js|mods/full.core.js).
```javascript
FUNC.core;
FUNC.core.[...];
```
As you continue to explore and register more modules
you will begin to understand more about its API.

Most packages will add content depending on the
pages DOM under `FUNC.my.[moduleName][index]`,
and all will add a constructor function under
`FUNC.[moduleName]` with some modules and
libaries adding constants within the same space;

```javascript
FUNC.my.[moduleName][index];
FUNC.[moduleName]();
```

For more details on available modules see the list below,
you will find API documentaion within the adjacent docs folder.

Matt Renyard.

List of available Libaries
--------------------------------------------------
- [Base Classes and the INIT System (init.js)](docs/init.md)
- [Library of Commonly Used Functionality Across FUNC Modules (FUNC.core)](docs/core.md)
- [DOM Useful Document Object Model manipulisation that tie-in with the strict HTML5 stlye used on RAMP (FUNC.dom)](docs/dom.md)
- [Event Library, dragNdrop or register complex events with a start,move and end (FUNC.event)](docs/event.md)
- ...hijax
- ...

List of available Modules
--------------------------------------------------
- ...forms
- [Manipulate, Draw and Arrange a Range of Diagrams (FUNC.diagram)](docs/diagram.md)
- ...
