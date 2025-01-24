```
field/Flag ( checkbox(BOOL) )
field/Input ( text(CHAR|VARCHAR), time(TIME), datetime-local(DATETIME), date(DATE), text(TINYTEXT), color(CHAR(6):HEX), url(VARCHAR), month(TINYINT:1-12), password(VARCHAR), number(INT|FLOAT), tel(VARCHAR), email(VARCHAR), textarea(TEXT), range(min:INT,max:INT) ) + datalist:SUGESTIONS
field/SelectOne ( type(URN:VARCHAR), status(enum:INT), list(iOption:id:VARCHAR|radio-list), rating(INT) )
field/SelectMany ( iOption(REVERSE_LOOKUP, LOOKUP_TABLE) list(iOption:id:VARCHAR|checkbox-list) )
```
```
n/a: button|
action: search
complex: file | image
control: reset | submit
flag/selectFrom: radio | checkbox
```
```
VARCHAR (text|email|tel|url|color|password)
TIME(time)
DATE(date)
DATATIME(datetime-local:converted:UTC)
TINYINT(number|month|week)
```
```
range
hidden
```
```
field/AddMany?
```
Database:
--------
```
CHAR(length) Fixed length  as Char
VARCHAR(maxlength)         as VarChar
TINYTEXT Upto 255 chars    as TinyText
TEXT(size)                 as Text
BOOL or BOOLEAN            as Flag
TINYINT				as 
SMALLINT                   as Integer
FLOAT(p)                   as DecimalPointNumber
DATE                       as Date
TIME                       as Time
DATETIME                   as DateTime
YEAR                       as Year

UNIQUE                     as UniquePrimaryKey
```
```
<<abstract>> ValidationRule
<<abstract>> DBTypeValidation
Unique
```
