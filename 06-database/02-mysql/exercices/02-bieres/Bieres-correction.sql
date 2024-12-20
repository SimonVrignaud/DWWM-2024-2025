-- Récupérer la BDD dans les ressources.
--  1. Quels sont les tickets qui comportent l’article d’ID 500, afficher le numéro de  ticket uniquement ? (24 résultats attendus)
SELECT NUMERO_TICKET FROM ventes WHERE ID_ARTICLE = 500;

--  2. Afficher les tickets du 15/01/2014. (1 résultat attendu)
SELECT * FROM ticket WHERE DATE_VENTE = "2014-01-15";

--  3. Afficher les tickets émis du 15/01/2014 au 17/01/2014.(4 résultats attendus)
SELECT * FROM ticket WHERE DATE_VENTE >= "2014-01-15" AND DATE_VENTE <= "2014-01-17";
-- ou
SELECT * FROM ticket WHERE DATE_VENTE BETWEEN "2014-01-15" AND "2014-01-17";

--  4. Afficher la liste des articles apparaissant à 50 et plus exemplaires sur un ticket.(1274 résultats attendus)
SELECT * FROM ventes WHERE QUANTITE >= 50;

--  5. Quelles sont les tickets émis au mois de mars 2014.(78 résultats attendus)
SELECT NUMERO_TICKET FROM ticket WHERE DATE_VENTE BETWEEN "2014-03-01" AND "2014-03-31";
-- ou
SELECT NUMERO_TICKET FROM ticket WHERE DATE_VENTE LIKE "2014-03-%";

--  6. Quelles sont les tickets émis entre les mois de mars et avril 2014 ? (166 résultats attendus)
SELECT NUMERO_TICKET FROM ticket WHERE DATE_VENTE BETWEEN "2014-03-01" AND "2014-04-30";
-- ou
SELECT NUMERO_TICKET FROM ticket WHERE DATE_VENTE LIKE "2014-03-%" OR DATE_VENTE LIKE "2014-04-%";

--  7. Quelles sont les tickets émis au mois de mars et juin 2014 ? (174 résultats attendus)
SELECT NUMERO_TICKET FROM ticket WHERE (MONTH(DATE_VENTE) = "03" OR MONTH(DATE_VENTE) = "06") AND YEAR(DATE_VENTE) = "2014";

--  8. Afficher l’id et le nom des bières classée par couleur. (3922 résultats attendus, vous pouvez afficher la couleur pour vérifier votre résultat)
SELECT a.ID_ARTICLE, a.NOM_ARTICLE, c.NOM_COULEUR FROM article a JOIN couleur c ON a.ID_Couleur = c.ID_Couleur ORDER BY c.ID_Couleur;
-- ou
SELECT ID_ARTICLE, NOM_ARTICLE, ID_Couleur FROM article ORDER BY ID_Couleur;

--  9. Afficher l’id et le nom des bières n’ayant pas de couleur. (706 résultats attendus)
SELECT a.ID_ARTICLE, a.NOM_ARTICLE FROM article a LEFT JOIN couleur c ON c.ID_Couleur = a.ID_Couleur WHERE c.ID_Couleur IS NULL;

--  10. Lister pour chaque ticket la quantité totale d’articles vendus classée par quantité décroissante. (4502 ou 8263 résultats attendus)
SELECT SUM(v.QUANTITE), NUMERO_TICKET FROM ventes v JOIN ticket USING(NUMERO_TICKET, ANNEE) GROUP BY NUMERO_TICKET, ANNEE ORDER BY v.QUANTITE DESC;
-- ou
SELECT v.NUMERO_TICKET, SUM(v.QUANTITE) AS total_quantite FROM ventes v GROUP BY v.NUMERO_TICKET ORDER BY total_quantite DESC;

