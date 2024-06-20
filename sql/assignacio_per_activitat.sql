-- places assignades
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
-- assignacions sense plaça
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
	WHERE c.id not in (select candidate_id FROM participant)
GROUP BY c.candidate_number,
    c.candidate_code,
    c.membership
    ORDER BY candidate_number ASC
    ;