-- Driver Name Restoration SQL
-- Generated: 2026-04-10 01:28:26

SET FOREIGN_KEY_CHECKS = 0;

-- Row 1: Plate=AAK 4591 | SetA=Duero, Jesus | SetB=Duero, Jesus
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Jesus', d.last_name = 'Duero'
  WHERE u.plate_number = 'AAK 4591' AND u.deleted_at IS NULL;

-- Row 2: Plate=AAK 9196 | SetA=NAFTM | SetB=NAFTM
-- Row 3: Plate=AAQ 1743 | SetA=NAFTM | SetB=NAFTM
-- Row 4: Plate=ABF 7471 | SetA=Genchez, Randy | SetB=Genchez, Randy
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Randy', d.last_name = 'Genchez'
  WHERE u.plate_number = 'ABF 7471' AND u.deleted_at IS NULL;

-- Row 5: Plate=ABG 7479 | SetA=Untal, Sanjali | SetB=Untal, Sanjali
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Sanjali', d.last_name = 'Untal'
  WHERE u.plate_number = 'ABG 7479' AND u.deleted_at IS NULL;

-- Row 6: Plate=ABL 1667 | SetA=Dimanda, Norodin | SetB=Dimanda, Norodin
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Norodin', d.last_name = 'Dimanda'
  WHERE u.plate_number = 'ABL 1667' AND u.deleted_at IS NULL;

-- Row 7: Plate=ABL 6901 | SetA=NAFTM | SetB=NAFTM
-- Row 8: Plate=ABF 2705 | SetA=NAD | SetB=NAD
-- Row 9: Plate=ABP 7643 | SetA=Belen, Henry | SetB=Belen, Henry
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Henry', d.last_name = 'Belen'
  WHERE u.plate_number = 'ABP 7643' AND u.deleted_at IS NULL;

-- Row 10: Plate=ACH 5774 | SetA=Azarcon, Arwin | SetB=Azarcon, Arwin
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Arwin', d.last_name = 'Azarcon'
  WHERE u.plate_number = 'ACH 5774' AND u.deleted_at IS NULL;

-- Row 11: Plate=ADY 2597 | SetA=NAFTM | SetB=NAFTM
-- Row 12: Plate=ADY 2598 | SetA=Rodriguez, Arvy | SetB=Rodriguez, Arvy
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Arvy', d.last_name = 'Rodriguez'
  WHERE u.plate_number = 'ADY 2598' AND u.deleted_at IS NULL;

-- Row 13: Plate=ADY 2599 | SetA=NAFTM | SetB=NAFTM
-- Row 14: Plate=AEA 9630 | SetA=NAFTM | SetB=NAFTM
-- Row 15: Plate=ALA 3699 | SetA=NAFTM | SetB=NAFTM
-- Row 16: Plate=AOA 8917 | SetA=Kalaing, Bensar | SetB=Kalaing, Bensar
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Bensar', d.last_name = 'Kalaing'
  WHERE u.plate_number = 'AOA 8917' AND u.deleted_at IS NULL;

-- Row 17: Plate=ASA 6135 | SetA=Camillotes, Jose | SetB=Camillotes, Jose
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Jose', d.last_name = 'Camillotes'
  WHERE u.plate_number = 'ASA 6135' AND u.deleted_at IS NULL;

-- Row 18: Plate=CAT 6073 | SetA=Ferrer, Jamie | SetB=Ferrer, Jamie
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Jamie', d.last_name = 'Ferrer'
  WHERE u.plate_number = 'CAT 6073' AND u.deleted_at IS NULL;

-- Row 19: Plate=CAV 2607 | SetA=Sumando, Joel | SetB=Sumando, Joel
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Joel', d.last_name = 'Sumando'
  WHERE u.plate_number = 'CAV 2607' AND u.deleted_at IS NULL;

-- Row 20: Plate=CAV 6803 | SetA=Ramos, Virgilio | SetB=Defeo, Dindo
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Virgilio', d.last_name = 'Ramos'
  WHERE u.plate_number = 'CAV 6803' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Dindo', d.last_name = 'Defeo'
  WHERE u.plate_number = 'CAV 6803' AND u.deleted_at IS NULL;

