`person?family-name=Renyard|Walker&given-name|not=Matt|John&age|gt=24&age|lt=76`
```
person?
  family-name=
    Renyard|Walker
  &given-name|not=
    Matt|John
  &age|gt=
    24
  &age|lt=
    76
```
Working age decendants for the Walker-Renyard line
excluding those with the given-name of Matt or John
```
SELECT * FROM Person
  WHERE
    familyName = Renyard OR familyName = Walker
    AND givenName <> Matt
    AND givenName <> John
    AND age > 24
    AND age < 76;
```
