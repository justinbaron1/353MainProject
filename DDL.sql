# lvc353_2
# Yan Ming Hu - 40005813
# Tom Lebreux - 40031710
# Simon Dubé - 40005153
# Daniel-Anthony Romero - 27776861
# Justin Baron - 40018436

# SET GLOBAL event_scheduler = ON;


SET foreign_key_checks=0;
DROP TABLE IF EXISTS Ad_Store;
DROP TABLE IF EXISTS Store;
DROP TABLE IF EXISTS StoreLocation;
DROP TABLE IF EXISTS AdPromotion;
DROP TABLE IF EXISTS Promotion;
DROP TABLE IF EXISTS Rating;
DROP TABLE IF EXISTS Ad_AdImage;
DROP TABLE IF EXISTS AdImage;
DROP TABLE IF EXISTS `Transaction`;
DROP TABLE IF EXISTS Ad;
DROP TABLE IF EXISTS SubCategory;
DROP TABLE IF EXISTS category;
DROP TABLE IF EXISTS DebitCard;
DROP TABLE IF EXISTS CreditCard;
DROP TABLE IF EXISTS Bill;
DROP TABLE IF EXISTS PaymentMethod;
DROP TABLE IF EXISTS StoreManager;
DROP TABLE IF EXISTS Admin;
DROP TABLE IF EXISTS BuyerSeller;
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS MembershipPlan;
DROP TABLE IF EXISTS Address;
SET foreign_key_checks=1;

CREATE TABLE Address(
	addressId int AUTO_INCREMENT,
	civicNumber int,
	street varchar(255),
	postalCode varchar(255),
	city varchar(255),
	PRIMARY KEY (addressId)
);

CREATE TABLE MembershipPlan (
	name varchar(255),
	visibleDuration date NOT NULL,
	monthlyPrice decimal(15,2) NOT NULL,
	PRIMARY KEY (name)
);

CREATE TABLE Users (
	userId int AUTO_INCREMENT,
	firstName varchar(255) NOT NULL,
	lastName varchar(255) NOT NULL,
	phoneNumber varchar(255) NOT NULL,
	email varchar(255) NOT NULL,
	password varchar(255) NOT NULL,
	addressID int NOT NULL,
	PRIMARY KEY(userId),
	FOREIGN KEY (addressId) REFERENCES Address(addressId),
	UNIQUE(email)
);

# CREATE TABLE paymentProcessingDepartment(
#	userId
#	amount
#	card Details
#	date_of_payment
# );

CREATE TABLE BuyerSeller (
	userId int,
	membershipPlanName varchar(255) NOT NULL,
	PRIMARY KEY (userId),
	FOREIGN KEY (userId) REFERENCES Users(userId),
	FOREIGN KEY (membershipPlanName) REFERENCES MembershipPlan(name)
	# TODO TRIGGER
	# Upon change, a bill must be generated marking the membership plan change.
	# Every month, a bill is generated according to the current membership.
);

CREATE TABLE Admin (
	userId int,
	PRIMARY KEY (userId),
	FOREIGN KEY (userId) REFERENCES Users(userId)
);

CREATE TABLE StoreManager (
	userId int,
	PRIMARY KEY (userId),
	FOREIGN KEY (userId) REFERENCES Users(userId)
);



CREATE TABLE PaymentMethod (
	paymentMethodId int AUTO_INCREMENT,
	expiryMonth int NOT NULL,
	expiryYear int NOT NULL,
	userId int NOT NULL,
	PRIMARY KEY (paymentMethodId),
	FOREIGN KEY (userId) REFERENCES BuyerSeller(userId)
	# TODO CONSTRAINTS
	# ExpiryMonth must be an integer value between 1 and 12 inclusively.
	# ExpiryMonth and ExpiryYear must be in the future.
);

CREATE TABLE Bill (
	billId int AUTO_INCREMENT,
	dateOfPayment date NOT NULL,
	amount decimal(15,2) NOT NULL,
	type ENUM('debit','credit') NOT NULL,
	paymentMethodId int NOT NULL,
	PRIMARY KEY (billId),
	FOREIGN KEY (paymentMethodId) REFERENCES PaymentMethod(paymentMethodId)
	# TODO CONTRAINT
	# The paymentMethod must not be expired.
);


