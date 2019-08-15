CREATE DATABASE inventory_tamecon;
use inventory_tamecon;

CREATE TABLE usuario(
	id int auto_increment primary key,
    login varchar(50) unique,
    password varchar(60),
	name_user varchar(50),
    access datetime,
    permission_inventory varchar(10),
    permission_taller varchar(10),
    permission_user varchar(10)

);


CREATE TABLE product(
	id int auto_increment primary key,
    name_product varchar(150),
    code_product varchar(50),
    precio_entrada float,
    precio_salida float,
    unidad varchar(20),
    cantidad float
);


CREATE TABLE clients(
	carnet_identidad int primary key,
    name_client varchar(100),
    telefono varchar(20)
);

CREATE TABLE assistant(
	id int auto_increment primary key,
    nombre varchar(100),
    celular varchar(20),
    carnet int unique
);

CREATE TABLE vehicle(
	placa varchar(20) primary key,
    marca varchar(30),
    modelo varchar(30),
    color varchar(30)
);



/*PROCEDIMIENTOS DE ALMACENADO*/

delimiter $
create procedure add_product(
	in name_p varchar(150),
    in code_p varchar(50),
    in price_in float,
    in price_out float,
    in unit varchar(20),
    in cantidad float)
    
	begin
		Insert Into product(name_product,code_product,precio_entrada,precio_salida,unidad,cantidad) 
			values(name_p,code_p,price_in,price_out,unit,cantidad);
end $


/*	EDITAR PRODUCTO*/
delimiter $
create procedure edit_product(
	in id_p int,
    in name_p varchar(150),
    in code_p varchar(50),
    in price_in float,
    in price_out float,
    in unit varchar(20),
    in amount float)
    begin
		UPDATE product SET name_product=name_p,code_product=code_p,precio_entrada=price_in,precio_salida=price_out,
        unidad=unit,cantidad=amount WHERE id = id_p;
end $

/*	AÑADIR CLIENTE */
delimiter $
create procedure add_cliente(
	in carnet int,
    in nombre varchar(100),
    in tel varchar(20)
    )
    
	begin
		Insert Into clients(carnet_identidad,name_client,telefono) 
			values(carnet,nombre,tel);
end $

delimiter $
create procedure edit_client(carnet int,nombre varchar(100),tel varchar(20))
	begin
		UPDATE clients SET name_client=nombre,telefono = tel WHERE carnet_identidad = carnet;
end $


/*ASISTENTES*/

delimiter $
create procedure add_assistant(nomb varchar(100),cel varchar(20),ci int)
	begin
		INSERT INTO assistant(nombre,celular,carnet) VALUES(nomb,cel,ci);
end $
delimiter $
create procedure edit_assistant(id_a int, nomb varchar(100),cel varchar(20),ci int)
	begin
		UPDATE assistant SET nombre= nomb, celular = cel, carnet = ci WHERE id =id_a;
end $

delimiter $
create procedure add_vehicle(plac varchar(20), modelo_v varchar(30),marca_v varchar(30),color_v varchar(30))
begin
	INSERT INTO vehicle(placa,modelo,marca,color) VALUES(plac,modelo_v,marca_v,color_v);
end $

delimiter $
create procedure edit_vehicle(plac varchar(20), modelo_v varchar(30),marca_v varchar(30),color_v varchar(30))
begin
	UPDATE vehicle SET modelo = modelo_v, marca = marca_v, color = color_v WHERE placa = plac;
end $