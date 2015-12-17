/*
These are comments:
pet(pid, type, name, dob, breed)  dob - date of birth
owner(oid, fname, lname)
owns(pid, oid, dop)   dop - date of purchase
This is a new line of commentary
and another
*/
drop database if exists Pets;


drop table if exists pet;
drop table if exists owner;
drop table if exists owns;

create table owner
	(oid int primary key auto_increment,
	fname varchar(25),
	lname varchar(25));
	
create table pet
	(pid int auto_increment primary key,
	type varchar(10),
	name varchar(25),
	dob date,
	breed varchar(20));
	
create table owns
	(pid int,
	oid int,
	dop date,
	primary key(pid, oid, dop),
	foreign key(pid) references pet(pid) on delete cascade on update cascade,
	foreign key(oid) references owner(oid) on delete cascade on update cascade) ;

insert into owner (fname, lname) 
	values ( 'John', 'Doe');
insert into owner (fname, lname) 
	values ( 'Mary', 'Smith');
insert into owner (fname, lname) 
	values ( 'Jimi', 'Hendrix');
insert into owner (fname, lname) 
	values ( 'Carole', 'King');
	
insert into pet (type, name, dob, breed)
	values ('dog','Billie','2001-04-01','Border Collie');
insert into pet (type, name, dob, breed)
	values ('dog','Seamus','2004-03-01','Border Collie');
insert into pet (type, name, dob, breed)
	values ('cat','Lenore','2002-04-01','Short haired');
insert into pet (type, name, dob, breed)
	values ('cat','Francesca','20004-06-11','Callico');
insert into pet (type, name, dob, breed)
	values ('bird','Rookie','2012-06-11','Cockatiel');
	
insert into owns (pid,oid, dop)
	values (1,1,'2001-5-1');
insert into owns (pid,oid, dop)
	values (2,1,'2004-9-1');
insert into owns (pid,oid, dop)
	values (3,1,'2002-6-1');
insert into owns (pid,oid, dop)
	values (4,2,'2007-9-1');
insert into owns (pid,oid, dop)
	values (5,4,'2014-9-10');

