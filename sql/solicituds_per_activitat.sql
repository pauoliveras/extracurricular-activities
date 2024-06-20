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