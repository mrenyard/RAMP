FUNC.js - Frontend Utilities for Networked Client
==================================================

**Writen originaly to complement The RAMP platform
with fountend interactive, immersive and dynamic 
content. Throught the use of The RAMPs serverside
Hijax centric Platform, FUNC.js brings a range of
Javascript (ECMAScript) modules that are capable
of enhancing any web DOM.**

 * @author Matt Renyard (renyard.m@gmail.com)
 * @package func.core

GETTING STARTED WITH FUNC
--------------------------------------------------

To use FUNC simple place the package folder (func)
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
    <script src="/assets/func/core-full.js"></script>
    <script defer>
FUNC.init.register('diagram', 'FUNC.diagram');
FUNC.init.run();
    </script>
  </body>
</html>
```
Optionally if chosing to place in a diferent location
within your sites root directory you may have to include
a new value for 'modsPath' before the first use of
FUNC.init.

The default path being '/assets/func/mods/'
```javascript
FUNC.modsPath = '/assets/func/mods/';
```

As you wish to use more modules accross your website/application
you will want to register them by adding new `FUNC.init.resgister`
statments above `FUNC.init.run()` and below any dependances.

  * Register potentual FUNC modules needed site wide, only loads those scripts actually need per page.
  * @param {srting} className - Identifing HtmlClass:name for modual use. 
  * @param {string} fn - FUNC[function/module] as a string to be exacuted against each HtmlEntity (fragment).

```javascript
FUNC.init.register([HtmlClassName:string], [FuncModule:string]);
```

EXPLORING FUNC.js APIs in Developer Tools Console
--------------------------------------------------

*Once you open a web document with FUNC.js loaded
as discripebed above you are ready to start playing
around with some of the exposed fuctionality.*

Open up your web document in you favourte web browser
and then its 'Web Developer Tools'.

- Firefox:     menu -> More tools -> Web Developer Tools (F12)
- Chrome/Edge: menu -> More tools -> Developer Tools (F12)

Now you can access FUNC under the Developer Tools Console tab!

Simple type: `FUNC;` to start exporing.

The current package versin number.
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
As you continue to expore and register more modules
you will beging to understand more about its API.

Most packages with add content dependent on the
pages DOM under `FUNC.my.[moduleName][index]`
all will add a constructor function under
`FUNC.[moduleName]` including some modules
adding constants within the same space;

```javascript
FUNC.my.[moduleName];
FUNC.[moduleName]();
```

For more details on each module you will find API
documentaion witin this folder as `[moduleName].md`.

Matt Renyard.