-- Row 21: Plate=CAV 9662 | SetA=Gudran, Rodel | SetB=Gundran, Rodel
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Rodel', d.last_name = 'Gudran'
  WHERE u.plate_number = 'CAV 9662' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Rodel', d.last_name = 'Gundran'
  WHERE u.plate_number = 'CAV 9662' AND u.deleted_at IS NULL;

-- Row 22: Plate=CAV 9716 | SetA=Taboada, Angelo | SetB=Reponte, Virgilio
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Angelo', d.last_name = 'Taboada'
  WHERE u.plate_number = 'CAV 9716' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Virgilio', d.last_name = 'Reponte'
  WHERE u.plate_number = 'CAV 9716' AND u.deleted_at IS NULL;

-- Row 23: Plate=CAX 5430 | SetA=Andrade, Elmer | SetB=Andrade, Elmer
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Elmer', d.last_name = 'Andrade'
  WHERE u.plate_number = 'CAX 5430' AND u.deleted_at IS NULL;

-- Row 24: Plate=CBM 1979 | SetA=Evangilista, Felimon | SetB=Fernandez, Norlando
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Felimon', d.last_name = 'Evangilista'
  WHERE u.plate_number = 'CBM 1979' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Norlando', d.last_name = 'Fernandez'
  WHERE u.plate_number = 'CBM 1979' AND u.deleted_at IS NULL;

-- Row 25: Plate=DAD 7555 | SetA=Castro, Nelson | SetB=Castro, Nelson
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Nelson', d.last_name = 'Castro'
  WHERE u.plate_number = 'DAD 7555' AND u.deleted_at IS NULL;

-- Row 26: Plate=DAJ 7468 | SetA=Bautista, Willy | SetB=Bautista, Willy
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Willy', d.last_name = 'Bautista'
  WHERE u.plate_number = 'DAJ 7468' AND u.deleted_at IS NULL;

-- Row 27: Plate=DAT 1367 | SetA=Cadalzo, Ramil | SetB=Cadalzo, Ramil
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Ramil', d.last_name = 'Cadalzo'
  WHERE u.plate_number = 'DAT 1367' AND u.deleted_at IS NULL;

-- Row 28: Plate=DAT 2657 | SetA=Lamigo, Freddie | SetB=Lamigo, Freddie
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Freddie', d.last_name = 'Lamigo'
  WHERE u.plate_number = 'DAT 2657' AND u.deleted_at IS NULL;

-- Row 29: Plate=DAU 9027 | SetA=Pajanilla, Erwin | SetB=Norombaba, Roel
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Erwin', d.last_name = 'Pajanilla'
  WHERE u.plate_number = 'DAU 9027' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Roel', d.last_name = 'Norombaba'
  WHERE u.plate_number = 'DAU 9027' AND u.deleted_at IS NULL;

-- Row 30: Plate=DAZ 9769 | SetA=Peñol, Roel | SetB=Tresvalles, Domingo
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Roel', d.last_name = 'Peñol'
  WHERE u.plate_number = 'DAZ 9769' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Domingo', d.last_name = 'Tresvalles'
  WHERE u.plate_number = 'DAZ 9769' AND u.deleted_at IS NULL;

-- Row 31: Plate=DBA 1887 | SetA=Miranda, Simeon | SetB=Miranda, Simeon
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Simeon', d.last_name = 'Miranda'
  WHERE u.plate_number = 'DBA 1887' AND u.deleted_at IS NULL;

-- Row 32: Plate=DBA 2302 | SetA=Sitoy, Carlito | SetB=Baja, Francisco
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Carlito', d.last_name = 'Sitoy'
  WHERE u.plate_number = 'DBA 2302' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Francisco', d.last_name = 'Baja'
  WHERE u.plate_number = 'DBA 2302' AND u.deleted_at IS NULL;

-- Row 33: Plate=DBA 5420 | SetA=Cabales, Juanito | SetB=Cabales, Juanito
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Juanito', d.last_name = 'Cabales'
  WHERE u.plate_number = 'DBA 5420' AND u.deleted_at IS NULL;

