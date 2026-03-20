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

## Contributing to the main RAMP project

This is more involved than using RAMP for your own projects...

>NOTE: Only for developers that wishing to contribute to the RAMP project core.
> For your indervidual RAMP projects you will want to install RAMP using one of our latest released packages.

First you will need to set up a development environment, We recomend doing this on one of
any number of typical Debbian based Linux system by follow the below processs which will
download and install multiple dependant parts:

- The develpment RAMP project,
- Apache2 WebShop Configuration (LAMP),
- Web Project Management Tools,
- connect all the relevant sub project and libararies (FUNC.js, MEDIA, STyLE)
- ... plus run first unit testing with code coverage and produce documentation.

Check, create and access the 'Projects' folder in your home directory.

```console
if [ ! -d ${HOME}/Projects ]; then mkdir ${HOME}/Projects; fi && cd ${HOME}/Projects/
```

Check for or clone the latest RAMP git repository.

```console
if [ ! -d RAMP ]; then git clone https://github.com/mrenyard/RAMP.git; fi && cd RAMP
```

Run buildDevEnv to finalise setup.

```console
sudo tools/buildDevEnv
```

You are now ready to start looking through and playing with the project code in your
preferred IDE. We recommend Visual Code which you can install through your default
application installation method (Snap, APT, FlatPak or AppCentre).
