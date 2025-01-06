FUNC.init (`init.js`|`init-full.js`)
==================================================================
Base Classes (Base, Enum, Exception, Collection) and the INIT System.
==================================================================

**FUNC init file includes definitions for its commonly used Base,
Enum, Exception and Collection Classes as well as the init system.
A system that ensure per page, dom spesific, modules are requested
and loaded along with any required libaraies on a per nessasary bases.**

 * @author Matt Renyard (twitter: @mrenyard)
 * @package func.Base (Base Class)
 * @package func.ENUM
 * @package func.Exception
 * @package func.Collection
 * @package func.init

Using Namespaces within your local namespace.
--------------------------------------------------
```javascript
FUNC.[newModule] = function(elm)
{
  //- USE (dependant namespaces)
  var _ = FUNC.[newModule:current],
      N = FUNC.Enum,
      Ex = FUNC.Exception,
      List = FUNC.Collection,
      core = FUNC.core;
  ...

  _.[globalNamespace:function] = function(param, ...) {
    ...
  }

  var type = new N('{:string}', '{:string}', '{:string}', '{:string}')

  try {
    ...
  } catch () {
    throw new Ex(Ex.BADMETHODCALL, '{message:string}')
  }

  core.addSection({title:string}, [{type:string}, {type:string}, ...], {moduleName:string});

  ...
}
```

FUNC.Base Abstract Class Inherited Universally.
--------------------------------------------------

### An &laquo;abstract&raquo; class inherited from FUNC.Base

To indicate that a class in abstract use the following declaration
within you constructor imediatly after super(). The fist action
within any constructor, being to call super().
```javascript
  const MyClass = class extends FUNC.Base
  {
    // ...
    constructor(paramOne) { super(); this.abstract('MyClass');
      // ...
    }
  }
```
```javascript
  //- LOCAL CLASSES.
  const MyAbstractClass = class extends FUNC.Base
  {
    #propertyOne;
    #propertyTwo;
    constructor(paramOne) { super(); this.abstract('MyClass');
      this.#propertyOne = [];
      this.#propertyTwo = paramOne;
      this.method();
    }
    get propertyOne() { return this.#propertyOne; }
    get propertyTwo() { return this.#propertyTwo; }
    set propertyTwo(v) { this.#propertyTwo = v; }
    method() { /* do somethiong... */}
  };

const MyConcreteClass = class extends MyAbstractClass
  {
    #propertyThree;
    constructor(parameOne, paramThree) {
      super(paramOne);
      this.#propertyThree = paramThree;
    }
    get propertyTree() { return this.#propertyTree; }
    secondMethod() { /* do somethng else... */}
  }
```

### A Concrete class inheriting from FUNC.Base
```javascript
  //- LOCAL CLASSES.
  const MyAbstractClass = class extends FUNC.Base
  {
    #propertyOne;
    #propertyTwo;
    constructor(paramOne) { super();
      this.#propertyOne = [];
      this.#propertyTwo = paramOne;
      this.method();
    }
    get propertyOne() { return this.#propertyOne; }
    get propertyTwo() { return this.#propertyTwo; }
    set propertyTwo(v) { this.#propertyTwo = v; }
    method() { /* do somethiong... */}
  };
```


FUNC.Enum Created from List of Arguments.
--------------------------------------------------

### Declaring a new Enum
```javascript
var pets = new Enum('Cat', 'Dog', 'Fish', 'Hamster', 'Cat Fish');
```
### Using Enum.at() for both number and name access.
```javascript
pets.at('Dog'); // returns {name:'dog', value:2}
pets.at(2); // returns {name:'dog', value:2}
```
### Enum.count
```javascript
pets.count; // 5
```

FUNC.Exception Types; Throw, Catch and Handling 
--------------------------------------------------
The Exception class is use to handle 4 primary exception
types, they are:
- BadMethodCall
- OutOfBounds
- UnexpectedArgument
- UndeclaredClass

...

FUNC.Collection Interation, Addition inc from DOM. 
--------------------------------------------------

### Creating a Collection from HTMLCollection
```javascript
var List = FUNC.Collection;
List.createFrom(document.getElementsByClassName(...));
```

### Alternatively add to collection manually.
```javascript
let collection = new List();
let collection.add(document.getElementById(...));
```

### Use iterator pattern on collections.
```javascript
let collection.rewind();
while(collection.next) { let o = collection.current;
  ...
}
```

Useful and Related Content
--------------------------------------------------

