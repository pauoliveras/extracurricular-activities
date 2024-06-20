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