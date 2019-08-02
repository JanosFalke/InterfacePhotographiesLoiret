set datestyle to 'european';

-- Creation des tables

-- Table Temporaire utiliser pour les differents traitement

DROP TABLE IF EXISTS tout;
CREATE TABLE IF NOT EXISTS tout
(
  reference_cindoc         TEXT,
  serie                    TEXT,
  article                  INTEGER,
  discriminant             TEXT,
  ville                    TEXT,
  sujet                    TEXT,
  description_detaillee    TEXT,
  date                     TEXT,
  notes_de_bas_de_page     TEXT,
  index_personnes          TEXT,
  fichier_numerique        TEXT,
  index_iconographique     TEXT,
  nombre_de_cliches        TEXT,
  taille_du_cliche         TEXT,
  negatif_ou_inversible    TEXT,
  couleur_ou_noir_et_blanc TEXT,
  remarques                TEXT
);

-- Importer les donnes via pgAdmin dans la table tout

DROP TABLE IF EXISTS DataTraitement;
CREATE TABLE IF NOT EXISTS DataTraitement AS TABLE tout;

DROP TABLE IF EXISTS AfterClean;
CREATE TABLE IF NOT EXISTS AfterClean
(
  reference_cindoc         TEXT,
  article                  INTEGER,
  discriminant             TEXT,
  ville                    TEXT,
  sujet                    TEXT,
  description_detaillee    TEXT,
  date                     TEXT,
  notes_de_bas_de_page     TEXT,
  index_personnes          TEXT,
  index_iconographique     TEXT,
  nombre_de_cliches        TEXT,
  taille_du_cliche         TEXT,
  negatif_ou_inversible    TEXT,
  couleur_ou_noir_et_blanc TEXT,
  remarques                TEXT
);

-- Table en de la base final

DROP TABLE IF EXISTS Articles, Villes, Tailles_cliches, Cliches, Articles_details;


CREATE TABLE Articles
(
  article   INTEGER,
  remarques TEXT,
  PRIMARY KEY (article)
);

CREATE TABLE Villes
(
  id_ville    SERIAL,
  nom         VARCHAR(255),
  code_postal VARCHAR(5),
  longitude   INTEGER,
  latitude    INTEGER,
  PRIMARY KEY (id_ville)
);

CREATE TABLE Tailles_cliches
(
  id_taille_cliche SERIAL,
  longueur         NUMERIC,
  largeur          NUMERIC,
  PRIMARY KEY (id_taille_cliche)
);

CREATE TABLE Cliches
(
  id_cliche                SERIAL,
  nombre_cliche            INTEGER,
  negatif_ou_inversible    VARCHAR(255),
  couleur_ou_noir_et_blanc VARCHAR(255),
  id_taille_cliche         SMALLINT,
  PRIMARY KEY (id_cliche),
  FOREIGN KEY (id_taille_cliche) references Tailles_cliches (id_taille_cliche)
);

CREATE TABLE Articles_details
(
  reference_cindoc      INTEGER,
  article               INTEGER,
  discriminant          INTEGER,
  id_ville              INTEGER,
  sujet                 TEXT,
  description_detaillee TEXT,
  date                  DATE,
  notes_de_bas_de_page  TEXT,
  index_personnes       TEXT,
  index_iconographique  TEXT,
  id_cliche             INTEGER,
  PRIMARY KEY (article, discriminant),
  FOREIGN KEY (article) references Articles (article),
  FOREIGN KEY (id_ville) references Villes (id_ville),
  FOREIGN KEY (id_cliche) references Cliches (id_cliche)
);



-- Requete de Nettoyage des Colonnes

CREATE OR REPLACE FUNCTION clean_data()
  RETURNS VOID AS
