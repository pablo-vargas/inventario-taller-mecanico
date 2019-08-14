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
select * from product;
call add_product('asd','asdd',55,55,'asd',12);
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
