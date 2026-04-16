CREATE DATABASE LAPTOPZ1;

USE LAPTOPZ1;

CREATE TABLE roles (
    roleID varchar(10) NOT NULL primary key
	check (roleID in ('admins', 'customers', 'employees'))
);

CREATE TABLE users
(
	userID int IDENTITY primary key,
	userName varchar(50) unique,
	phoneNumber decimal(12,0) unique,
	email varchar(100) unique,
	passwords varchar(16),
	roles varchar(10) NOT NULL foreign key (roles) references roles(roleID),
	check (roles in ('admins', 'customers', 'employees'))
);

CREATE TABLE productCategories
(
	pCateID int IDENTITY primary key,
	pCateName nvarchar(150),
	slug varchar(255) unique,
	parentID int foreign key (parentID) references productCategories(pCateID)
);

CREATE TABLE products
(
	productID int IDENTITY primary key,
	productCategory int foreign key (productCategory) references productCategories(pCateID),
	productName nvarchar(255),
	slug varchar(255) unique,
	price decimal(12,2),
	quantity int,
	warrantyMonth int,
	descriptions text,
	statuses bit,
	createAt datetime,
	rating TINYINT CHECK (rating BETWEEN 1 AND 5),
);

CREATE TABLE productImage 
(
	imgID int IDENTITY primary key,
	productID int foreign key (productID) references products(productID),
	imgUrl varchar(255),
	ismain bit
);

CREATE TABLE serviceCategories
(
	serviceCateID int IDENTITY primary key,
	serviceCateName nvarchar(255),
	slug varchar(255) unique,
	parentID int foreign key (parentID) references serviceCategories(serviceCateID)
)

CREATE TABLE serviceses
(
	serviceID int IDENTITY primary key,
	serviceCateID int foreign key (serviceCateID) references serviceCategories(serviceCateID),
	serviceName nvarchar(255),
	slug varchar(255) unique,
	priceFrom decimal(12,2),
	priceTo decimal(12,2),
	warrantyMonth int,
	descriptions text,
	statuses bit,
	rating TINYINT CHECK (rating BETWEEN 1 AND 5),
);

CREATE TABLE orders
(
	orderID int IDENTITY primary key,
	customerID int foreign key (customerID) references users(userID),
	saleStaffID int foreign key (customerID) references users(userID),
	orderType varchar(10) check (orderType in ('sale', 'repair')),
	statuses varchar(10) check (statuses in ('pending', 'processing', 'done')),
	tolalAmount decimal(12,2),
	createAt datetime
);

CREATE TABLE oderItem
(
	orderItemID int IDENTITY primary key,
	orderID int foreign key (orderID) references orders(orderID),
	itemType varchar(10) check (ItemType in ('service', 'product')),
	productID int foreign key (productID) references products(productID),
	serviceID int foreign key (serviceID) references serviceses(serviceID),
	quantity int,
	price decimal(12,2)
);

CREATE TABLE repairAssignments
(
	repairAssignmentsID int IDENTITY primary key,
	orderID int foreign key (orderID) references orders(orderID),
	technicianID int foreign key (technicianID) references users(userID),
	assignedAt datetime,
	note text
);

CREATE TABLE post
(
	postID int IDENTITY primary key,
	tilte nvarchar(255),
	slug varchar(255) unique,
	content nvarchar(max),
	postType nvarchar(10) check (postType in ('news', 'vlog')),
	authorID int foreign key (authorID) references users(userID),
	createAt datetime,
	rating TINYINT CHECK (rating BETWEEN 1 AND 5),
);