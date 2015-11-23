create table sql_key (
	sk_id int not null auto_increment primary key,
	key_name char(255) not null default '',
	rel_tb_num int not null default 0,
	sql_string varchar(65533),
	comments varchar(2048),
	eg_runtime float not null default 0,
	op_type char(16) not null default ''
);


create table sql_params (
	sp_id int not null auto_increment primary key,
	sk_id int not null default 0,
	param_name char(255) not null default '',
	param_comments varchar(2048) not null default '',
	required char(64) not null default '',
	eg_val char(255) not null default '',

);

create table sql_rel_table (
	srt_id int not null auto_increment primary key,
	tb_name char(255) not null default
);


create table sql_projects (
	spj_id int not null auto_increment primary key,
	app_name char(255) not null default '',
	module_name char(255) not null default ''
);