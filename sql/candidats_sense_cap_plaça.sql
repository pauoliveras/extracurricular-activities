-- candidats sense cap plaça
select * 
from candidate c 
where 
c.id not in (select candidate_id from participant);