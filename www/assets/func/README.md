FUNC.js - Frontend Utilities for Networked Client
==================================================

**Written originally to complement The RAMP platform
with frontend interactive, immersive and dynamic 
content, through the use of RAMP's serverside
Hijax-centric Platform. FUNC.js brings a range of
Javascript (ECMAScript) modules that are capable
of enhancing your web DOM (Document Object Model).**

 * @author Matt Renyard (twitter: @mrenyard)
 * @package func

GETTING STARTED WITH FUNC
--------------------------------------------------

To use FUNC simply place the package folder (func)
within the root of your web directory, most commonly
within an 'assets' folder.
```
  |
  +-- public_html       (website root folder)
  | |-- ...
  | |-- index.html      (static homepage)?
  | |-- controller.php  (RAMP controller file)
  | |-- assets
  |   +-- func        <-- HELLO from FUNC.js.
  |     |-- docs
  |     |-- extlibs
  |     |-- mods
  |     |-- core.js
  |     +-- core-full.js
  |
  +-- ramp.ini          (local initialization file)
```
Then add the below code to the bottom of any relevant
template and any or all pages on your site.

```html
      ...
    </footer>
    <script src="/assets/func/core.js"></script>
    <script defer>
FUNC.init.register('diagram', 'FUNC.diagram');
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
statements above `FUNC.init.run()` and below any dependancies.
```javascript
FUNC.init.register([HtmlClassName:string], [FuncModule:string]);
```

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

The 'init' registration system.
```javascript
FUNC.init;
FUNC.init.register(..., ...)
FUNC.init.run();
```

The 'core' library 
```javascript
FUNC.core;
FUNC.core.[...];
```
As you continue to explore and register more modules
you will begin to understand more about its API.

Most packages will add content depending on the
pages DOM under `FUNC.my.[moduleName][index]`,
and all will add a constructor function under
`FUNC.[moduleName][index]` with some modules
adding module constants within the same space;

```javascript
FUNC.my.[moduleName][index];
FUNC.[moduleName]();
```

For more details on each module you will find API
documentaion witin this folder as `[moduleName].md`.

Matt Renyard.

MODULES
--------------------------------------------------
- [Core](docs/core.md)
- [Diagram](docs/diagram.md)
