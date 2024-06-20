SELECT 
    email,
    candidate_name AS 'Nom i Cognoms',
    candidate_group AS 'Curs',
    candidate_number AS 'Número sorteig assignat',
    desired_activity_count AS 'Núm. activitats',
    if(membership, 'Si','No') AS 'Soci/a AFA'
FROM
    afatg_activitats.candidate;