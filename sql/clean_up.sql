set foreign_key_checks = 0;

truncate table candidate;
truncate table requested_activity;
truncate table participant;
truncate table waiting_candidate;
set foreign_key_checks = 1;

set foreign_key_checks = 0;

truncate table participant;
truncate table waiting_candidate;
set foreign_key_checks = 1;