-- Row 34: Plate=DCQ 1551 | SetA=Monarba, Almar | SetB=Monarba, Almar
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Almar', d.last_name = 'Monarba'
  WHERE u.plate_number = 'DCQ 1551' AND u.deleted_at IS NULL;

-- Row 35: Plate=EAA 4540 | SetA=Juluat, Nelson | SetB=Juluat, Nelson
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Nelson', d.last_name = 'Juluat'
  WHERE u.plate_number = 'EAA 4540' AND u.deleted_at IS NULL;

-- Row 36: Plate=EAA 9555 | SetA=Laya, Aldrin | SetB=Laya, Aldrin
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Aldrin', d.last_name = 'Laya'
  WHERE u.plate_number = 'EAA 9555' AND u.deleted_at IS NULL;

-- Row 37: Plate=EAB 8186 | SetA=Pabalate, Elmar | SetB=Pabalate, Elmar
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Elmar', d.last_name = 'Pabalate'
  WHERE u.plate_number = 'EAB 8186' AND u.deleted_at IS NULL;

-- Row 38: Plate=EAD 7438 | SetA=NAD | SetB=NAD
-- Row 39: Plate=EAE 1247 | SetA=Ostonal, Agapito | SetB=Singalawa, Melencio
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Agapito', d.last_name = 'Ostonal'
  WHERE u.plate_number = 'EAE 1247' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Melencio', d.last_name = 'Singalawa'
  WHERE u.plate_number = 'EAE 1247' AND u.deleted_at IS NULL;

-- Row 40: Plate=EAE 1919 | SetA=Trinidad, Efren | SetB=Sanchez, Rogelio
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Efren', d.last_name = 'Trinidad'
  WHERE u.plate_number = 'EAE 1919' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Rogelio', d.last_name = 'Sanchez'
  WHERE u.plate_number = 'EAE 1919' AND u.deleted_at IS NULL;

-- Row 41: Plate=EAE 4949 | SetA=Fontanilla, Michael | SetB=Domingo, Wilfredo
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Michael', d.last_name = 'Fontanilla'
  WHERE u.plate_number = 'EAE 4949' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Wilfredo', d.last_name = 'Domingo'
  WHERE u.plate_number = 'EAE 4949' AND u.deleted_at IS NULL;

-- Row 42: Plate=EAE 5883 | SetA=Tangginog, Yasse | SetB=Tangginog, Dayanodin
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Yasse', d.last_name = 'Tangginog'
  WHERE u.plate_number = 'EAE 5883' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Dayanodin', d.last_name = 'Tangginog'
  WHERE u.plate_number = 'EAE 5883' AND u.deleted_at IS NULL;

-- Row 43: Plate=EAF 6347 | SetA=Uyangorin, Domingo | SetB=Uyangorin, Domingo
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Domingo', d.last_name = 'Uyangorin'
  WHERE u.plate_number = 'EAF 6347' AND u.deleted_at IS NULL;

-- Row 44: Plate=EAF 7245 | SetA=Cuevas, Ricardo | SetB=Cuevas, Ricardo
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Ricardo', d.last_name = 'Cuevas'
  WHERE u.plate_number = 'EAF 7245' AND u.deleted_at IS NULL;

-- Row 45: Plate=NAC 4989 | SetA=Matallano, Gerse | SetB=Matallino, Gerse
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Gerse', d.last_name = 'Matallano'
  WHERE u.plate_number = 'NAC 4989' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Gerse', d.last_name = 'Matallino'
  WHERE u.plate_number = 'NAC 4989' AND u.deleted_at IS NULL;

-- Row 46: Plate=NAD 1140 | SetA=NAFTM | SetB=NATFM
-- Row 47: Plate=NAE 7193 | SetA=Kaiting, Ibrahim | SetB=Kaiting, Ibrahim
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Ibrahim', d.last_name = 'Kaiting'
  WHERE u.plate_number = 'NAE 7193' AND u.deleted_at IS NULL;

-- Row 48: Plate=NAM 1610 | SetA=NAD | SetB=NAD
-- Row 49: Plate=NBR 1341 | SetA=Malunes, Felimon | SetB=Malunes, Felimon
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Felimon', d.last_name = 'Malunes'
  WHERE u.plate_number = 'NBR 1341' AND u.deleted_at IS NULL;