$$
BEGIN
  UPDATE DataTraitement
  SET date = replace(date, 'Prise de vue :', '');
  UPDATE DataTraitement
  SET date = replace(date, 'janvier', '01');
  UPDATE DataTraitement
  SET date = replace(date, 'février', '02');
  UPDATE DataTraitement
  SET date = replace(date, 'mars', '03');
  UPDATE DataTraitement
  SET date = replace(date, 'avril', '04');
  UPDATE DataTraitement
  SET date = replace(date, 'mai', '05');
  UPDATE DataTraitement
  SET date = replace(date, 'juin', '06');
  UPDATE DataTraitement
  SET date = regexp_replace(date, 'juillet|jullet', '07');
  UPDATE DataTraitement
  SET date = replace(date, 'août', '08');
  UPDATE DataTraitement
  SET date = replace(date, 'septembre', '09');
  UPDATE DataTraitement
  SET date = replace(date, 'octobre', '10');
  UPDATE DataTraitement
  SET date = replace(date, 'novembre', '11');
  UPDATE DataTraitement
  SET date = replace(date, 'décembre', '12');
  UPDATE DataTraitement
  SET date = regexp_replace(date, '[:Alnum:]*-', ' ');
  UPDATE DataTraitement
  SET date = regexp_replace(date, '-([:Alnum:]*)-', ' ', 'g');

  UPDATE datatraitement
  SET ville                    = regexp_replace(ville, '\s+', ' ', 'g'),
      sujet                    = regexp_replace(sujet, '\s+', ' ', 'g'),
      description_detaillee    = regexp_replace(description_detaillee, '\s+', ' ', 'g'),
      date                     = regexp_replace(date, '\s+', ' ', 'g'),
      notes_de_bas_de_page     = regexp_replace(notes_de_bas_de_page, '\s+', ' ', 'g'),
      index_personnes          = regexp_replace(index_personnes, '\s+', ' ', 'g'),
      index_iconographique     = regexp_replace(index_iconographique, '\s+', ' ', 'g'),
      taille_du_cliche         = regexp_replace(taille_du_cliche, '\s+', ' ', 'g'),
      negatif_ou_inversible    = regexp_replace(negatif_ou_inversible, '\s+', ' ', 'g'),
      couleur_ou_noir_et_blanc = regexp_replace(couleur_ou_noir_et_blanc, '\s+', ' ', 'g'),
      remarques                = regexp_replace(remarques, '\s+', ' ', 'g');

  UPDATE datatraitement
  SET ville            = regexp_replace(ville, '\(.*\)', '', 'g'),
      taille_du_cliche = regexp_replace(taille_du_cliche, '\(.*\)', '', 'g');

  UPDATE datatraitement
  SET ville                    = regexp_replace(ville, ',+', ',', 'g'),
      sujet                    = regexp_replace(sujet, ',+', ',', 'g'),
      description_detaillee    = regexp_replace(description_detaillee, ',+', ',', 'g'),
      date                     = regexp_replace(date, ',+', ',', 'g'),
      notes_de_bas_de_page     = regexp_replace(notes_de_bas_de_page, ',+', ',', 'g'),
      index_personnes          = regexp_replace(index_personnes, ',+', ',', 'g'),
      index_iconographique     = regexp_replace(index_iconographique, ',+', ',', 'g'),
      taille_du_cliche         = regexp_replace(taille_du_cliche, ',+', ',', 'g'),
      negatif_ou_inversible    = regexp_replace(negatif_ou_inversible, ',+', ',', 'g'),
      couleur_ou_noir_et_blanc = regexp_replace(couleur_ou_noir_et_blanc, ',+', ',', 'g'),
      remarques                = regexp_replace(remarques, ',+', ',', 'g');

  UPDATE datatraitement
  SET ville                    = regexp_replace(ville, '^,', '', 'g'),
      sujet                    = regexp_replace(sujet, '^,', '', 'g'),
      description_detaillee    = regexp_replace(description_detaillee, '^,', '', 'g'),
      date                     = regexp_replace(date, '^,', '', 'g'),
      notes_de_bas_de_page     = regexp_replace(notes_de_bas_de_page, '^,', '', 'g'),
      index_personnes          = regexp_replace(index_personnes, '^,', '', 'g'),
      index_iconographique     = regexp_replace(index_iconographique, '^,', '', 'g'),
      taille_du_cliche         = regexp_replace(taille_du_cliche, '^,', '', 'g'),
      negatif_ou_inversible    = regexp_replace(negatif_ou_inversible, '^,', '', 'g'),
      couleur_ou_noir_et_blanc = regexp_replace(couleur_ou_noir_et_blanc, '^,', '', 'g'),
      remarques                = regexp_replace(remarques, '^,', '', 'g');

  UPDATE datatraitement
  SET ville                    = regexp_replace(ville, '^\s+', '', 'g'),
      sujet                    = regexp_replace(sujet, '^\s+', '', 'g'),
      description_detaillee    = regexp_replace(description_detaillee, '^\s+', '', 'g'),
      date                     = regexp_replace(date, '^\s+', '', 'g'),
      notes_de_bas_de_page     = regexp_replace(notes_de_bas_de_page, '^\s+', '', 'g'),
      index_personnes          = regexp_replace(index_personnes, '^\s+', '', 'g'),
      index_iconographique     = regexp_replace(index_iconographique, '^\s+', '', 'g'),
      taille_du_cliche         = regexp_replace(taille_du_cliche, '^\s+', '', 'g'),
      negatif_ou_inversible    = regexp_replace(negatif_ou_inversible, '^\s+', '', 'g'),
      couleur_ou_noir_et_blanc = regexp_replace(couleur_ou_noir_et_blanc, '^\s+', '', 'g'),
      remarques                = regexp_replace(remarques, '^\s+', '', 'g');

  UPDATE datatraitement
  SET ville                    = regexp_replace(ville, '( |), ', ',', 'g'),
      sujet                    = regexp_replace(sujet, '( |), ', ',', 'g'),
      description_detaillee    = regexp_replace(description_detaillee, '( |), ', ',', 'g'),
      date                     = regexp_replace(date, '( |), ', ', ', 'g'),
      notes_de_bas_de_page     = regexp_replace(notes_de_bas_de_page, '( |), ', ',', 'g'),
      index_personnes          = regexp_replace(index_personnes, '( |), ', ',', 'g'),
      index_iconographique     = regexp_replace(index_iconographique, '( |), ', ',', 'g'),
      taille_du_cliche         = regexp_replace(taille_du_cliche, '( |), ', '|', 'g'),
      negatif_ou_inversible    = regexp_replace(negatif_ou_inversible, '( |), ', ',', 'g'),
      couleur_ou_noir_et_blanc = regexp_replace(couleur_ou_noir_et_blanc, '( |), ', ',', 'g'),
      remarques                = regexp_replace(remarques, ' , ', ', ', 'g');

  UPDATE datatraitement
  SET reference_cindoc = regexp_replace(reference_cindoc, '( |)\|( |)', ',', 'g');

  UPDATE datatraitement
  SET reference_cindoc = regexp_replace(reference_cindoc, ' , ', ',', 'g');

  UPDATE datatraitement
  SET negatif_ou_inversible = regexp_replace(negatif_ou_inversible, '(diapositive 135|verre)', '', 'g');

  UPDATE datatraitement
  SET negatif_ou_inversible = regexp_replace(negatif_ou_inversible, '\s+', '', 'g');
  
  UPDATE datatraitement
  SET index_iconographique = regexp_replace(index_iconographique, '( |)([,|/])( |)', ',', 'g'),
      date                 = regexp_replace(date, '( |)([,|/])( |)', ',', 'g');

  UPDATE datatraitement
  SET taille_du_cliche = regexp_replace(taille_du_cliche, ',', '.', 'g');

