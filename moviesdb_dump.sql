CREATE DATABASE IF NOT EXISTS moviesdb DEFAULT CHARACTER SET utf8; -- baasi loomine
USE moviesdb; -- baasi kasutamine


CREATE TABLE actor ( -- tabeli loomine
  actor_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  firstname VARCHAR(50) NOT NULL,
  lastname VARCHAR(50) NOT NULL,
  PRIMARY KEY  (actor_id),
  KEY idx_actor_lastname (lastname) -- väljade määramine
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE language (
  language_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(20) NOT NULL,
  PRIMARY KEY (language_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE film (
  film_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(100) NOT NULL,
  description TEXT DEFAULT NULL,
  release_year YEAR DEFAULT NULL,
  language_id SMALLINT UNSIGNED NOT NULL,
  length SMALLINT UNSIGNED DEFAULT NULL,
  PRIMARY KEY  (film_id),
  KEY idx_title (title),
  CONSTRAINT fk_film_language FOREIGN KEY (language_id) REFERENCES language (language_id) ON DELETE RESTRICT ON UPDATE CASCADE -- foreign key märamine koos muutumis tingimustega
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE film_actor ( 
    actor_id SMALLINT UNSIGNED NOT NULL, 
    film_id SMALLINT UNSIGNED NOT NULL, 
    PRIMARY KEY (actor_id,film_id), 
    CONSTRAINT fk_film_actor_actor FOREIGN KEY (actor_id) REFERENCES actor (actor_id) ON DELETE RESTRICT ON UPDATE CASCADE, 
    CONSTRAINT fk_film_actor_film FOREIGN KEY (film_id) REFERENCES film (film_id) ON DELETE RESTRICT ON UPDATE CASCADE 
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE category (
  category_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(25) NOT NULL,
  PRIMARY KEY  (category_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE film_category (
  film_id SMALLINT UNSIGNED NOT NULL,
  category_id SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (film_id, category_id),
  CONSTRAINT fk_film_category_film FOREIGN KEY (film_id) REFERENCES film (film_id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_film_category_category FOREIGN KEY (category_id) REFERENCES category (category_id) ON DELETE RESTRICT ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;




INSERT INTO category VALUES (1,'Action'), -- väärtuste lisamine
(2,'Animation'),
(3,'Children'),
(4,'Classics'),
(5,'Comedy'),
(6,'Documentary'),
(7,'Drama'),
(8,'Family'),
(9,'Horror'),
(10,'Music');


INSERT INTO language VALUES 
(1,'English'),
(2,'Estonian'),
(3,'Russian');

INSERT INTO film VALUES 
(1, 'The Lord of the Rings: The Return of the King', 'The Lord of the Rings film trilogy is unusual in that it was, up until the release of Jackson''s prequel trilogy The Hobbit, the only series whose separate instalments were written and shot simultaneously (excluding pick up shoots). Jackson found The Return of the King the easiest of the films to make, because it contained the climax of the story, unlike the other two films. The Return of the King was originally the second of two planned films under Miramax from January 1997 to August 1998, and more or less in its finished structure as the first film was to end with The Two Towers'' Battle of Helm''s Deep. Filming took place under multiple units across New Zealand, between 11 October 1999 and 22 December 2000, with pick up shoots for six weeks in 2003 before the film''s release.', 2003, 1, 200),
(2, 'Armageddon', 'Armageddon is a 1998 American science fiction disaster thriller film directed by Michael Bay, produced by Jerry Bruckheimer, and released by Touchstone Pictures. The film follows a group of blue-collar deep-core drillers sent by NASA to stop a gigantic asteroid on a collision course with Earth. It features an ensemble cast including Bruce Willis, Ben Affleck, Billy Bob Thornton, Liv Tyler, Owen Wilson, Will Patton, Peter Stormare, William Fichtner, Michael Clarke Duncan, Keith David, and Steve Buscemi.', 1998, 1, 150),
(3, 'The Fifth Element', 'The Fifth Element (French: Le Cinquième Élément) is a 1997 English-language French science fiction action film directed and co-written by Luc Besson. It stars Bruce Willis, Gary Oldman and Milla Jovovich. Primarily set in the 23rd century, the film''s central plot involves the survival of planet Earth, which becomes the responsibility of Korben Dallas (Willis), a taxicab driver and former special forces major, after a young woman (Jovovich) falls into his cab. Dallas joins forces with her to recover four mystical stones essential for the defence of Earth against an impending attack.', 1997, 1, 126),
(4, 'Viimne reliikvia', '"Viimne reliikvia" on Eesti film aastast 1969. Filmi süžee põhineb Eduard Bornhöhe romaanil "Vürst Gabriel ehk Pirita kloostri viimsed päevad". Eesti filmi sajanda juubeli puhul 2012. aastal valiti "Viimne reliikvia" parimaks nii filmikildude kui ka filmilaulude poolest', 1969, 2, 86),
(5, 'Ледокол', '«Ледокол» — российский фильм-катастрофа режиссёра Николая Хомерики. Производство студии «ПРОФИТ». Премьера фильма в России состоялась 20 октября 2016 года. Сюжет фильма основан на реальных событиях, произошедших в 1985 году с ледоколом «Михаил Сомов», который оказался зажат антарктическими льдами и провёл в вынужденном дрейфе 133 дня.', 2016, 3, 129);

INSERT INTO actor VALUES 
(1, 'Liv', 'Tyler'),
(2,'Elijah', 'Wood'),
(3, 'Ian', 'McKellen'),
(4, 'Bruce ', 'Willis'),
(5, 'Aleksandr', 'Goloborodko'),
(6, 'Ingrīda ', 'Andriņa'),
(7, 'Пётр', 'Фёдоров'),
(8, 'Анна', 'Михалкова');


INSERT INTO film_actor VALUES 
(1, 1),
(2, 1),
(3, 1),
(1, 2),
(4, 2),
(4, 3),
(5, 4),
(6, 4),
(7, 5),
(8, 5);

INSERT INTO film_category  VALUES 
(5, 7),
(4, 1),
(1, 7),
(1, 1),
(2, 1),
(3, 1);


CREATE VIEW film_info -- vaae loomine
AS
SELECT film.film_id AS id, film.title AS title, film.description AS description, film.release_year AS year, film.length AS length, 
(
    SELECT GROUP_CONCAT(CONCAT_WS(',', category.category_id, category.name) SEPARATOR ';') -- väärtuste valik ühendamisega
    FROM category 
    	JOIN film_category ON category.category_id = film_category.category_id -- tabelite ühendamine
    WHERE film_category.film_id = film.film_id -- tingimus
) AS categories, 
(
    SELECT GROUP_CONCAT(CONCAT_WS(',', actor.actor_id, actor.firstname, actor.lastname)SEPARATOR ';') 
    FROM actor 
    	JOIN film_actor ON film_actor.actor_id = actor.actor_id 
    WHERE film.film_id=film_actor.film_id 
) AS actors, 
(
    SELECT GROUP_CONCAT(CONCAT_WS(',',language.language_id, language.name))
    FROM language 
    WHERE film.language_id=language.language_id 
) AS language 
FROM film 
-- GROUP BY film.film_id 

