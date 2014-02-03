 create table if not exists team (
 	sokker_team_id varchar(256) not null,
 	team_id varchar(512) not null primary key,
 	name varchar(512) not null,
 	country_id smallint(100) not null,
 	region_id smallint(100) not null,
 	date_created date not null,
 	rank double(9999, 2) not null,
 	national smallint(1) not null,
 	arena_name varchar(512) not null,
 	fanclub_mood smallint(1) not null,
 	juniors_max smallint(2) not null,
 	
 	foreign key country_id references countries(country_id),
 	foreign key sokker_team_id references x_usuario_sokker_team(sokker_team_id)
 );
 
 create table if not exists informacion_semanal (
 	sokker_team_id varchar(256) not null,
 	money integer not null,
 	training_type smallint(1) not null,
 	fanclub_count integer not null,
 	
 	foreign key sokker_team_id references x_usuario_sokker_team(sokker_team_id)
 )