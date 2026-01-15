
USE `ramp_db`;

-- -----------------------------------------------------
-- Data for table `Person`
-- -----------------------------------------------------

INSERT INTO `Person` ( `uname`,`email`,`honorificPrefix`,`givenName`,`additionalNames`,`familyName`,`honorificSuffix`,
`FK_homeAddress_countryCode`,`FK_homeAddress_postalCode`,`FK_homeAddress_deliveryPointSuffix`) VALUES
('mrenyard', 'renyard.m@gmail.com', 'Mr', 'Matt', NULL, 'Renyard', NULL, 'GB', 'SO16 8EL', 'T1'),
('anprenyard', 'alex.renyard@gmail.com', 'Mr', 'Alex', NULL, 'Renyard', NULL, 'GB', 'SO16 8EL', 'T1');

-- -----------------------------------------------------
-- Data for table `Address`
-- -----------------------------------------------------
INSERT INTO `Address` (`countryCode`,`postalCode`,`deliveryPointSuffix`,`subBuildingName`,`buildingName`,`buildingNumber`,
`organisationName`,`departmentName`,`PoBoxNumber`,`dependentThoroughfare`,`thoroughfare`,`doubleDependentLocality`,`dependentLocality`,`PostTown`) VALUES
('GB', 'SO16 8EL', 'T1', NULL, NULL, '157', NULL, NULL, NULL, NULL, 'Oakwood Drive', NULL, NULL, 'SOUTHAMPTON'),
('GB', 'SO16 8EL', 'T2', NULL, NULL, '155', NULL, NULL, NULL, NULL, 'Oakwood Drive', NULL, NULL, 'SOUTHAMPTON'),
('GB', 'SO16 8EL', 'J1', NULL, NULL, '159', 'Justified Ltd', NULL, NULL, NULL, 'Oakwood Drive', NULL, NULL, 'SOUTHAMPTON');

-- -----------------------------------------------------
-- Data for table `LOOKUP_Person_Address`
-- -----------------------------------------------------
INSERT INTO `LOOKUP_Person_Address` (`FK_Person_uname`,`type`,`FK_Address_countryCode`,`FK_Address_postalCode`,`FK_Address_deliveryPointSuffix`) VALUES
('mrenyard', 'HOME', 'GB','SO16 8EL', 'T1'),
('mrenyard', 'BUSINESS', 'GB','SO16 8EL', 'J1'),
('anprenyard', 'PARENTAL-HOME', 'GB','SO16 8EL', 'T1'),
('anprenyard', 'STUDY-RESIDENCE',  'GB','SO16 8EL', 'T2');
