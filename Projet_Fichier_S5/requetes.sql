-- Nombre de cliché par ville
SELECT V.nom as ville,SUM(C.nombre_cliche) as nombre_de_cliche
FROM articles_details Ad
       JOIN cliches C on Ad.id_cliche = C.id_cliche
       JOIN villes V on Ad.id_ville = V.id_ville
GROUP BY V.nom;

-- Nombre de cliche pris avant 2000
SELECT SUM(C.nombre_cliche) as nombre_de_cliche
FROM articles_details Ad
       JOIN cliches C on Ad.id_cliche = C.id_cliche
WHERE Ad.date < to_date('01 01 2000', 'DD MM YYYY');

-- Nombre de cliche par format
SELECT Tc.longueur, Tc.largeur, sum(C.nombre_cliche)
FROM cliches C
       JOIN tailles_cliches Tc on C.id_taille_cliche = Tc.id_taille_cliche
GROUP BY (Tc.longueur, Tc.largeur);


--Nombre de cliché pris en moyenne
SELECT AVG(C.nombre_cliche)
FROM cliches C;

-- Nombre de cliché moyen par article dans une ville
SELECT V.nom as ville,AVG(C.nombre_cliche) as nombre_de_cliche
FROM articles_details Ad
       JOIN cliches C on Ad.id_cliche = C.id_cliche
       JOIN villes V on Ad.id_ville = V.id_ville
GROUP BY V.nom;

-- Nombre de discriminant par article
SELECT Ad.article,COUNT(discriminant)
FROM articles_details Ad
GROUP BY Ad.article;

-- Trigger Date
CREATE OR REPLACE FUNCTION detect_date()
  RETURNS TRIGGER AS
$$
DECLARE
  maintenant TIMESTAMP = now();
BEGIN
  IF new.date > maintenant THEN
    RAISE WARNING 'DATE invalid : % est une date future',new.date;
    RETURN NULL;
  END IF;
  RETURN new;

END;
$$
  LANGUAGE plpgsql;

CREATE TRIGGER verif_date
  BEFORE INSERT OR UPDATE
  ON articles_details
  FOR EACH ROW
EXECUTE PROCEDURE detect_date();




--Trigger suppression ville

CREATE OR REPLACE FUNCTION change_ville_id_function()
  RETURNS TRIGGER AS
$$
DECLARE
  rec RECORD;
BEGIN
	FOR rec IN SELECT id_ville
        FROM articles_details 
    LOOP 
	  IF rec.id_ville = OLD.id_ville THEN
      	UPDATE articles_details SET id_ville = null WHERE id_ville = OLD.id_ville; 
	  END IF;
    END LOOP;
	RETURN old;
END;
$$
  LANGUAGE plpgsql;

CREATE TRIGGER change_ville_ids
  BEFORE DELETE
  ON villes
  FOR EACH ROW
EXECUTE PROCEDURE change_ville_id_function();


--Trigger suppression taille cliche

CREATE OR REPLACE FUNCTION change_taille_cliche_id_func()
  RETURNS TRIGGER AS
$$
DECLARE
  rec RECORD;
BEGIN
	FOR rec IN SELECT id_taille_cliche
        FROM cliches 
    LOOP 
	  IF rec.id_taille_cliche = OLD.id_taille_cliche THEN
      	UPDATE cliches SET id_taille_cliche = null WHERE id_taille_cliche = OLD.id_taille_cliche; 
	  END IF;
    END LOOP;
	RETURN old;
END;
$$
  LANGUAGE plpgsql;


CREATE TRIGGER change_tailles_cliches_id
  BEFORE DELETE
  ON tailles_cliches
  FOR EACH ROW
EXECUTE PROCEDURE change_taille_cliche_id_func();



--Creation indexes
CREATE INDEX index_ville_articles ON articles_details (id_ville);
CREATE INDEX index_cliches_id_taille ON cliches (id_taille_cliche);

CREATE INDEX index_reference ON articles_details (reference_cindoc);
CREATE INDEX index_code_postal ON villes (code_postal);
CREATE INDEX index_nom_ville ON villes (nom);
CREATE INDEX index_date ON articles_details (date);
CREATE INDEX index_sujet ON articles_details (sujet);
CREATE INDEX index_icono ON articles_details (index_iconographique);
CREATE INDEX index_personnes ON articles_details (index_personnes);
CREATE INDEX index_largeur ON tailles_cliches (largeur);
CREATE INDEX index_longueur ON tailles_cliches (longueur);