-- Row 50: Plate=NBW 7071 | SetA=Makapundag, Alkisar | SetB=Makapundag, Alkisar
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Alkisar', d.last_name = 'Makapundag'
  WHERE u.plate_number = 'NBW 7071' AND u.deleted_at IS NULL;

-- Row 51: Plate=NBX 4348 | SetA=Gundran, Mark Lester | SetB=Gundran, Mark Lester
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Mark Lester', d.last_name = 'Gundran'
  WHERE u.plate_number = 'NBX 4348' AND u.deleted_at IS NULL;

-- Row 52: Plate=NCJ 7661 | SetA=NAD | SetB=NAD
-- Row 53: Plate=NCN 8583 | SetA=Nur, Radzmil | SetB=Nur, Radzmil
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Radzmil', d.last_name = 'Nur'
  WHERE u.plate_number = 'NCN 8583' AND u.deleted_at IS NULL;

-- Row 54: Plate=NCW 5011 | SetA=Patajo, Ruben | SetB=Patajo, Ruben
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Ruben', d.last_name = 'Patajo'
  WHERE u.plate_number = 'NCW 5011' AND u.deleted_at IS NULL;

-- Row 55: Plate=NDA 5429 | SetA=Ubag, Paulo | SetB=Ubag, Paulo
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Paulo', d.last_name = 'Ubag'
  WHERE u.plate_number = 'NDA 5429' AND u.deleted_at IS NULL;

-- Row 56: Plate=NDA 8102 | SetA=NAD | SetB=NAD
-- Row 57: Plate=NAD 8102 | SetA=Ayag, Lito | SetB=Opeña, Mario
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Lito', d.last_name = 'Ayag'
  WHERE u.plate_number = 'NAD 8102' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Mario', d.last_name = 'Opeña'
  WHERE u.plate_number = 'NAD 8102' AND u.deleted_at IS NULL;

-- Row 58: Plate=NDA 8106 | SetA=Orias, Wilfredo | SetB=Orias, Wilfredo
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Wilfredo', d.last_name = 'Orias'
  WHERE u.plate_number = 'NDA 8106' AND u.deleted_at IS NULL;

-- Row 59: Plate=NDC 7363 | SetA=Laurente, R | SetB=Laurente, R
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'R', d.last_name = 'Laurente'
  WHERE u.plate_number = 'NDC 7363' AND u.deleted_at IS NULL;

-- Row 60: Plate=NDG 7105 | SetA=Ramber, Javier | SetB=Ramber, Javier
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Javier', d.last_name = 'Ramber'
  WHERE u.plate_number = 'NDG 7105' AND u.deleted_at IS NULL;

-- Row 61: Plate=NDI 2585 | SetA=Ausa, Felix | SetB=Penaflor, Joseph
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Felix', d.last_name = 'Ausa'
  WHERE u.plate_number = 'NDI 2585' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Joseph', d.last_name = 'Penaflor'
  WHERE u.plate_number = 'NDI 2585' AND u.deleted_at IS NULL;

-- Row 62: Plate=NEA 1292 | SetA=Manalo, Victor | SetB=Manalo, Victor
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Victor', d.last_name = 'Manalo'
  WHERE u.plate_number = 'NEA 1292' AND u.deleted_at IS NULL;

-- Row 63: Plate=NEF 4940 | SetA=Sunico, July | SetB=Sunico, Roberto
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'July', d.last_name = 'Sunico'
  WHERE u.plate_number = 'NEF 4940' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Roberto', d.last_name = 'Sunico'
  WHERE u.plate_number = 'NEF 4940' AND u.deleted_at IS NULL;

-- Row 64: Plate=NEI 4883 | SetA=Gundran, Jimmy | SetB=Gundran, Jimmy
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Jimmy', d.last_name = 'Gundran'
  WHERE u.plate_number = 'NEI 4883' AND u.deleted_at IS NULL;

-- Row 65: Plate=NEN 2955 | SetA=Gonzales, Rommel | SetB=Gonzales, Rommel
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Rommel', d.last_name = 'Gonzales'
  WHERE u.plate_number = 'NEN 2955' AND u.deleted_at IS NULL;

