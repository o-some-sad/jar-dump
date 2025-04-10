create database php_project;
use php_project;

create table if not exists users (user_id int primary key auto_increment, name varchar(50) not null, email varchar(100) not null unique,
password varchar(250) not null, 
role enum('user', 'admin') default 'user', 
profile_picture varchar(250) not null, 
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
deleted_at timestamp default null,
verified_at timestamp default null
);

create table if not exists orders (
order_id int primary key auto_increment, user_id int, 
total_price decimal(10,2) not null check (total_price > 0), 
status enum('processing', 'out_for_delivery', 'done'),
room_no int not null, ext int not null,
notes text, 
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
,updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
deleted_at timestamp default null,
foreign key (user_id) references users(user_id)
);

create table if not exists category(
category_id int primary key auto_increment, name varchar(50)
);

create table if not exists products(
product_id int primary key auto_increment, 
name varchar(50) not null, quantity int not null check (quantity >= 0), 
price decimal (10,2) not null check (price > 0), category_id int,
image varchar(250) not null,
description text not null,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
deleted_at timestamp default null,
foreign key (category_id) references category(category_id)
);


create table if not exists order_items(
product_id int, 
order_id int, 
quantity int check(quantity > 0) not null,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
deleted_at timestamp default null,
foreign key (product_id) references products(product_id),
foreign key (order_id) references orders(order_id)
);

-- drop database php_project;
show tables;