END;
$$
  LANGUAGE plpgsql;

SELECT clean_data();

CREATE OR REPLACE FUNCTION split()
  RETURNS VOID AS
$$
DECLARE
  donnee        datatraitement;
  reference_cin VARCHAR;
  localite      VARCHAR;
  sujet_split   VARCHAR;
  index_icono   VARCHAR;
  date_prise    VARCHAR;
  nb_cliche     VARCHAR ARRAY;
  taille_cliche VARCHAR ARRAY;
  neg_inv       VARCHAR;
  color_bw      VARCHAR ARRAY;
  n             INTEGER;
  i             INTEGER;
  count         INTEGER;
BEGIN
  TRUNCATE afterclean;
  count = 0;
  FOR donnee IN (SELECT * FROM datatraitement)
    LOOP
      FOR reference_cin IN SELECT regexp_split_to_table(donnee.reference_cindoc, ',')
        LOOP
          FOR localite IN SELECT regexp_split_to_table(donnee.ville, ',')
            LOOP
              FOR sujet_split IN SELECT regexp_split_to_table(donnee.sujet, ',')
                LOOP
                  FOR index_icono IN SELECT regexp_split_to_table(donnee.index_iconographique, ',')
                    LOOP
                      FOR date_prise IN SELECT regexp_split_to_table(donnee.date, ',')
                        LOOP
                          nb_cliche = regexp_split_to_array(donnee.nombre_de_cliches, ',');
                          taille_cliche = regexp_split_to_array(donnee.taille_du_cliche, '\|');
                          color_bw = regexp_split_to_array(donnee.couleur_ou_noir_et_blanc, ',');

                          n = array_length(nb_cliche, 1);
                          IF n IS NULL THEN
                            n = -1;
                          END IF;
                          FOR i IN 1..n
                            LOOP
                              FOR neg_inv IN SELECT regexp_split_to_table(donnee.negatif_ou_inversible, ',')
                                LOOP
                                  -- RAISE NOTICE '%, %, %, %, %, %, %, %, %, %, %, %, %, %, %, %',reference_cin,donnee.serie,donnee.article,donnee.discriminant,localite,donnee.sujet,donnee.description_detaillee,donnee.date,donnee.notes_de_bas_de_page,donnee.index_personnes,donnee.fichier_numerique,donnee.index_iconographique,nb_cliche [ i],taille_cliche [ i],neg_inv,color_bw [ i];
                                  INSERT INTO afterclean
                                  VALUES (reference_cin, donnee.article, donnee.discriminant, LOWER(localite),
                                          LOWER(sujet_split),
                                          LOWER(donnee.description_detaillee), date_prise, LOWER(donnee.notes_de_bas_de_page),
                                          LOWER(donnee.index_personnes), LOWER(index_icono),
                                          nb_cliche [ i],
                                          taille_cliche [ i], LOWER(neg_inv), LOWER(color_bw [ i]), LOWER(donnee.remarques));
                                  count = count + 1;
                                END LOOP;
                            END LOOP;
                        END LOOP;
                    END LOOP;
                END LOOP;
            END LOOP;
        END LOOP;
    END LOOP;

  UPDATE afterclean
  SET ville = regexp_replace(ville, '^\s+', '');
  UPDATE afterclean
  SET ville = LOWER(ville);
  UPDATE afterclean
  SET ville = regexp_replace(ville, '--.*', '');
  UPDATE afterclean
  SET ville = regexp_replace(ville, ' $', '');
  UPDATE afterclean
  SET ville = regexp_replace(ville, '( |)- ', '-');
  UPDATE afterclean
  SET discriminant = NULL
  WHERE TRUE = TRUE;

  -- On met tout les champs vide a null

  UPDATE afterclean
  SET reference_cindoc = NULL
  WHERE reference_cindoc = '';
  UPDATE afterclean
  SET ville = NULL
  WHERE ville = '';
  UPDATE afterclean
  SET sujet = NULL
  WHERE sujet = '';
  UPDATE afterclean
  SET description_detaillee = NULL
  WHERE description_detaillee = '';
  UPDATE afterclean
  SET date = NULL
  WHERE date = '';
  UPDATE afterclean
  SET notes_de_bas_de_page = NULL
  WHERE notes_de_bas_de_page = '';
  UPDATE afterclean
  SET index_personnes = NULL
  WHERE index_personnes = '';
  UPDATE afterclean
  SET index_iconographique = NULL
  WHERE index_iconographique = '';
  UPDATE afterclean
  SET nombre_de_cliches = NULL
  WHERE nombre_de_cliches = '';
  UPDATE afterclean
  SET taille_du_cliche = NULL
  WHERE taille_du_cliche = '';
  UPDATE afterclean
  SET negatif_ou_inversible = NULL
  WHERE negatif_ou_inversible = '';
  UPDATE afterclean
  SET couleur_ou_noir_et_blanc= NULL
  WHERE couleur_ou_noir_et_blanc = '';
  UPDATE afterclean
  SET remarques = NULL
  WHERE remarques = '';

  RAISE NOTICE 'Nb Ligne : % ',count;