--  11. Lister chaque ticket pour lequel la quantité totale d’articles vendus est supérieure
--  à 500 classée par quantité décroissante.(1026 résultats attendus)
SELECT v.NUMERO_TICKET, SUM(v.QUANTITE) AS total_quantite FROM ventes v GROUP BY v.NUMERO_TICKET HAVING total_quantite > 500 ORDER BY total_quantite DESC;

--  12. Lister chaque ticket pour lequel la quantité totale d’articles vendus est supérieure
--  à 500 classée par quantité décroissante.On exclura du total,
--  les ventes ayant une quantité supérieure à 50  (1021 résultats attendus)
SELECT v.NUMERO_TICKET, SUM(CASE WHEN v.QUANTITE <= 50 THEN v.QUANTITE ELSE 0 END) AS total_quantite FROM ventes v GROUP BY v.NUMERO_TICKET HAVING total_quantite > 500 ORDER BY total_quantite DESC;
-- ou
SELECT v.NUMERO_TICKET, SUM(v.QUANTITE) AS total_quantite FROM ventes v WHERE QUANTITE <= 50 GROUP BY v.NUMERO_TICKET HAVING total_quantite > 500  ORDER BY total_quantite DESC;

--  13. Lister l'id, le nom de la bière, le volume et le titrage des bières de type ‘Trappiste’. (48 résultats attendus.)
SELECT a.ID_ARTICLE, a.NOM_ARTICLE, a.VOLUME, a.TITRAGE FROM article a JOIN type t ON a.ID_TYPE = t.ID_TYPE WHERE t.NOM_TYPE = "Trappiste";

--  14. Lister les marques de bières du continent ‘Afrique’ (3 résultats attendus)
SELECT m.NOM_MARQUE, m.ID_MARQUE FROM marque m JOIN pays p ON m.ID_PAYS = p.ID_PAYS JOIN continent c ON p.ID_CONTINENT = c.ID_CONTINENT WHERE c.NOM_CONTINENT = "Afrique";
-- OU
SELECT DISTINCT NOM_MARQUE FROM marque m LEFT JOIN pays p ON p.ID_PAYS = m.ID_PAYS LEFT JOIN continent c ON c.ID_CONTINENT = p.ID_CONTINENT WHERE c.NOM_CONTINENT = "Afrique";

--  15. Lister les bières du continent ‘Afrique’ (6 résultats attendus)
SELECT a.ID_ARTICLE, a.NOM_ARTICLE, m.NOM_MARQUE FROM article a JOIN marque m ON a.ID_MARQUE = m.ID_MARQUE JOIN pays p ON m.ID_PAYS = p.ID_PAYS JOIN continent c ON p.ID_CONTINENT = c.ID_CONTINENT WHERE c.NOM_CONTINENT = "Afrique";

--  16. Lister les tickets (année, numéro de ticket, montant total payé). En sachant que le
--  prix de vente est égal au prix d’achat augmenté de 15% et que l’on n’est pas
--  assujetti à la TVA. (8263 résultats attendus avec pour les tickets 1, 2 et 3 des totaux égaux à "601.40", "500.05" et "513.33")
SELECT t.ANNEE, t.NUMERO_TICKET, SUM(a.PRIX_ACHAT * 1.15 * v.QUANTITE) AS total_paye FROM ticket t JOIN ventes v ON t.NUMERO_TICKET = v.NUMERO_TICKET AND t.ANNEE = v.ANNEE JOIN article a ON v.ID_ARTICLE = a.ID_ARTICLE GROUP BY t.ANNEE, t.NUMERO_TICKET;
-- OU
select v.ANNEE, v.NUMERO_TICKET, round(sum((v.QUANTITE * a.PRIX_ACHAT) * 1.15), 2) as total
    from ventes v
    left join article a on a.ID_ARTICLE = v.ID_ARTICLE
    group by v.ANNEE, v.NUMERO_TICKET;

