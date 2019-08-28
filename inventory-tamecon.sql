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


CREATE TABLE assign_work(
	id int auto_increment primary key,
	id_v int,
    id_job int,
    price_job float,
    FOREIGN KEY (id_v) REFERENCES vehicle_entry(id),
    FOREIGN KEY (id_job) REFERENCES jobs(id)
);
select count(id_job),id_job,price_job from assign_work where id_v=26 Group BY id_job;
select count(id_job),id_job,price_job,J.trabajo,(price_job*count(id_job)) AS total from assign_work W 
inner join jobs J on W.id_job=J.id where id_v=26 Group BY id_job;

Select (costo_trabajo-descuento_trabajo) AS total, adelantos,(costo_trabajo-descuento_trabajo-adelantos) as saldo
 from vehicle_entry where id = 26;

select carnet_identidad,name_client,nombre,V.placa,VD.marca,VD.modelo,VD.color,date(fecha_ingreso),date(fecha_salida)
from vehicle_entry V inner join assistant A on V.id_assistant= A.id
					inner join clients C on V.carnet_cliente = C.carnet_identidad
                    inner join vehicle VD on V.placa= VD.placa
where V.id=26;

CREATE TABLE inventory_consumption(
	id int auto_increment primary key,
	id_p int,
    id_v int,
    fecha_entrega datetime,
    precio_entrega float,
    cantidad_entrega float,
	FOREIGN KEY (id_p) REFERENCES product(id),
    FOREIGN KEY (id_v) REFERENCES vehicle_entry(id)
); 
select * from inventory_consumption where id_v=26;


delimiter $
create procedure consumo_inv(prod int , vehiculo int ,precio float,cant float)
begin
	Set @ADELANTOS=(Select costo_inventario from vehicle_entry Where id=vehiculo);
    set @Cant=(Select cantidad from product Where id =prod);
    
	Insert Into inventory_consumption(id_p,id_v,fecha_entrega,precio_entrega,cantidad_entrega) 
    Values(prod,vehiculo,NOW(),precio,cant);
    
    UPDATE vehicle_entry SET costo_inventario=(@ADELANTOS+(precio*cant))WHERE id=vehiculo;
    UPDATE product SET cantidad=@Cant-cant WHERE id=prod;
end $

select * from jobs;
select * from vehicle_entry;
select * from assign_work;
call assign_job(1,26,250);

delimiter $
create procedure assign_job(job int , vehiculo int ,precio float)
begin
	Set @cost=(Select costo_trabajo from vehicle_entry Where id=vehiculo);
	
    
	Insert Into assign_work(id_v,id_job,price_job) 
    Values(vehiculo,job,precio);
    
    UPDATE vehicle_entry SET costo_trabajo=(@cost+precio) WHERE id=vehiculo;
    
end $


CREATE TABLE advance_payments_job(
	id int auto_increment primary key,
	id_v int,
    amount float,
    fecha_payment datetime,
    detalle varchar(100),
    FOREIGN KEY (id_v) REFERENCES vehicle_entry(id)
);


CREATE TABLE advance_payments_inventory(
	id int auto_increment primary key,
	id_v int,
    amount float,
    fecha_payment datetime,
    detalle varchar(100),
    FOREIGN KEY (id_v) REFERENCES vehicle_entry(id)
);

delimiter $
create procedure add_advance_job(vehiculo int,monto float,detail varchar(100))
begin
	Set @ADELANTOS=(Select adelantos from vehicle_entry Where id=vehiculo);
	INSERT INTO advance_payments_job(id_v,amount,fecha_payment,detalle)
		VALUES(vehiculo,monto,NOW(),detail);
	UPDATE vehicle_entry SET adelantos=@ADELANTOS+monto WHERE id= vehiculo;
end $

delimiter $
create procedure add_advance_inv(vehiculo int,monto float,detail varchar(100))
begin
	Set @ADELANTOS=(Select adelantos_inventario from vehicle_entry Where id=vehiculo);
	INSERT INTO advance_payments_inventory(id_v,amount,fecha_payment,detalle)
		VALUES(vehiculo,monto,NOW(),detail);
	UPDATE vehicle_entry SET adelantos_inventario=@ADELANTOS+monto WHERE id= vehiculo;
end $


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