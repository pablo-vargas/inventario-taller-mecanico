
use inventory_tamecon;
drop procedure add_workshop;

delimiter $
create procedure add_workshop(pl varchar(20),ci int,id_a int,fecha date ,tipo int)
begin
	INSERT into vehicle_entry(placa,carnet_cliente,id_assistant,fecha_ingreso,tipo_trabajo,
		costo_inventario,costo_trabajo,descuento_inventario,descuento_trabajo,adelantos,adelantos_inventario,fecha_salida)
		VALUES(pl,ci,id_a,fecha,tipo,0,0,0,0,0,0,'2000-00-00');
end $

call add_workshop('68',14176648,1,date(NOW()),2);

CREATE TABLE assign_work(
	id int auto_increment primary key,
	id_v int,
    id_job int,
    price_job float,
    FOREIGN KEY (id_v) REFERENCES vehicle_entry(id),
    FOREIGN KEY (id_job) REFERENCES jobs(id)
);
CREATE TABLE assign_job(
	id int auto_increment primary key,
	id_v int,
    detail varchar(100),
    price_job float,
    FOREIGN KEY (id_v) REFERENCES vehicle_entry(id)
);

/*ultima modificacion*/

delimiter $
create procedure asignar_trabajo(det varchar(100) , vehiculo int ,precio float)
begin
	Set @cost=(Select costo_trabajo from vehicle_entry Where id=vehiculo);
	
    
	Insert Into assign_job(id_v,detail,price_job) 
    Values(vehiculo,det,precio);
    
	set @price = (select sum(price_job) from assign_job where id_v=vehiculo);
    update vehicle_entry set costo_trabajo=@price where id=vehiculo;
    
end $


/*TABLA DE PROVEEDORES*/
create table provider(
	id int primary key auto_increment,
    carnet int,
    nombre varchar(50),
    telefono varchar(20),
    detalle text
);

update provider SET id=1 where id = 2;
select * from provider;
delete from provider where id = 1;


delimiter $
create procedure add_provider(_ci int,_nombre varchar(50),_telef varchar(20),_detail text)
begin
	Insert into provider(carnet,nombre,detalle,telefono)
    Values (_ci,_nombre,_detail,_telef);
end $


/* TABLAS PROVEEDOR  ACTUALIZACION  2.0  */
create table type_product(
    id int auto_increment primary key,
    id_product int,
    id_provider int,
    FOREIGN KEY(id_product) REFERENCES product(id),
    FOREIGN KEY(id_provider) REFERENCES provider(id)
    
);

CREATE TABLE entrada(
    id int AUTO_INCREMENT PRIMARY KEY,
	id_provider int,
    precio float,
    fecha datetime,
    adelanto float,
    FOREIGN KEY(id_provider) REFERENCES provider(id)
);

CREATE TABLE entrada_prod(
	id int AUTO_INCREMENT PRIMARY KEY,
    id_entrada INT,
    id_producto int,
    cantidad float,
    precio float,
    FOREIGN KEY(id_entrada) REFERENCES entrada(id),
    FOREIGN key(id_producto) REFERENCES product(id)
);

CREATE TABLE pagos(
	id int AUTO_INCREMENT PRIMARY KEY,
    id_entrada int,
    monto float,
    fecha datetime,
    detalle text,
    FOREIGN KEY(id_entrada) REFERENCES entrada(id)
);

delimiter $
create PROCEDURE add_entrada(id_p int)
begin 
	Insert into entrada(id_provider,precio,fecha,adelanto) values (id_p,0,now(),0);
end $

delimiter $
CREATE PROCEDURE add_pago(id_entrad int,_monto float,_detalle text,_fecha date)
begin
	SET @amount = (Select adelanto from entrada where id=id_entrad);
	insert into pagos(id_entrada,monto,detalle,fecha) VALUES (id_entrad,_monto,_detalle,_fecha);
    UPDATE entrada SET adelanto=(@amount+_monto) where id=id_entrad;
end $


delimiter $
create procedure add_entry_product(_id int ,_id_prod int,_cantidad float)
BEGIN
    SET @precio = (Select precio_entrada from product where id = _id_prod);
    SET @cant = (Select cantidad from product where id = _id_prod);
    SET @price_entry = (Select precio from entrada where id=_id);

    insert into entrada_prod(id_entrada,id_producto,cantidad,precio)
    values(_id,_id_prod,_cantidad,@precio);

    Update entrada SET precio = (@price_entry+(@precio*_cantidad)) where id= _id;
    Update product Set cantidad= (@cant+_cantidad) where id=_id_prod;

End $


delimiter $
create procedure edit_entry_product(_id int,_cant float,_precio float)
begin 
    
    Update entrada_prod set cantidad=_cant, precio=_precio where id=_id;
    set @entry = (Select id_entrada from entrada_prod where id=_id);
    set @total = (Select sum(precio*cantidad) from entrada_prod where id_entrada=@entry);
    Update entrada SET precio = @total where id= @entry;
end $

delimiter $
create procedure remove_entry_product(_id int)
begin 
    set @entry = (Select id_entrada from entrada_prod where id=_id);
    delete from entrada_prod where id = _id;
    
    set @total = (Select sum(precio*cantidad) from entrada_prod where id_entrada=@entry);
    Update entrada SET precio = @total where id= @entry;
end $

/*MODIFICAR MATERIALES DE REGISTRO DE VEHICULOS*/
delimiter $
create procedure remove_product_vehicle(_id int)
begin
	set @idv = (Select id_v from inventory_consumption where id=_id);
    DELETE FROM inventory_consumption where id=_id;
    set @price = (select sum(precio_entrega*cantidad_entrega) from inventory_consumption where id_v=@idv);
    update vehicle_entry set costo_inventario=@price where id=@idv;
end $ 

call remove_product_vehicle(5);

delimiter $
create procedure update_product_vehicle(_id int,_precio float, _cantidad float, _nombre varchar(100))
begin
	update inventory_consumption set precio_entrega=_precio,cantidad_entrega=_cantidad,n_product=_nombre
    where id=_id;
    set @idv = (Select id_v from inventory_consumption where id=_id);
	set @price = (select sum(precio_entrega*cantidad_entrega) from inventory_consumption where id_v=@idv);
    update vehicle_entry set costo_inventario=@price where id=@idv;
end $

delimiter $
create procedure remove_job_vehicle(_id int)
begin
	set @idv = (Select id_v from assign_job where id=_id);
    DELETE FROM assign_job where id=_id;
    set @price = (select sum(price_job) from assign_job where id_v=@idv);
    update vehicle_entry set costo_trabajo=@price where id=@idv;
end $ 

delimiter $
create procedure update_job_vehicle(_id int,_precio float,_detalle varchar(100))
 begin
	Update assign_job set detail=_detalle, price_job=_precio where id = _id;
    
    set @idv = (Select id_v from assign_job where id=_id);
    set @price = (select sum(price_job) from assign_job where id_v=@idv);
    update vehicle_entry set costo_trabajo=@price where id=@idv;
    
 end $