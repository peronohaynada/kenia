create table usuario (
	usuario_id integer not null auto_increment primary key,
	nombre_usuario varchar(64) not null,
	contrasena_usuario varchar(256) not null,
	confirmacion_credenciales_sokker tinyint(1) default 0
);
create table x_usuario_sokker_team (
	sokker_team_id varchar(256) not null,
	usuario_sokker varchar(256) null,
	usuario_contrasena varchar(256) null,
	usuario_id integer not null,
	foreign key usuario_id references usuario(usuario_id)
);
create table equipo_baneado (
	sokker_team_id varchar(256) not null,
	foreign key sokker_team_id references x_usuario_sokker_team(sokker_team_id)
);
create table juniors (
	junior_id varchar(256) not null primary key,
	sokker_team_id varchar(256) not null,
	nombre varchar(256) not null,
	apellido varchar(256) not null,
	edad varchar(256) not null,
	altura varchar(256) not null,
	peso varchar(256) not null,
	imc varchar(256) not null,
	formacion varchar(256) not null,
	semanas varchar(256) not null,
	sigue_en_escuela tinyint(1) not null default 1,
	foreign key sokker_team_id references usuario(sokker_team_id)
);
create table habilidad_junior (
	junior_id varchar(256) not null,
	habilidad varchar(256) not null,
	semanas_restantes varchar(256) not null,
	semana_actual varchar(256) not null,
	dia varchar(256) not null,
	foreign key junior_id references juniors(junior_id) on delete cascade
);