--  17. Donner le C.A. par année. (3 résultats attendus : 
-- 2014: "585092.90", 2015: "1513659.30", 2016: "2508155.68")
SELECT YEAR(t.DATE_VENTE) as annee, SUM(a.PRIX_ACHAT * 1.15 * v.QUANTITE) AS CA FROM ticket t JOIN ventes v ON t.NUMERO_TICKET = v.NUMERO_TICKET AND t.ANNEE = v.ANNEE JOIN article a ON v.ID_ARTICLE = a.ID_ARTICLE GROUP BY ANNEE;

--  18. Lister les quantités vendues de chaque article pour l’année 2016. (1960 résultats attendues (ou 3922))
SELECT v.ID_ARTICLE, SUM(v.QUANTITE) as total_quantite FROM ventes v JOIN ticket t ON v.NUMERO_TICKET = t.NUMERO_TICKET WHERE YEAR(t.DATE_VENTE) = 2016 GROUP BY v.ID_ARTICLE;

--  19. Lister les quantités vendues de chaque article pour les années 2014,2015 ,2016. (5838 résultats attendus (ou 11197))
SELECT v.ID_ARTICLE, SUM(v.QUANTITE) AS total_quantite FROM ventes v JOIN ticket t ON v.NUMERO_TICKET = t.NUMERO_TICKET WHERE YEAR(t.DATE_VENTE) in (2014, 2015, 2016) GROUP BY v.ID_ARTICLE;

--  20. Lister les articles qui n’ont fait l’objet d’aucune vente en 2014. (498 résultats attendus)
SELECT a.NOM_ARTICLE FROM article a WHERE a.ID_ARTICLE NOT IN (
    SELECT DISTINCT ID_ARTICLE FROM ventes WHERE ANNEE = 2014
)
GROUP BY a.NOM_ARTICLE;

--  21. Coder de 3 manières différentes la requête suivante :
--  Lister les pays qui fabriquent des bières de type ‘Trappiste’. (3 résultats attendus)
select NOM_PAYS
from pays p
    left join marque m on p.ID_PAYS = m.ID_PAYS
    left join article a on m.ID_MARQUE = a.ID_MARQUE
    left join type t on t.ID_TYPE = a.ID_TYPE
    where t.NOM_TYPE = 'Trappiste'
    group by NOM_PAYS;

select NOM_PAYS
from pays p
    left join marque m USING(ID_PAYS)
    left join article a USING(ID_MARQUE)
    where a.ID_TYPE = 
    (
        select ID_TYPE
        from type
        where NOM_TYPE = 'Trappiste'
    )
    group by NOM_PAYS;

select distinct NOM_PAYS
from pays p
    left join marque m on p.ID_PAYS = m.ID_PAYS
    left join article a on m.ID_MARQUE = a.ID_MARQUE
    left join type t on t.ID_TYPE = a.ID_TYPE
    where t.NOM_TYPE = 'Trappiste';

--  22. Lister les tickets sur lesquels apparaissent un des articles apparaissant aussi sur
--  le ticket 2014-856. (38 résultats attendus)
SELECT DISTINCT v.NUMERO_TICKET FROM ventes v WHERE v.ID_ARTICLE IN (SELECT ID_ARTICLE FROM ventes WHERE NUMERO_TICKET = "856" AND ANNEE = "2014");

--  23. Lister les articles ayant un degré d’alcool plus élevé que la plus forte des
--  trappistes. (74 résultats attendus)
SELECT a.ID_ARTICLE, a.NOM_ARTICLE FROM article a WHERE a.TITRAGE > (SELECT MAX(a2.TITRAGE) FROM article a2 JOIN type t ON a2.ID_TYPE = t.ID_TYPE WHERE t.NOM_TYPE = "Trappiste");

--  24. Afficher les quantités vendues pour chaque couleur en 2014.
-- (5 résultats attendus : Blonde	"72569", Brune	"49842"	,
-- NULL	"36899", Ambrée	31427, Blanche	14416	)
SELECT c.NOM_COULEUR, SUM(v.QUANTITE) as total_quantite FROM ventes v JOIN article a ON v.ID_ARTICLE = a.ID_ARTICLE JOIN couleur c ON a.ID_Couleur = c.ID_Couleur JOIN ticket t ON v.NUMERO_TICKET = t.NUMERO_TICKET AND v.ANNEE = t.ANNEE WHERE YEAR(t.DATE_VENTE) = 2014 GROUP BY c.NOM_COULEUR;