-- Row 66: Plate=NEN 2957 | SetA=Calingasan, Apolinario | SetB=Calisangan, Apolinario
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Apolinario', d.last_name = 'Calingasan'
  WHERE u.plate_number = 'NEN 2957' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Apolinario', d.last_name = 'Calisangan'
  WHERE u.plate_number = 'NEN 2957' AND u.deleted_at IS NULL;

-- Row 67: Plate=NEO 67116 | SetA=Boroy, Morlino | SetB=Bonsol, Henner
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Morlino', d.last_name = 'Boroy'
  WHERE u.plate_number = 'NEO 67116' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Henner', d.last_name = 'Bonsol'
  WHERE u.plate_number = 'NEO 67116' AND u.deleted_at IS NULL;

-- Row 68: Plate=NEP 2440 | SetA=Calubag, Leonildo | SetB=Baguioro, Marlito
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Leonildo', d.last_name = 'Calubag'
  WHERE u.plate_number = 'NEP 2440' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Marlito', d.last_name = 'Baguioro'
  WHERE u.plate_number = 'NEP 2440' AND u.deleted_at IS NULL;

-- Row 69: Plate=NEP 9750 | SetA=Leyva, Peter | SetB=Candelaria, Sismundo
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Peter', d.last_name = 'Leyva'
  WHERE u.plate_number = 'NEP 9750' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Sismundo', d.last_name = 'Candelaria'
  WHERE u.plate_number = 'NEP 9750' AND u.deleted_at IS NULL;

-- Row 70: Plate=NET 6100 | SetA=Tandual, Jefrrey | SetB=Satar, Edwin
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Jefrrey', d.last_name = 'Tandual'
  WHERE u.plate_number = 'NET 6100' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Edwin', d.last_name = 'Satar'
  WHERE u.plate_number = 'NET 6100' AND u.deleted_at IS NULL;

-- Row 71: Plate=NEU 5546 | SetA=Romera, Ricky | SetB=Rio, Jose
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Ricky', d.last_name = 'Romera'
  WHERE u.plate_number = 'NEU 5546' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Jose', d.last_name = 'Rio'
  WHERE u.plate_number = 'NEU 5546' AND u.deleted_at IS NULL;

-- Row 72: Plate=NEV 5065 | SetA=Ramos, Alejandro | SetB=Motol, Joey
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Alejandro', d.last_name = 'Ramos'
  WHERE u.plate_number = 'NEV 5065' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Joey', d.last_name = 'Motol'
  WHERE u.plate_number = 'NEV 5065' AND u.deleted_at IS NULL;

-- Row 73: Plate=NEW 3821 | SetA=Granado, Hermilio | SetB=Quijado, Ronipo
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Hermilio', d.last_name = 'Granado'
  WHERE u.plate_number = 'NEW 3821' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Ronipo', d.last_name = 'Quijado'
  WHERE u.plate_number = 'NEW 3821' AND u.deleted_at IS NULL;

-- Row 74: Plate=NEW 6279 | SetA=Utap, Daud | SetB=Piandiong, Joseph
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Daud', d.last_name = 'Utap'
  WHERE u.plate_number = 'NEW 6279' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Joseph', d.last_name = 'Piandiong'
  WHERE u.plate_number = 'NEW 6279' AND u.deleted_at IS NULL;

-- Row 75: Plate=NFH 3664 | SetA=Ariola, Oliver | SetB=Nieva, Edward
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Oliver', d.last_name = 'Ariola'
  WHERE u.plate_number = 'NFH 3664' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Edward', d.last_name = 'Nieva'
  WHERE u.plate_number = 'NFH 3664' AND u.deleted_at IS NULL;

-- Row 76: Plate=NFZ 8295 | SetA=Cuballes, Rolly | SetB=Cuballes, Rolly
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Rolly', d.last_name = 'Cuballes'
  WHERE u.plate_number = 'NFZ 8295' AND u.deleted_at IS NULL;