END;
$$
  LANGUAGE plpgsql;
																										  																				 																										  
																									
SELECT split(); -- Prend un certain temps

DROP TABLE IF EXISTS groupdata;
SELECT reference_cindoc,
       article,
       discriminant,
       ville,
       sujet,
       description_detaillee,
       date,
       notes_de_bas_de_page,
       index_personnes,
       index_iconographique,
       SUM(CAST(nombre_de_cliches as INTEGER)) as nb_cliche,
       taille_du_cliche,
       negatif_ou_inversible,
       couleur_ou_noir_et_blanc,
       remarques
       INTO groupdata
FROM AfterClean
GROUP BY reference_cindoc, article, discriminant,ville, sujet, description_detaillee, date,
         notes_de_bas_de_page, index_personnes, index_iconographique, taille_du_cliche,
         negatif_ou_inversible, couleur_ou_noir_et_blanc, remarques;

DROP TABLE AfterClean;
DROP TABLE DataTraitement;

CREATE OR REPLACE FUNCTION trigger_discriminant()
  RETURNS TRIGGER AS
$$
DECLARE
  last_disc INTEGER;
BEGIN
  last_disc = (SELECT discriminant
               FROM articles_details
               WHERE articles_details.
                       article = new.article
               ORDER BY discriminant DESC
               LIMIT 1);

  IF last_disc IS NULL THEN
    new.discriminant = 1 ;
  ELSE
    new.discriminant = last_disc + 1;
  END IF;
  RETURN new;
END;
$$
  LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trigger_discriminant_t ON articles_details;