--  25. Donner pour chaque fabricant, le nombre de tickets sur lesquels apparait un de
--  ses produits en 2014. (11 résultats attendus dont 7383 sans NULL)
SELECT f.NOM_FABRICANT, COUNT(DISTINCT v.NUMERO_TICKET) AS total_tickets FROM fabricant f JOIN marque m ON f.ID_FABRICANT = m.ID_FABRICANT JOIN article a ON m.ID_MARQUE = a.ID_MARQUE JOIN ventes v ON a.ID_ARTICLE = v.ID_ARTICLE JOIN ticket t ON v.NUMERO_TICKET = t.NUMERO_TICKET AND v.ANNEE = t.ANNEE WHERE YEAR(t.DATE_VENTE) = 2014 GROUP BY f.NOM_FABRICANT; 

-- 26. Donner l’ID, le nom, le volume et la quantité vendue des 20 articles les plus  vendus en 2016. 
--(résultats allant de l'id "3192" avec 597 ventes à l'id "3789" avec 488 ventes)
SELECT v.ID_ARTICLE, a.NOM_ARTICLE, a.VOLUME, SUM(v.QUANTITE) as total_quantite FROM ventes v JOIN article a ON v.ID_ARTICLE = a.ID_ARTICLE JOIN ticket t ON v.NUMERO_TICKET = t.NUMERO_TICKET AND v.ANNEE = t.ANNEE WHERE YEAR(t.DATE_VENTE) = 2016 GROUP BY v.ID_ARTICLE ORDER BY total_quantite DESC LIMIT 20;

--  27. Donner l’ID, le nom, le volume et la quantité vendue des 5 ‘Trappistes’ les plus vendus en 2016.
-- (résultats allant de l'id "3588" avec 502 ventes à l'id "2104" avec 357 ventes)
SELECT v.ID_ARTICLE, a.NOM_ARTICLE, a.VOLUME, SUM(v.QUANTITE) as total_quantite FROM ventes v JOIN article a ON v.ID_ARTICLE = a.ID_ARTICLE JOIN type t ON a.ID_TYPE = t.ID_TYPE JOIN ticket tkt ON v.NUMERO_TICKET = tkt.NUMERO_TICKET AND v.ANNEE = tkt.ANNEE WHERE YEAR(tkt.DATE_VENTE) = 2016 AND t.NOM_TYPE = "Trappiste" GROUP BY v.ID_ARTICLE ORDER BY total_quantite DESC LIMIT 5;

--  28. Donner l’ID, le nom, le volume et les quantité vendues en 2015 et 2016, des
--  bières dont les ventes ont été stables. (moins de 1% de variation)
-- (29 résultats attendus)
SELECT v.ID_ARTICLE, a.NOM_ARTICLE, a.VOLUME, SUM(CASE WHEN YEAR(t.DATE_VENTE) = 2015 THEN v.QUANTITE ELSE 0 END) AS quantite_2015, SUM(CASE WHEN YEAR(t.DATE_VENTE) = 2016 THEN v.QUANTITE ELSE 0 END) AS quantite_2016 FROM ventes v JOIN article a ON v.ID_ARTICLE = a.ID_ARTICLE JOIN ticket t ON v.NUMERO_TICKET = t.NUMERO_TICKET AND v.ANNEE = t.ANNEE GROUP BY v.ID_ARTICLE HAVING abs(SUM(CASE WHEN YEAR(t.DATE_VENTE) = 2015 THEN v.QUANTITE ELSE 0 END) - SUM(CASE WHEN YEAR(t.DATE_VENTE) = 2016 THEN v.QUANTITE ELSE 0 END)) / SUM(CASE WHEN YEAR(t.DATE_VENTE) = 2015 THEN v.QUANTITE ELSE 0 END) < 0.01;
-- OU
select ID_ARTICLE, NOM_ARTICLE, VOLUME,
    (select sum(quantite) from ventes where ID_ARTICLE = A.ID_ARTICLE and ANNEE = 2015) as '2015',
    (select sum(quantite) from ventes where ID_ARTICLE = A.ID_ARTICLE and ANNEE = 2016) as '2016'
  from article as A
  where cast((select sum(quantite) from ventes where ID_ARTICLE = A.ID_ARTICLE and ANNEE = 2016) -
    (select sum(quantite) from ventes where ID_ARTICLE = A.ID_ARTICLE and ANNEE = 2015) as float) /
    (select sum(quantite) from ventes where ID_ARTICLE = A.ID_ARTICLE and ANNEE = 2015) * 100 between -1 and 1
  order by A.ID_ARTICLE

