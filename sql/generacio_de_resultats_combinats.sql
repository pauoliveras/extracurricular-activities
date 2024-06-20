-- Assignació número aleatori
SELECT 
    email,
    candidate_name AS 'Nom i Cognoms',
    candidate_group AS 'Curs',
    candidate_number AS 'Número sorteig assignat',
    desired_activity_count AS 'Núm. activitats',
    if(membership, 'Si','No') AS 'Soci/a AFA'
FROM
    afatg_activitats.candidate;
    
-- Assignació de plaça (ordenat per activitiat i també per número de seqüència)
SELECT 
    c.candidate_name as Nom,
    c.candidate_group as Grup,
    c.email as Email,
	p.candidate_number as Número,
    a.code as Activitat,
    r.requested_order as Opció,
    p.sequence_number as 'Número seqüència',
    if(c.membership, 'Si', 'No') as 'Soci/a',
    c.desired_activity_count as 'Activitats sol·licitades'
FROM
    afatg_activitats.participant p
    
        JOIN
    activity a ON p.activity_id = a.id
        JOIN
    candidate c ON p.candidate_id = c.id
        JOIN
    requested_activity r ON a.code = r.activity_code
        AND c.id = r.candidate_id
ORDER BY activitat, p.sequence_number ;
    
-- llista d'espera per activitat
SELECT 
wc.activity_code as Activitat,
wc.candidate_number as 'Número candidat',
email as Email,
candidate_name as 'Candidat',
candidate_group as 'Grup',
if (membership, 'Si', 'No') as 'Soci/a',
requested_order as 'Prioritat',
wc.sequence_number as 'Seqüència'
 FROM afatg_activitats.waiting_candidate wc join afatg_activitats.candidate c on wc.id = c.id
 join afatg_activitats.requested_activity ra on wc.id = ra.candidate_id and wc.activity_code = ra.activity_code
 order by Activitat, wc.sequence_number ASC;
 
 -- places assignades agrupades per candidat
SELECT 
    c.candidate_number as Número,
    c.candidate_name as Nom,
    c.candidate_group as Grup,
    c.email as Email,
    if(c.membership, 'Si','No') as 'Soci/a',
    GROUP_CONCAT(distinct CONCAT(a.requested_order, ': ', a.activity_code) order by a.requested_order separator ' | ') as Activitats
FROM
	afatg_activitats.candidate c 
    join afatg_activitats.requested_activity a on c.id = a.candidate_id

GROUP BY c.candidate_number,
    c.candidate_code,
    c.membership
    ORDER BY candidate_number ASC
    ;
    
-- Seqüència d'assignacions
SELECT 
    c.candidate_name as Nom,
    c.candidate_group as Grup,
    c.email as Email,
	p.candidate_number as Número,
    a.code as Activitat,
    r.requested_order as Opció,
    p.sequence_number as 'Número seqüència',
    if(c.membership, 'Si', 'No') as 'Soci/a',
    c.desired_activity_count as 'Activitats sol·licitades'
FROM
    afatg_activitats.participant p
    
        JOIN
    activity a ON p.activity_id = a.id
        JOIN
    candidate c ON p.candidate_id = c.id
        JOIN
    requested_activity r ON a.code = r.activity_code
        AND c.id = r.candidate_id
ORDER BY sequence_number;

-- Sol·licituds per activitat
SELECT 
    a.code as Activitat,
    requested_order as Ordre,
    candidate_number as Número,
    candidate_name as Nom,
    candidate_group as Curs,
    email as Email,
    if (c.membership, 'Si','No') as "Soci/a"
FROM
    afatg_activitats.requested_activity ra
        JOIN
    candidate c ON ra.candidate_id = c.id
        JOIN
    activity a ON ra.activity_code = a.code
    ORDER BY a.code ASC,candidate_number ASC;
    
    
-- Candidats sense cap assignació

SELECT 
    c.candidate_number as Número,
    c.candidate_name as Nom,
    c.candidate_group as Grup,
	c.email as Email,
    if(c.membership, 'Si', 'No') as 'Soci/a',
    GROUP_CONCAT(distinct CONCAT(a.requested_order, ': ', a.activity_code) order by a.requested_order separator ' | ') as 'Activitats'
FROM
	afatg_activitats.candidate c 
    join afatg_activitats.requested_activity a on c.id = a.candidate_id 
WHERE c.id not in (select candidate_id from participant)
GROUP BY c.candidate_number,
    c.candidate_code,
    c.membership
    ORDER BY membership DESC, c.candidate_number ASC
    ;
    
-- total solicituds per activitat
select a.code as activitat, count(*) as solicituds from activity a join requested_activity ra on a.code = ra.activity_code 
group by a.code order by a.code ASC;
    