CREATE TABLE CreditCard (
	paymentMethodId int,
	cardNumber int NOT NULL,
	securityCode int NOT NULL,
	PRIMARY KEY (paymentMethodId),
	FOREIGN KEY (paymentMethodId) REFERENCES PaymentMethod(paymentMethodId)
);

CREATE TABLE DebitCard (
	paymentMethodId int,
	cardNumber int NOT NULL,
	PRIMARY KEY (paymentMethodId),
	FOREIGN KEY (paymentMethodId) REFERENCES PaymentMethod(paymentMethodId)
);

CREATE TABLE category (
	category varchar(255),
	PRIMARY KEY (category)
);


CREATE TABLE SubCategory (
	category varchar(255),
	subCategory varchar(255),
	PRIMARY KEY (category, subCategory),
	FOREIGN KEY (category) REFERENCES category(category)
);

CREATE TABLE Ad (
	adId int AUTO_INCREMENT,
	title varchar(255) NOT NULL,
	description text(1000),
	startDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	endDate date NOT NULL,
	type varchar(255) NOT NULL,
	category varchar(255) NOT NULL,
	subCategory varchar(255) NOT NULL,
	PRIMARY KEY (adId),
	FOREIGN KEY (category,subCategory) REFERENCES SubCategory(category,subCategory)
);

CREATE TABLE Transaction (
	billId int,
	adId int NOT NULL,
	PRIMARY KEY (billId),
	FOREIGN KEY (billId) REFERENCES Bill(billId),
	FOREIGN KEY (adId) REFERENCES Ad(adId)
	# TODO CONTRAINT
	# For a transaction to be stored, the related ad must have at least 1 Ad_Store.
);


CREATE TABLE AdImage (
	url varchar(255),
	PRIMARY KEY (url)
);

CREATE TABLE Ad_AdImage (
	adImageUrl varchar(255),
	adId int,
	PRIMARY KEY (adImageUrl, adId),
	FOREIGN KEY (adImageUrl) REFERENCES AdImage(url),
	FOREIGN KEY (adId) REFERENCES Ad(adId)
);

CREATE TABLE Rating(
	userId int,
	adId int,
	rating int NOT NULL,
	PRIMARY KEY (userId, adId),
	FOREIGN KEY (userId) REFERENCES Users(userId),
	FOREIGN KEY (adId) REFERENCES Ad(adId)
	# TODO CONTRAINT
	# For a user to rate an ad, there must be at least one Transaction marked as purchasedInStore made by this BuyerSeller for this Ad.
);

CREATE TABLE Promotion(
	duration int,
	price decimal(15,2) NOT NULL,
	PRIMARY KEY (duration)
);

CREATE TABLE AdPromotion(
	adId int,
	duration int,
	startDate date,
	billId int NOT NULL,
	PRIMARY KEY (adId),
	FOREIGN KEY (adId) REFERENCES Ad(adId),
	FOREIGN KEY (duration) REFERENCES Promotion(duration),
	FOREIGN KEY (billId) REFERENCES Bill(billId)
);

CREATE TABLE StoreLocation (
	name varchar(255),
	clientsPerHour int NOT NULL,
	PRIMARY KEY (name)
);

CREATE TABLE Store (
	storeId int AUTO_INCREMENT,
	addressId int NOT NULL,
	locationName varchar(255) NOT NULL,
	userId int NOT NULL,
	PRIMARY KEY (storeId),
	FOREIGN KEY (locationName) REFERENCES StoreLocation(name),
	FOREIGN KEY (userId) REFERENCES StoreManager(userId),
	UNIQUE(addressId)
);


CREATE TABLE Ad_Store (
	adId int AUTO_INCREMENT,
	storeId int NOT NULL,
	dateOfRent date NOT NULL, 
	timeStart time NOT NULL,
	timeEnd time NOT NULL,
	includesDeliveryServices boolean NOT NULL DEFAULT 0,
	billId int NOT NULL,
	PRIMARY KEY (adId),
	FOREIGN KEY (adId) REFERENCES Ad(adId),
	FOREIGN KEY (storeId) REFERENCES Store(storeId),
	FOREIGN KEY (billId) REFERENCES Bill(billId),
	UNIQUE(billId)
);













