```
 _________________________________________________________
 RECORD COLLECTION WITH FILTER                    FIGURE-1
 ---------------------------------------------------------
        VIEW-ID                FILTER-COLLECTION
  _________^_________  ________________^_________________
 |                   ||                                  |
 | CONTROLLER  MODEL ||                        FILTER    |
  _____^____  ___^___ |                    ______^_______
 |          ||  R-C  ||   NAME     VALUE  |              |
 |          || __^__ | ____^____   __^__  |              |
 |          |||     |||         | |     | |              |

 /[controller]/person?family-name=Renyard&given-name=Matt

 [R-C] = RECORD-COLLECTION
 _________________________________________________________
```
```
 ______________________________
 SINGLE RECORD         FIGURE-2
 ------------------------------
             VIEW-ID
  _____________^______________
 |                            |
 |                  MODEL     |
 |CONTROLLER  ________^_______
  _____^____ |                |
 |          || RECORD   KEY   |
 |          || __^__  __ ^___ |
 |          |||     ||       ||

 /[controller]/person/mrenyard/
 ______________________________
```
```
 __________________________________________
 PROPERTY OF RECORD                FIGURE-3
 ------------------------------------------
                  VIEW-ID
  ___________________^____________________
 |                                        |
 |                        MODEL           |
 |            ______________^_____________
 |CONTROLLER |                            |
  _____^____ | RECORD   KEY     PROPERTY  |
 |          || __^__  ___^___  _____^____ |
 |          |||     ||       ||          ||

 /[controller]/person/mrenyard/family-name/
 __________________________________________
```
```
viewId

filterCollection
  filter
    name
    value
```
```
model (recordType, key = null, propertyName = null)
  recordName
  recordId
  propertyName
```