--  29. Lister les types de bières suivant l’évolution de leurs ventes entre 2015 et 2016.
--  Classer le résultat par ordre décroissant des performances.
-- (13 résultats attendus allant de "Bio" 82.71 à "Lambic" 47.28)

SELECT t.NOM_TYPE,
       SUM(CASE WHEN YEAR(tt.DATE_VENTE) = 2015 THEN v.QUANTITE ELSE 0 END) AS quantite_2015,
       SUM(CASE WHEN YEAR(tt.DATE_VENTE) = 2016 THEN v.QUANTITE ELSE 0 END) AS quantite_2016,
       (SUM(CASE WHEN YEAR(tt.DATE_VENTE) = 2016 THEN v.QUANTITE ELSE 0 END) / 
        SUM(CASE WHEN YEAR(tt.DATE_VENTE) = 2015 THEN v.QUANTITE ELSE 0 END)) * 100 AS performance
FROM type t
JOIN article a ON t.ID_TYPE = a.ID_TYPE
JOIN ventes v ON a.ID_ARTICLE = v.ID_ARTICLE
JOIN ticket tt ON v.NUMERO_TICKET = tt.NUMERO_TICKET
GROUP BY t.NOM_TYPE
ORDER BY performance DESC;SELECT t.NOM_TYPE,
       SUM(CASE WHEN YEAR(tt.DATE_VENTE) = 2015 THEN v.QUANTITE ELSE 0 END) AS quantite_2015,
       SUM(CASE WHEN YEAR(tt.DATE_VENTE) = 2016 THEN v.QUANTITE ELSE 0 END) AS quantite_2016,
       (SUM(CASE WHEN YEAR(tt.DATE_VENTE) = 2016 THEN v.QUANTITE ELSE 0 END) / 
        SUM(CASE WHEN YEAR(tt.DATE_VENTE) = 2015 THEN v.QUANTITE ELSE 0 END)) * 100 AS performance
FROM type t
JOIN article a ON t.ID_TYPE = a.ID_TYPE
JOIN ventes v ON a.ID_ARTICLE = v.ID_ARTICLE
JOIN ticket tt ON v.NUMERO_TICKET = tt.NUMERO_TICKET AND v.ANNEE = tt.ANNEE
GROUP BY t.NOM_TYPE
ORDER BY performance DESC;
-- OU
select ID_TYPE, NOM_TYPE,
    round(
        (
        cast(
        (
            select sum(quantite)
            from ventes
            where annee = 2016
            and ID_article in (
                select ID_article
                from article
                where ID_TYPE = t.id_type
                )
        )
        - 
        (
            select sum(quantite)
            from ventes
            where annee = 2015
            and ID_article in 
                (
                select ID_article
                from article
                where ID_TYPE = t.id_type
                )
        )
        as float) /
        (
        select sum(quantite)
        from ventes
        where annee = 2015
        and ID_article in 
            (
            select ID_article
            from article
            where ID_TYPE = t.id_type
            )
        )
    * 100) ,2) as evolution
