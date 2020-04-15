USE `svelte_db`;

--
-- Dumping data for table `Person`
--
-- `uname`, `givenName`, `familyName`, `honorificPrefix`
INSERT INTO `Person`
(`uname`, `givenName`, `familyName`, `honorificPrefix`) VALUES
('arenyard', 'Alex', 'Renyard', 'Miss'),
('billo', 'Bill', 'Oddy', 'Mr'),
('crenyard', 'Claire', 'Renyard', 'Mrs'),
('drenyard', 'Derek', 'Renyard', 'Mr'),
('ereilly', 'Tim', 'eReilly', 'Mr'),
('fredflint', 'Fred', 'Flintstone', 'Mr'),
('grenyard', 'Gail', 'Renyard', 'Mrs'),
('hhill', 'Harry', 'Hill', 'Mr'),
('ismith', 'Ian', 'Smith', 'Mr'),
('jrenyard', 'Julie', 'Renyard', 'Mrs'),
('kosbourn', 'Kelly', 'Osbourn', 'Miss'),
('lrenyard', 'Linda', 'Renyard', 'Ms'),
('mrenyard', 'Matt', 'Renyard', 'Mr');


-- `email`, `typeID`, `password`
INSERT INTO `Account`
(`auPK`, `email`, `accountTypeID`, `password`) VALUES
('arenyard', 'arenyard@gmail.co.uk', 2, 'sl0N1Oj5JS0pw'),
('billo', 'bill@gmail.co.uk', 3, 'sl0N1Oj5JS0pw'),
('crenyard', 'crenyard@gmail.co.uk', 5, 'sl0N1Oj5JS0pw'),
('drenyard', 'drenyard@gmail.co.uk', 3, 'sl0N1Oj5JS0pw'),
('ereilly', 'ereilly@gmail.com', 3, 'sl0N1Oj5JS0pw'),
('fredflint', 'Fred@gmail.co.uk', 2, 'sl0N1Oj5JS0pw'),
('grenyard', 'grenyard@gmail.co.uk', 4, 'sl0N1Oj5JS0pw'),
('hhill', 'hhill@gmail.co.uk', 1, 'sl0N1Oj5JS0pw'),
('ismith', 'ismith@gmail.co.uk', 2, 'sl0N1Oj5JS0pw'),
('jrenyard', 'jrenyard@gmail.co.uk', 3, 'sl0N1Oj5JS0pw'),
('kosbourn', 'kosbourn@gmail.co.uk', 1, 'sl0N1Oj5JS0pw'),
('lrenyard', 'lrenyard@gmail.co.uk', 3, 'sl0N1Oj5JS0pw'),
('mrenyard', 'mrenyard@gmail.co.uk', 6, 'sl0N1Oj5JS0pw');

