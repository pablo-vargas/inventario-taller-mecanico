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

CREATE TABLE jobs(
	id int auto_increment primary key,
    trabajo varchar(200) unique,
    price_public float,
    price_middle float,
    price_bill float
);

CREATE TABLE vehicle_entry(
	id int auto_increment primary key,
    carnet_cliente int,
    placa varchar(20),
    id_assistant int,
    fecha_ingreso datetime,
    fecha_salida datetime,
    costo_inventario float,
    costo_trabajo float,
    tipo_trabajo int,/*1 ,2 ,3*/
    descuento_inventario float,
    descuento_trabajo float,
    adelantos float,
    adelantos_inventario float,
    FOREIGN KEY (carnet_cliente) references clients(carnet_identidad),
    FOREIGN KEY (placa) references vehicle(placa),
    FOREIGN KEY (id_assistant) references assistant(id)
);

SELECT count(*) FROM vehicle_entry V  inner join assign_work J on V.id= J.id_v
			inner join advance_payments_job AJ on V.id=AJ.id_v
            inner join inventory_consumption IC on V.id=IC.id_v
            inner join advance_payments_inventory AI on V.id=AI.id_v
            HAVING (SUM(J.price_job))>(SUM(AJ.amount)) OR (SUM(IC.precio_entrega*IC.cantidad_entrega))>(SUM(AI.amount)
)  ;

CREATE TABLE assign_work(
	id_v int,
    id_job int,
    price_job float,
    FOREIGN KEY (id_v) REFERENCES vehicle_entry(id),
    FOREIGN KEY (id_job) REFERENCES jobs(id)
);
CREATE TABLE inventory_consumption(
	id_p int,
    id_v int,
    fecha_entrega datetime,
    precio_entrega float,
    cantidad_entrega float,
	FOREIGN KEY (id_p) REFERENCES product(id),
    FOREIGN KEY (id_v) REFERENCES vehicle_entry(id)
);

CREATE TABLE advance_payments_job(
	id_v int,
    amount float,
    fecha_payment datetime,
    detalle varchar(100),
    FOREIGN KEY (id_v) REFERENCES vehicle_entry(id)
);
CREATE TABLE advance_payments_inventory(
	id_v int,
    amount float,
    fecha_payment datetime,
    detalle varchar(100),
    FOREIGN KEY (id_v) REFERENCES vehicle_entry(id)
);




/*INGRESO NUEVO VEHICULO*/
delimiter $
create procedure add_workshop(pl varchar(20),ci int,id_a int,fecha date ,tipo int)
begin
	INSERT into vehicle_entry(placa,carnet_cliente,id_assistant,fecha_ingreso,tipo_trabajo,
		costo_inventario,costo_trabajo,descuento_inventario,descuento_trabajo,adelantos,adelantos_inventario)
		VALUES(pl,ci,id_a,fecha,tipo,0,0,0,0,0,0);
end $

delimiter $
create procedure edit_workshop(id_v int,pl varchar(20),ci int,id_a int,fecha date ,tipo int)
begin
        UPDATE vehicle_entry SET placa=pl,carnet_cliente=ci,id_assistant=id_a,fecha_ingreso=fecha,tipo_trabajo=tipo
			WHERE id = id_v;
end $


/*PROCEDIMIENTOS ALMACENADO PARA TRABAJOS*/
delimiter $
create procedure add_job(job varchar(200),pub float,middle float, bill float)
begin
	INSERT INTO jobs(trabajo,price_public,price_middle,price_bill)
		VALUES(job,pub,middle,bill);
end $
delimiter $
create procedure edit_job(id_j int,job varchar(200),pub float,middle float, bill float)
begin
	UPDATE jobs SET trabajo= job,price_public= pub,price_middle=middle,price_bill=bill
		WHERE id = id_j;
end $
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