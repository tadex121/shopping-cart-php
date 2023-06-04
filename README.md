NAKUPOVALNI SEZNAM
Kratek opis projekta in njegove funkcionalnosti.

Predpogoji
PHP
MySQL
Spletni strežnik (npr. Apache, Nginx)
Namestitev
Klonirajte ta projekt v želeno mapo na vašem lokalnem razvojnem strežniku.

Poiščite datoteko common.php v mapi config/common.php. Na vrstici 30 (define('PROJECT_NAME', '')) vpišite mapo, v kateri se nahaja projekt.
Na vrsticah 86, 87, 88 vpišite svoje podatke o bazi.

Ustvarite bazo podatkov:
Uporabite svoje orodje za upravljanje z bazami podatkov (npr. phpMyAdmin) ali izvedite ukaze SQL za ustvarjanje baze podatkov.

V bazi ustvarite dve tabeli: "users" in "lists".

CREATE TABLE users (
  ID INT(11) AUTO_INCREMENT,
  UUID VARCHAR(100),
  Password VARCHAR(255),
  Firstname VARCHAR(255),
  Lastname VARCHAR(255),
  Email VARCHAR(255),
  Role VARCHAR(55),
  Status ENUM('active', 'deleted'),
  PRIMARY KEY (ID)
);

CREATE TABLE lists (
  ID INT(11) AUTO_INCREMENT,
  ListText TEXT,
  Completed TINYINT(4),
  Status ENUM('active', 'deleted'),
  PRIMARY KEY (ID)
);

Zaženite vaš lokalni razvojni strežnik (npr. XAMPP ali Nginx).

Odprite spletni brskalnik in vnesite naslednji naslov: http://localhost:8000.

Opomba: Poskrbite, da ste zaženili vaš lokalni razvojni strežnik in da je konfiguracija strežnika pravilno nastavljena.

Prosimo, upoštevajte, da se naslov http://localhost:8000 nanaša na lokalno razvojno okolje. Prepričajte se, da prilagodite naslov, če uporabljate drugačen port ali konfiguracijo strežnika.