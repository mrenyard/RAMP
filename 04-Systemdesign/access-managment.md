RAMP ACCESS
-----------

1) :REGISTERED
2) :CUSTOMER
3) :AFFILIATE (~ADMINISTRATOR)
4) :ADMINISTRATOR
5) :ADMINISTRATOR_MANAGER
6) :SYSTEM_ADMINISTRATOR

DATA ACCESS
-----------
```
owner|group|all

owner = ~ (AuthenticatableUnit)
group = LoginAccountType
registereds =

READ|EDIT

Person
 owner = YES|YES
 group = >=4 (3 or ~Affiliate)|6
 all   = NO|NO
 
Address
 owner = YES|YES
 group = YES|YES
 all   = YES|NO

CRAFT
 owner = YES|ON
 group = >=4 (or ~Affiliate)|>=4 (or ~Affiliate)
 all   = NO|NO
 
Warrenty
 owner = YES|NO
 group = >=4 (or ~Affilate)|>=4
 all   = NO|NO
 
AdministratorAccount
 owner = YES|NO
 group = >=5|>=6
 all   = NO|NO
 
 Products
  owner = YES|YES
  group = >=1|>=4
  all   = NO|NO
```
DATABASE_TABLE: ResourceAccess
------------------------------
```
URN                 = person
ownerAccess         = NO       0
ownerEdit           = NO       0
groupLevelAccess    = 4        4
groupLevelEdit      = 5        5
ownerAffliateAccess = YES      1
ownerAffilateEdit   = NO       0
allAccess           = NO       0
allEdit             = NO       0
code                = 00451000
```
```
URN                 = person:~
ownerAccess         = YES      1
ownerEdit           = YES      1
groupLevelAccess    = 4        4
groupLevelEdit      = 6        6
ownerAffliateAccess = YES      1
ownerAffilateEdit   = YES      1
allAccess           = NO       0
allEdit             = NO       0
code                = 11461100
```
```
URN                 = craft:new
ownerAccess         = YES      1
ownerEdit           = YES      1
groupLevelAccess    = 2        2
groupLevelEdit      = 2        2
ownerAffliateAccess = YES      1
ownerAffilateEdit   = YES      1
allAccess           = NO       0
allEdit             = NO       0
code                = 11221100
```
```
URN                 = craft:~
ownerAccess         = YES      1
ownerEdit           = NO       0
groupLevelAccess    = 2        2
groupLevelEdit      = 4        4
ownerAffliateAccess = YES      1
ownerAffilateEdit   = NO       0
allAccess           = NO       0
allEdit             = NO       0
code                = 1024100
```