-- Row 77: Plate=NGA 5044 | SetA=Salazar. Angel | SetB=Salazar, Angel
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Salazar. Angel', d.last_name = ''
  WHERE u.plate_number = 'NGA 5044' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Angel', d.last_name = 'Salazar'
  WHERE u.plate_number = 'NGA 5044' AND u.deleted_at IS NULL;

-- Row 78: Plate=NGA 7736 | SetA=Jorojoro, Domingo | SetB=Jorojoro, Domingo
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Domingo', d.last_name = 'Jorojoro'
  WHERE u.plate_number = 'NGA 7736' AND u.deleted_at IS NULL;

-- Row 79: Plate=NGB 2854 | SetA=Funtanilla, Monico | SetB=Funtanilla, Monico
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Monico', d.last_name = 'Funtanilla'
  WHERE u.plate_number = 'NGB 2854' AND u.deleted_at IS NULL;

-- Row 80: Plate=NGB 6033 | SetA=Monisit, William | SetB=Monisit, William
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'William', d.last_name = 'Monisit'
  WHERE u.plate_number = 'NGB 6033' AND u.deleted_at IS NULL;

-- Row 81: Plate=NGF 1484 | SetA=Borromeo, Jayson | SetB=Borromeo, Jayson
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Jayson', d.last_name = 'Borromeo'
  WHERE u.plate_number = 'NGF 1484' AND u.deleted_at IS NULL;

-- Row 82: Plate=NGO 2629 | SetA=Joquino, Edwin | SetB=Razo, Fernando
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Edwin', d.last_name = 'Joquino'
  WHERE u.plate_number = 'NGO 2629' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Fernando', d.last_name = 'Razo'
  WHERE u.plate_number = 'NGO 2629' AND u.deleted_at IS NULL;

-- Row 83: Plate=NGP 1877 | SetA=Cortez, Renato | SetB=Tequillo, Noel
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Renato', d.last_name = 'Cortez'
  WHERE u.plate_number = 'NGP 1877' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Noel', d.last_name = 'Tequillo'
  WHERE u.plate_number = 'NGP 1877' AND u.deleted_at IS NULL;

-- Row 86: Plate=ULO 884 | SetA=Adobas, Nelson | SetB=Cruz, Armando
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Nelson', d.last_name = 'Adobas'
  WHERE u.plate_number = 'ULO 884' AND u.deleted_at IS NULL;

UPDATE drivers d
  INNER JOIN units u ON u.secondary_driver_id = d.id
  SET d.first_name = 'Armando', d.last_name = 'Cruz'
  WHERE u.plate_number = 'ULO 884' AND u.deleted_at IS NULL;

-- Row 87: Plate=UWD 421 | SetA=Emberso, Napoleon | SetB=Emberso, Napoleon
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Napoleon', d.last_name = 'Emberso'
  WHERE u.plate_number = 'UWD 421' AND u.deleted_at IS NULL;

-- Row 88: Plate=UWD 431 | SetA=Hagad, Alfredo | SetB=Hagad, Alfredo
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Alfredo', d.last_name = 'Hagad'
  WHERE u.plate_number = 'UWD 431' AND u.deleted_at IS NULL;

-- Row 89: Plate=UWN 226 | SetA=Raagas, Francisco | SetB=Raagas, Francisco
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Francisco', d.last_name = 'Raagas'
  WHERE u.plate_number = 'UWN 226' AND u.deleted_at IS NULL;

-- Row 90: Plate=VAA 9864 | SetA=Lorenzo, Gary | SetB=Lorenzo, Gary
UPDATE drivers d
  INNER JOIN units u ON u.driver_id = d.id
  SET d.first_name = 'Gary', d.last_name = 'Lorenzo'
  WHERE u.plate_number = 'VAA 9864' AND u.deleted_at IS NULL;

-- Row 91: Plate=VFL 543 | SetA=NAD | SetB=NAD
-- Verification
SELECT d.id, d.first_name, d.last_name, u.plate_number, 'primary' as role
  FROM drivers d INNER JOIN units u ON u.driver_id = d.id
  WHERE d.first_name IS NOT NULL ORDER BY u.plate_number LIMIT 20;

SELECT COUNT(*) as total_with_names FROM drivers WHERE first_name IS NOT NULL AND first_name != '';