CREATE TRIGGER trigger_discriminant_t
  BEFORE INSERT
  ON
    articles_details
  FOR EACH ROW
EXECUTE PROCEDURE trigger_discriminant();

CREATE OR REPLACE FUNCTION from1To3Fn()
  RETURNS VOID AS
$$
DECLARE
  -- Cliche
  taille_cliche     VARCHAR[];
  -- Article
  article_data      RECORD;
  -- index si besion de cree des id
  index_count       INTEGER = 1;
  -- Données principale
  main_data         groupdata;
  id_taille_cliche_ INTEGER;
  date_             VARCHAR[];
  id_ville_         INTEGER;
  -- Info
  nb_insertion      INTEGER = 0;
BEGIN

  TRUNCATE Articles_details,Articles,Cliches,Tailles_cliches;

  index_count = 1;

  FOR taille_cliche IN (SELECT DISTINCT regexp_split_to_array(taille_du_cliche, 'x')
                        FROM groupdata
                        WHERE taille_du_cliche IS NOT NULL)
    LOOP
      IF (SELECT COUNT(*)
          FROM tailles_cliches T
          WHERE T.longueur = CAST(taille_cliche [ 1] as NUMERIC)
            AND T.largeur = CAST(taille_cliche [ 2] as NUMERIC)) = 0
      THEN
        INSERT INTO tailles_cliches (longueur, largeur)
        VALUES (CAST(taille_cliche [ 1] as NUMERIC), CAST(taille_cliche [ 2] as NUMERIC));
        index_count = index_count + 1;
        nb_insertion = nb_insertion + 1;
      END IF;
    END LOOP;

  FOR article_data IN (SELECT DISTINCT article,remarques FROM groupdata ORDER BY article)
    LOOP
      INSERT INTO articles
      VALUES (article_data.article, article_data.remarques);
      nb_insertion = nb_insertion + 1;
    END LOOP;

  index_count = 1;

  FOR main_data IN SELECT * FROM groupdata
    LOOP
      -- Recuperation le L'id de la taille
      taille_cliche = regexp_split_to_array(main_data.taille_du_cliche, 'x');
      id_taille_cliche_ = (SELECT id_taille_cliche
                           FROM tailles_cliches
                           WHERE longueur = CAST(taille_cliche [ 1] AS NUMERIC)
                             AND largeur = CAST(taille_cliche [ 2] AS NUMERIC));
      -- Voir si les donnees exist deja
      id_ville_ = (SELECT id_ville FROM villes V WHERE V.nom LIKE main_data.ville LIMIT 1);

      INSERT INTO cliches (nombre_cliche, negatif_ou_inversible, couleur_ou_noir_et_blanc, id_taille_cliche)
      VALUES (main_data.nb_cliche, main_data.negatif_ou_inversible, main_data.couleur_ou_noir_et_blanc,
              id_taille_cliche_);

      date_ = regexp_split_to_array(main_data.date, ' ');

      IF array_length(date_, 1) = 1 THEN
        INSERT INTO articles_details
        VALUES (CAST(main_data.reference_cindoc as INTEGER), main_data.article, NULL, id_ville_,
                main_data.sujet, main_data.description_detaillee, to_date(main_data.date, 'YYYY'),
                main_data.notes_de_bas_de_page, main_data.index_personnes,
                main_data.index_iconographique, index_count);
      ELSEIF array_length(date_, 1) = 2 THEN
        INSERT INTO articles_details
        VALUES (CAST(main_data.reference_cindoc as INTEGER), main_data.article, NULL, id_ville_,
                main_data.sujet, main_data.description_detaillee, to_date(main_data.date, 'MM YYYY'),
                main_data.notes_de_bas_de_page, main_data.index_personnes,
                main_data.index_iconographique, index_count);
      ELSEIF array_length(date_, 1) = 3 THEN
        INSERT INTO articles_details
        VALUES (CAST(main_data.reference_cindoc as INTEGER), main_data.article, NULL, id_ville_,
                main_data.sujet, main_data.description_detaillee, to_date(main_data.date, 'DD MM YYYY'),
                main_data.notes_de_bas_de_page, main_data.index_personnes,
                main_data.index_iconographique, index_count);
      END IF;
      index_count = index_count + 1;
      nb_insertion = nb_insertion + 1;
    END LOOP;
  RAISE NOTICE 'insert : % ',nb_insertion;
END;
$$
  LANGUAGE plpgsql;

SELECT from1To3Fn();

DROP TABLE IF EXISTS groupdata;
DROP TABLE IF EXISTS tout;