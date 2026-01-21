# RAMP

**Rapid Application (Minimal Production) Maximum Product
web application development environment for building
accessible, flexible, customisable web systems using the
LAMP development Stack**

## DESIGN PRINCIPLES FOR RAMP

Rapid Development path from a good data structure design
to an early demonstratable, functioning, robust, working,
database drivin application, allowing developers to
then concentrate on any advanced features, specilised
user interface design and user experience.

Clear Separation of form and function (control, model,
view (mark-up templates etc)).

Front-End Control of HTML output for web designers with
semantic HTML and semantic site structure promoted and
used throughout by default.

Server Side Independence from front-end with clearly
defined API and heavy use of the decorator pattern.
Advanced server side functionality can be developed in
isolation, tested against use cases, and dropped into the
system where and when needed.

Coding Standards matter to us, from semantic HTML5, CSS3
(Baseline 2023), and best practices Object Orientated
Programming (OOP); with self explanatory method names,
common API's and encapsulation.

Progressive Enhancement principles are promoted and used
throughout. Designed with consideration for modern
JavaScript and AJAX (Hijax methodology) from the outset,
with the server side capable of returning complete pages,
HTML fragments or JSON dependant on request headers at the
same resource address.
i.e. It still works with JavaScript switched off.

Highly Customisable, with local code stored in a separate
directory (local_dir), application specific components
are added without interfering with the offical release
code, but add a class of the same name and it
automatically overrides the existing class.

Rapid Development, Clear Separation, Front-End Control,
Server Side Independence, Coding Standards, Progressive
Enhancement and Highly Customisable.

The RAMP way to building better systems.

## Setting up a development environment for the first time

Setting up a development environment to work on the main
RAMP project is much more envolved than it is to setup RAMP
as the base for your own projects...

>NOTE: Only for developers that wishing to contrabute to the RAMP project core.
> For your indervidual RAMP projects you will want to install RAMP using our latest released package.

This development environment setup proces will get and install
multiple dependant parts not just RAMP but also:

- Apache2 WebShop Configuration (LAMP),
- Web Project Management Tools,
- FUNC.js
- MEDIA
- and STyLE
- ...plus connect all the relevant sub project and libararies.

```console
if [ ! -d ${HOME}/Projects ]; then mkdir ${HOME}/Projects; fi & cd ${HOME}/Projects/
```

```console
git clone https://github.com/mrenyard/RAMP.git
```

```console
cd RAMP/ && sudo tools/buildDevEnv
```
