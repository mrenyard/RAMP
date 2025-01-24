```
object (self)
```
```html
  <fieldset id="object:id">
    <label id="object:id:property" ...>
     select-one <input with list or select>
     select-many checkboxes
  </fieldset>
```
object relationships
--------------------
```
one-to-one i.e. homeAddress -> Address
                uniAddress -> Address
```
```html
  <fieldset id="address:id" class="record">
    <legend>Home Address</legend>
```
```
one-to-many i.e. oranisation.employees (org-person-lookup) (many-to-many)
```
```html
  <section id="oranisation:id:employees" class="record-collection">
    <header><h3>Employees</h3></header>
    <fieldset id="person:id1" ...>
    <fieldset id="person:id2" ...>
```
```
person:mrenyard (0)
  person:mrenyard:property=value (1)
  person:mrenyard:select-one (1)
    from avalible/add (2)
    = value (2)
  person:mrenyrd:select-many (1)
    from avalible/add (2)
    = values (2)
  person:mrenyard:home-address (1)
    = (address:id) (2)
      address:pk:property=value (3)
      address:pk:select-one (3)
        from avalible/add (4)
        = value (4)
      address:pk:select-many (3)
        from avalible/add (4)
        = values (4)
  person:mrenyard:family (1)
    = (lookup-many -> personCollection:) (2)
      person:pk (3)
        person:pk:propery=value (4)
        person:pk:select-one (4)
          from avalible/add (5)
          = value (5)
        person:pk:select-many (4)
          from avalible/add (5)
          = values (5)
```
