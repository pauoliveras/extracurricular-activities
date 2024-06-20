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
ORDER BY sequence_number;

-- waiting list

SELECT 
    c.email as Email,
    c.candidate_number as Número,
    c.candidate_name as Nom,
    c.candidate_group as Grup,
    if(c.membership, 'Si', 'No') as 'Soci/a',
    GROUP_CONCAT(distinct CONCAT(a.requested_order, ': ', a.activity_code) order by a.requested_order separator ' | ') as 'Activitats pendents'
FROM
	afatg_activitats.waiting_candidate wc 
    join afatg_activitats.candidate c on wc.id = c.id
    join afatg_activitats.requested_activity a on wc.id = a.candidate_id and wc.activity_code = a.activity_code

GROUP BY c.candidate_number,
    c.candidate_code,
    c.membership
    ORDER BY membership DESC, c.candidate_number ASC
    ;
    
select code as 'Activitat', capacity as 'Places' from activity;

-- requested activities summary
select a.code, count(*) from activity a join requested_activity ra on a.code = ra.activity_code 
where ra.requested_order = 3
group by a.code order by a.code ASC;

-- total solicituds per activitat
select a.code as activitat, count(*) as solicituds from activity a join requested_activity ra on a.code = ra.activity_code 
group by a.code order by a.code ASC;

-- update activity set capacity = 4;

select sum(capacity) from activity;