from type t
order by evolution desc

--  30. Existe-t-il des tickets sans vente ? (3 résultats attendus)
select ANNEE, NUMERO_TICKET
from ticket
where concat(ANNEE, NUMERO_TICKET) not in
    (select concat(ANNEE, NUMERO_TICKET) from ventes);

--  31. Lister les produits vendus en 2016 dans des quantités jusqu’à -15% des quantités
--  de l’article le plus vendu. (12 résultats attendus)
select a.ID_ARTICLE,
    NOM_ARTICLE,
    (select sum(QUANTITE) from ventes where ANNEE = 2016 and ID_ARTICLE = a.ID_ARTICLE) as qte
from article a
where (select sum(QUANTITE) from ventes where ANNEE = 2016 and ID_ARTICLE = a.ID_ARTICLE)
        >=
      (select sum(QUANTITE)as q from ventes where ANNEE = 2016 group by ID_ARTICLE order by q desc limit 1) * 0.85
order by qte desc;

--  LES BESOINS DE MISE A JOUR
--  32. Appliquer une augmentation de tarif de 10% pour toutes les bières ‘Trappistes’ de couleur ‘Blonde’ (Résultat attendu : 22 lignes modifiées)
UPDATE article a
JOIN type t ON a.ID_TYPE = t.ID_TYPE
JOIN couleur c ON a.ID_COULEUR = c.ID_COULEUR
SET a.PRIX_ACHAT = a.PRIX_ACHAT * 1.10
WHERE t.NOM_TYPE = 'Trappiste' AND c.NOM_COULEUR = 'Blonde';

--  33. Mettre à jour le degré d’alcool des toutes les bières n’ayant pas cette information.
--  On y mettra le degré d’alcool de la moins forte des bières du même type et de même couleur. (6 lignes modifiées ou 28)
UPDATE article a
JOIN couleur c ON a.ID_COULEUR = c.ID_COULEUR
JOIN type t ON a.ID_TYPE = t.ID_TYPE
SET a.TITRAGE = (
    SELECT MIN(a2.TITRAGE)
    FROM article a2
    WHERE a2.ID_TYPE = a.ID_TYPE AND a2.ID_COULEUR = a.ID_COULEUR AND a2.TITRAGE IS NOT NULL
)
WHERE a.TITRAGE IS NULL;

-- VERSION compliqué qui prend en compte couleur et type séparé :
UPDATE article a SET TITRAGE = 
IF((SELECT MIN(TITRAGE) FROM article WHERE a.ID_Couleur = ID_Couleur AND a.ID_TYPE = ID_TYPE) IS NOT NULL,
        (SELECT MIN(TITRAGE) FROM article WHERE a.ID_Couleur = ID_Couleur AND a.ID_TYPE = ID_TYPE),
    IF((SELECT MIN(TITRAGE) FROM article WHERE a.ID_TYPE = ID_TYPE) IS NOT NULL,
        (SELECT MIN(TITRAGE) FROM article WHERE a.ID_TYPE = ID_TYPE),
        IF((SELECT MIN(TITRAGE) FROM article WHERE a.ID_Couleur = ID_Couleur) IS NOT NULL,
        (SELECT MIN(TITRAGE) FROM article WHERE a.ID_Couleur = ID_Couleur),
        (SELECT MIN(TITRAGE) FROM article)))) 
WHERE TITRAGE IS NULL;
--  34. Suppression des bières qui ne sont pas des bières ! (type ‘Bière Aromatisée’) (262 lignes supprimées)
DELETE FROM article
WHERE ID_TYPE IN (SELECT ID_TYPE FROM type WHERE NOM_TYPE = 'Bière Aromatisée');

--  35. Supprimer les tickets qui n’ont pas de ventes.(3 lignes supprimées)
delete
from ticket
where concat(ANNEE, NUMERO_TICKET) not in
    (select concat(ANNEE, NUMERO_TICKET) from ventes);