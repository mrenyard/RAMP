DROP TABLE IF EXISTS MockMinRecord;

CREATE TABLE MockMinRecord (
  key1 text NOT NULL,
  key2 text NOT NULL,
  key3 text NOT NULL,
  property1 text NULL,
  property2 text NULL,
  PRIMARY KEY(key1, key2, key3)
);

INSERT INTO MockMinRecord (key1, key2, key3, property1, property2)
VALUES  ('A', 'A', 'A', 'valueA','valueB'),
		('A', 'A', 'B', 'Avalue','valueB'),
		('A', 'A', 'C', 'valueA','valueB'),
		('A', 'A', 'D', 'Avalue','valueB');

SELECT * FROM MockMinRecord;