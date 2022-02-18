RAMP
======

**Rapid web application development environment for building flexible, customisable web systems using the LAMP development Stack**


DESIGN PRINCIPLES FOR SVELTE
---------------------------------------------------------

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

Coding Standards matter to us, from semantic HTML5, CSS3,
and best practices Object Orientated Programming (OOP);
with self explanatory method names, common API's and
encapsulation.

Progressive Enhancement principles are promoted and used
throughout. Designed with consideration for modern
JavaScript and AJAX from the outset, with the server side
capable of returning complete pages, HTML fragments or
JSON dependant on request headers at the same resource
address. i.e. It still works with JavaScript switched off.

Highly Customisable, with local code stored in a separate
directory (local_dir), application specific components
are added without interfering with the offical release
code, but add a class of the same name and it
automatically overrides the existing class.

Rapid Development, Clear Separation, Front-End Control,
Server Side Independence, Coding Standards, Progressive
Enhancement and Highly Customisable.


SOME RULES OF THE WEB WE KEPT IN MIND
---------------------------------------------------------

1) There is ONLY ONE type of event in any web application.  
    A HTTP request.

2) Every View (web fragment) ONLY has ONE parent.  
    Semantics are inferred in both page markup  
     and overall site structure.

3) Once created, a resource's address SHOULD rarely be abandoned.

4) Every unique resource should have A unique address
  - Do not break the back button
  - Frames are bad
  - AJAX used to load primary content is bad

5) An Authorisation Form (login), is NOT a resource.  
    And therefore should NOT have its own address

6) Functional Enhancements (Javascript) are LAST element  
   in the front end technology stack, NOT the FIRST.  
    Preceded firstly by Markup (HTML) and then Style (CSS)

---------------------------------------------------------

There are three types of semantics to consider within the
world wide web:  
Hierarchical, Ordered and Networked.

- Hierarchical:
  - Page Markup
  - Site url structure (site maps)
- Order:
  - Order from top to bottom within any Hierarchy
  - Page content (first sibling - last sibling)
  - Google search results
- Networked:
  - HyperLinks from one resource to another
   (particularly between different sites)


WE SUPPORT THE PRINCIPLES AND IDEAS OF:
----------------------------------------------------------

A Front End Engineer's Manifesto  
[http://f2em.com/]

Design Patterns: Elements of Reusable Object-Oriented Software  
(GoF:  Erich Gamma, Richard Helm, Ralph Johnson, and John Vlissides)

Design Patterns Explained: A New Perspective on Object-Oriented Design  
[https://www.amazon.co.uk/Design-Patterns-Explained-Perspective-Object-Oriented/dp/0321247140]  
and Commonality Variability Analysis whitepaper  
[http://www.netobjectives.net/files/pdfs/Introduction_CommonalityVariabilityAnalysis.pdf]  
(NetObjectives).

Hijax  
[https://domscripting.com/blog/display/41]  
(Jeremy Keith)

The Web Standards Project WaSP  
[http://www.webstandards.org/]
