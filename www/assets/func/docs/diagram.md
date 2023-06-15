FUNC.diagram.js
==================================================================
Manipulate, Draw and Arrange a Range of Diagrams
==================================================================

**FUNCs Diagram module adds interactivity and commodity to a
range of commonly used popular developer centric diagrams.**

 * @author Matt Renyard (twitter: @mrenyard)
 * @package func.diagram
 * @depends func.core
 * @depends CSS module-diagram.css

Build, edit and arrange Unified Modeling Language diagram
types such as *Class*, *Sequence*, *Activity* and *Use Case*
as well as *Entity Relationship Diagrams* for databases.

Module Application Programming Interface
--------------------------------------------------
```javascript
FUNC.diagram.types;
```
```javascript
FUNC.diagram.add(typeN, title);
```
```javascript
FUNC.my.diagram[i].type;
```
```javascript
FUNC.my.diagram[i].view;
```
```javascript
FUNC.my.diagram[i].views;
```
```javascript
FUNC.my.diagram[i].shapes;
```
```javascript
FUNC.my.diagram[i].connections;
```
```javascript
FUNC.my.diagram[i].components;
```
```javascript
FUNC.my.diagram[i].addShape(typeN, title);
```
```javascript
FUNC.my.diagram[i].save();
```

Instantiable Classes
--------------------------------------------------
### ClassDiagram
### SequanceDiagram
### DatabaseDiagram

### Class
### Entity

### Association

Expected DOM (HTML) Templates for Preloading Data
--------------------------------------------------
### Diagram boundary
Each Diagram boundry should appear as a `[HtmlElement:section]`
within the `[HtmlElement:#main]` with varable values for the
diagram 'Title', 'Type' and 'Veiw Variant'.

 * Type: ('uml-class', 'uml-sequance', 'uml-usecase', 'uml-action', 'erd-database')
 * Veiw Variants:
   - ['uml-class'] ('detail', 'comparison', 'abstract', 'compact')
   - ['uml-sequance'] ('detail')
   - ['uml-usecase'] ('detail')
   - ['uml-action'] ('detail')
   - ['erd-database'] ('detail')

```html
    <div id="main" role="main">
      <header><h1>Page Main Heading</h1></header>
      ...
      <section id="[diagram-id:title]" class="diagram [diagram-type] view-[view-variant]">
        <header><h2>[DiagramId:title]</h2></header>
        <div class="canvas">
          ...
        </div>
      </section>
      ...
    </div>
```
### Shapes
Every [HtmlElement:article] represents a single 'Shape' on our diagram canvas.
```html
      <section id="[diagram-id:title]" class="diagram [diagram-type] view-[view-type]">
        ...
        <div class="canvas">
          <article id="[shape-title:id]" class="[shape-type] [shape-variant]">
            <header contenteditable="true"><h3 title="[shapeType]: [shapeTitle]">[shapeTitle]</h3></header>
          </article>
          ...
        </div>
      </section>
```
### UML Class
UML Classes are formated as below 
```html
        <div class="canvas">
          <article id="[class-title:id]" class="class [abstract]">
            <header contenteditable="true"><h3 title="Class: [classTitle]">[classTitle]</h3></header>
            <ol>
              <li class="properties"><h4>Properties</h4>
                <ul contenteditable="true">
                  <li>{propertyName}: {dataType}</li>
                  <li>...</li>
                </ul>
              </li>
              <li class="methods"><h4>Methods</h4>
                <ul contenteditable="true">
                  <li>{methodName}({dataType} {param}): {returnType}</li>
                  <li>...</li>
                </ul>
              </li>
              <li class="associations"><h4>Associations</h4>
                <ul>
                  <li class="[associationType]"><a href="#{associated-class}">{associatedClass} ([associationType])</a></li>
                  <li class="[associationType]"><a href="#{associated-class}">{associatedClass} ([associationType])</a><em class="label">[associationLabel]</em></li>
                  <li>...</li>
                </ul>
              </li>
            </ol>
          </article>
          ...
        </div>
```
Useful and Related Content
--------------------------------------------------
 - [Using Namspace placeholders](./core.md#using-namespaces-within-your-local-namespace).

