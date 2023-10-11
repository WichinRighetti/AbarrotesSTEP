drop database IF EXISTS SistemaInventario;

create database SistemaInventario;
use SistemaInventario;

create table Categoria(
categoria_id int primary key auto_increment,
nombre varchar(255) not null, 
estatus bit default 1
);

create table Subcategoria(
subcategoria_id int primary key auto_increment,
nombre varchar(255) not null, 
estatus bit default 1
);

create table Producto(
producto_id int primary key auto_increment,
categoria_id int not null,
subcategoria_id int not null,
nombre varchar(255) not null,
descripcion varchar(255) not null,
foto varchar(255) not null, 
estatus bit default 1, 

foreign key (categoria_id) references categoria(categoria_id),
foreign key (subcategoria_id) references subcategoria(subcategoria_id)
);

create table almacen(
almacen_id int primary key auto_increment,
Nombre varchar(255) not null,
Direccion varchar(255) not null,
Descripcion varchar(255)
);

create table inventario(
inventario_id int primary key auto_increment,
producto_id int not null,
almacen_id int not null,
cantidad int default 0 not null,
Maximo int default 0 not null

foreign key (producto_id) references producto(producto_id),
foreign key (almacen_id) references almacen(almacen_id)
);

create table entrada(
entrada_id int primary key auto_increment,
inventario_id int not null,
cantidad int not null,
fecha date not null
);

create table salida(
salida_id int primary key auto_increment,
inventario_id int not null,
cantidad int not null,
fecha date not null
);

drop database IF EXISTS SistemaInventario;

create database SistemaInventario;
use SistemaInventario;

create table Categoria(
categoria_id int primary key auto_increment,
nombre varchar(255) not null, 
estatus bit default 1
);

INSERT INTO Categoria (nombre, estatus)
VALUES
  ("Sonido y Música",1),
  ("Mobiliario",1),
  ("Decoración",1),
  ("Catering y Cocina",1),
  ("Bar y Bebidas",1),
  ("Juegos y Entretenimiento",1),
  ("Fotografía y Video",1),
  ("Efectos Especiales",1);

create table Subcategoria(
subcategoria_id int primary key auto_increment,
nombre varchar(255) not null, 
estatus bit default 1
);

INSERT INTO Subcategoria (nombre, estatus)
VALUES
  ("Sillas",1),
  ("Mesas",1),
  ("Manteles",1),
  ("Salas",1),
  ("Calentones",1),
  ("Cubresillas",1),
  ("Servilletas",1);

create table Producto(
producto_id int primary key auto_increment,
categoria_id int not null,
subcategoria_id int not null,
nombre varchar(255) not null,
descripcion varchar(255) not null,
foto varchar(255) not null, 
estatus bit default 1,

foreign key (categoria_id) references categoria(categoria_id),
foreign key (subcategoria_id) references subcategoria(subcategoria_id)
);


INSERT INTO Producto (`categoria_id`,`subcategoria_id`,`nombre`,`descripcion`,`foto`,`estatus`)
VALUES
  (2,5,"Calenton patio","parturient montes, nascetur ridiculus mus. Proin vel arcu eu odio","https://netflix.com",1),
  (2,4,"Sillon individual","orci luctus et ultrices posuere cubilia Curae Donec tincidunt. Donec","http://youtube.com",1),
  (2,3,"Redondo rojo","non, egestas a, dui. Cras pellentesque. Sed dictum. Proin eget","https://pinterest.com",1),
  (2,4,"Taburete","consectetuer ipsum nunc id enim. Curabitur massa. Vestibulum accumsan neque","https://twitter.com",1),
  (2,1,"madera blanca","consequat enim diam vel arcu. Curabitur ut odio vel est","http://zoom.us",1);


create table almacen(
almacen_id int primary key auto_increment,
Nombre varchar(255) not null,
Direccion varchar(255) not null,
Descripcion varchar(255)
);

INSERT INTO almacen (`Nombre`,`Direccion`,`Descripcion`)
VALUES
  ("Almacen Tijuana","ipsum. Curabitur","parturient montes, nascetur ridiculus mus. Proin vel arcu eu odio"),
  ("Almacen Tecate","nec ante. Maecenas","orci luctus et ultrices posuere cubilia Curae Donec tincidunt. Donec");

create table inventario(
inventario_id int primary key auto_increment,
producto_id int not null,
almacen_id int not null,
nombre_almacen varchar(255) not null,
cantidad int default 0 not null,

foreign key (producto_id) references producto(producto_id),
foreign key (almacen_id) references almacen(almacen_id)
);

 

create table entrada(
entrada_id int primary key auto_increment,
inventario_id int not null,
cantidad int not null,
fecha date not null
);

 

create table salida(
salida_id int primary key auto_increment,
inventario_id int not null,
cantidad int not null,
fecha date not null
);

Select e.entrada_id, p.producto_id, c.categoria_id, c.nombre, p.nombre Producto, p.descripcion, p.foto,
                i.inventario_id, a.almacen_id, a.nombre Almacen, a.direccion, a.descripcion 'Descripcion Almacen', i.cantidad,
                e.cantidad Entrada, e.fecha 
                from entrada e left JOIN inventario i ON e.inventario_id = i.inventario_id
                left JOIN almacen a ON i.almacen_id = a.almacen_id
                left JOIN producto p ON i.producto_id = p.producto_id
                left JOIN categoria c ON p.categoria_id = c.categoria_id
                left JOIN subcategoria s ON p.subcategoria_id = s.subcategoria_id;