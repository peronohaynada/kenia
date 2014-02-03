create table if not exists countries (
	country_id smallint(100) not null primary key,
	name varchar(256) not null,
	currency_name varchar(256) not null,
	currency_rate varchar(256) not null
)