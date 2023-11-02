RelationToOne (Str $name, Record $parent, Str $withRecordName)
RelationToMany(Str $name, Record $parent, Str $withRecordName, Str $withPropertyName)
RelationLookup(Str $name, Record $parent, Str $withRecordName, Str $usesLookupTable, Str $toLookupProperty)

Record->primaryKey(new) | RecordFromKey
RecordCollection + [last]Record->primaryKey(new)
RecordCollection + [last]Record->primaryKey(new)

Person->homeAddess (countryCode, PostCode, UDP)
------------------
Person::FK_homeAddress_Address_countryCode
Person::FK_homeAddress_Address_postCode
Person::FK_homeAddress_Address_deliveryPiontSufex

Relation('homeAddress', :Person, 'Address');
RelationToOne('homeAddress', :Person, 'Address');

Address->owner
--------------
Address::FK_owner_Person_uname?type=owner

Relation('owners', Address, PersonCollection);
RelationLookup('owner', :Address, 'Person', 'LOOKUP_Person_Address', Filter::type=ownership);
RelationLookup('occupants', :Address, 'Person', 'LOOKUP_Person_Address', 'fk_Person');
RelationLookup('residents', :Person, 'Address', 'LOOKUP_Person_Address', 'fk_Address');

Person->addresses
-----------------
LOOKUP_Person_Address::FK_Person_uname
LOOKUP_Person_Address::FK_Address_countryCode
LOOKUP_Person_Address::FK_Address_postCode
LOOKUP_Person_Address::FK_Address_deliveryPiontSufex

Relation('addresses', Person, AddressCollection)
RelationLookup('addresses', :Person, LOOKUP_Person_Address)

Person->bankAccounts
--------------------
BankAccount::FK_holder_Person_uname

Relation('bankAccounts', Person, BankAccountCollection);
RelationToMany('bankAccounts', :Person, 'BankAccount', 'holder');

BankAccount->holder
-------------------
BankAccount::FK_holder_Person_uname

Relation('holder', BankAccount, Person)
RelationToOne('holder', BankAccount, 'Person')

