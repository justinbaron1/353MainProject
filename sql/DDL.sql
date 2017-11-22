# lvc353_2
# Yan Ming Hu - 40005813
# Tom Lebreux - 40031710
# Simon Dubé - 40005153
# Daniel-Anthony Romero - 27776861
# Justin Baron - 40018436

SET GLOBAL event_scheduler = ON;


SET foreign_key_checks=0;
DROP TABLE IF EXISTS StorePrices;
DROP TABLE IF EXISTS PaymentExtra;
DROP TABLE IF EXISTS Ad_Store;
DROP TABLE IF EXISTS Store;
DROP TABLE IF EXISTS StrategicLocation;
DROP TABLE IF EXISTS AdPromotion;
DROP TABLE IF EXISTS Promotion;
DROP TABLE IF EXISTS Rating;
DROP TABLE IF EXISTS Ad_AdImage;
DROP TABLE IF EXISTS AdImage;
DROP TABLE IF EXISTS Transaction;
DROP TABLE IF EXISTS Ad;
DROP TABLE IF EXISTS SubCategory;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS DebitCard;
DROP TABLE IF EXISTS CreditCard;
DROP TABLE IF EXISTS Bill;
DROP TABLE IF EXISTS PaymentMethod;
DROP TABLE IF EXISTS StoreManager;
DROP TABLE IF EXISTS Admin;
DROP TABLE IF EXISTS BuyerSeller;
DROP TABLE IF EXISTS paymentProcessingDepartment;
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS MembershipPlan;
DROP TABLE IF EXISTS Address;
DROP TABLE IF EXISTS City;
DROP TABLE IF EXISTS Province;
SET foreign_key_checks=1;


CREATE TABLE Province(
	province varchar(255),
	PRIMARY KEY (province)
);

CREATE TABLE City(
	city varchar(255),
	province varchar(255) NOT NULL,
	PRIMARY KEY (city),
	FOREIGN KEY (province) REFERENCES Province(province)
		ON UPDATE CASCADE
);

CREATE TABLE Address(
	addressId int AUTO_INCREMENT,
	civicNumber int NOT NULL,
	street varchar(255) NOT NULL,
	postalCode varchar(255) NOT NULL,
	city varchar(255) NOT NULL,
	PRIMARY KEY (addressId),
	FOREIGN KEY (city) REFERENCES City(city)
		ON UPDATE CASCADE
);

CREATE TABLE MembershipPlan (
	name varchar(255),
	visibleDuration int NOT NULL,
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
	addressId int NOT NULL,
	PRIMARY KEY(userId),
	FOREIGN KEY (addressId) REFERENCES Address(addressId)
		ON UPDATE CASCADE,
	UNIQUE(email)
);

CREATE TABLE paymentProcessingDepartment(
	billId int NOT NULL,
	dateOfPayment TIMESTAMP NOT NULL,
	amount decimal(15,2) NOT NULL,
	type varchar(255) NOT NULL,
	paymentMethodId int NOT NULL,
	PRIMARY KEY (billId)
);

CREATE TABLE BuyerSeller (
	userId int,
	membershipPlanName varchar(255) NOT NULL DEFAULT 'default',
	contactEmail varchar(255) NOT NULL,
	contactPhone varchar(255) NOT NULL,
	PRIMARY KEY (userId),
	FOREIGN KEY (userId) REFERENCES Users(userId),
	FOREIGN KEY (membershipPlanName) REFERENCES MembershipPlan(name)
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
);

CREATE TABLE Bill (
	billId int AUTO_INCREMENT,
	dateOfPayment TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	amount decimal(15,2) NOT NULL,
	type varchar(255) NOT NULL,
	paymentMethodId int NOT NULL,
	PRIMARY KEY (billId),
	FOREIGN KEY (paymentMethodId) REFERENCES PaymentMethod(paymentMethodId)
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

CREATE TABLE Category (
	category varchar(255),
	PRIMARY KEY (category)
);


CREATE TABLE SubCategory (
	category varchar(255),
	subCategory varchar(255),
	PRIMARY KEY (category, subCategory),
	FOREIGN KEY (category) REFERENCES Category(category)
		ON UPDATE CASCADE
);

CREATE TABLE Ad (
	adId int AUTO_INCREMENT,
	sellerId int,
	title varchar(255) NOT NULL,
	price decimal(15,2) NOT NULL,
	description text(1000),
	startDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	endDate date NOT NULL,
	type varchar(255) NOT NULL,
	category varchar(255) NOT NULL,
	subCategory varchar(255) NOT NULL,
	PRIMARY KEY (adId),
	FOREIGN KEY (sellerId) REFERENCES BuyerSeller(userId),
	FOREIGN KEY (category,subCategory) REFERENCES SubCategory(category,subCategory)
		ON UPDATE CASCADE
);

CREATE TABLE Transaction (
	billId int,
	adId int NOT NULL,
	PRIMARY KEY (billId),
	FOREIGN KEY (billId) REFERENCES Bill(billId),
	FOREIGN KEY (adId) REFERENCES Ad(adId)
);


CREATE TABLE AdImage (
	url varchar(255),
	PRIMARY KEY (url)
);

CREATE TABLE Ad_AdImage (
	adImageUrl varchar(255),
	adId int,
	PRIMARY KEY (adImageUrl, adId),
	FOREIGN KEY (adImageUrl) REFERENCES AdImage(url)
		ON UPDATE CASCADE,
	FOREIGN KEY (adId) REFERENCES Ad(adId)
);

CREATE TABLE Rating(
	userId int,
	adId int,
	rating int,
	PRIMARY KEY (userId, adId),
	FOREIGN KEY (userId) REFERENCES BuyerSeller(userId),
	FOREIGN KEY (adId) REFERENCES Ad(adId)
);

CREATE TABLE Promotion(
	duration int,
	price decimal(15,2) NOT NULL,
	PRIMARY KEY (duration)
);

CREATE TABLE AdPromotion(
	adId int,
	duration int,
	startDate TIMESTAMP,
	billId int,
	PRIMARY KEY (adId),
	FOREIGN KEY (adId) REFERENCES Ad(adId),
	FOREIGN KEY (duration) REFERENCES Promotion(duration),
	FOREIGN KEY (billId) REFERENCES Bill(billId),
	UNIQUE(billId)
);

CREATE TABLE StrategicLocation (
	name varchar(255),
	clientsPerHour int NOT NULL,
	weekendExtraCostPercent int NOT NULL,
	PRIMARY KEY (name)
);

CREATE TABLE Store (
	storeId int AUTO_INCREMENT,
	addressId int NOT NULL,
	locationName varchar(255) NOT NULL,
	userId int NOT NULL,
	PRIMARY KEY (storeId),
	FOREIGN KEY (addressId) REFERENCES Address(addressId),
	FOREIGN KEY (locationName) REFERENCES StrategicLocation(name)
		ON UPDATE CASCADE,
	FOREIGN KEY (userId) REFERENCES StoreManager(userId),
	UNIQUE(addressId)
);


CREATE TABLE Ad_Store (
	adId int,
	storeId int NOT NULL,
	dateOfRent date NOT NULL, 
	timeStart time NOT NULL,
	timeEnd time NOT NULL,
	includesDeliveryServices boolean NOT NULL DEFAULT 0,
	billId int,
	PRIMARY KEY (adId, storeId, dateOfRent),
	FOREIGN KEY (adId) REFERENCES Ad(adId),
	FOREIGN KEY (storeId) REFERENCES Store(storeId),
	FOREIGN KEY (billId) REFERENCES Bill(billId),
	UNIQUE(billId)
	# TODO TRIGGER
	# ON DELETE: Delete the Bill related
	# dateOfRent must be in the future
);


CREATE TABLE PaymentExtra (
	cardType varchar(255),
	extraPercent int NOT NULL,
	PRIMARY KEY (cardType)
);

CREATE TABLE StorePrices(
	momentOfWeek varchar(255),
	hourlyPrice decimal(15,2) NOT NULL,
	PRIMARY KEY (momentOfWeek)
);

DELIMITER $$
DROP TRIGGER IF EXISTS ExpiryMonthChecker$$
CREATE TRIGGER ExpiryMonthChecker
BEFORE INSERT 
ON PaymentMethod
FOR EACH ROW
BEGIN
   IF (NEW.ExpiryMonth<1 OR NEW.ExpiryMonth>12) THEN
      SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'ExpiryMonth has to be between 1 and 12';
   ELSEIF (NEW.ExpiryYear=YEAR(CURRENT_DATE())) THEN
   		IF NEW.ExpiryMonth<MONTH(CURRENT_DATE()) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'The month is not valid';
		END IF;
   ELSEIF NEW.ExpiryYear<YEAR(CURRENT_DATE()) THEN
   		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'The year is not valid';
   END IF; 
END;$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS paymentMethodExpiredChecker$$
CREATE TRIGGER paymentMethodExpiredChecker
BEFORE INSERT 
ON Bill
FOR EACH ROW
BEGIN
	SET @month=(SELECT expiryMonth FROM PaymentMethod WHERE NEW.paymentMethodId=PaymentMethod.paymentMethodId);
	SET @year=(SELECT expiryYear FROM PaymentMethod WHERE NEW.paymentMethodId=PaymentMethod.paymentMethodId);
   IF (@month<1 OR @month.ExpiryMonth>12) THEN
      SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'ExpiryMonth has to be between 1 and 12';
   ELSEIF (@year=YEAR(CURRENT_DATE())) THEN
   		IF @month<MONTH(CURRENT_DATE()) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'The month is not valid';
		END IF;
   ELSEIF @year<YEAR(CURRENT_DATE()) THEN
   		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'The year is not valid';
   END IF; 
END;$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS DefaultRatingOnTransaction$$
CREATE TRIGGER DefaultRatingOnTransaction
AFTER INSERT
ON Transaction
FOR EACH ROW
	BEGIN
		INSERT INTO Rating(userId,adId,rating)
		VALUES((SELECT sellerId FROM Ad WHERE NEW.adId=Ad.adId),NEW.adId,NULL);
	END; $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS generateBill$$
CREATE TRIGGER generateBill
AFTER UPDATE
ON BuyerSeller
FOR EACH ROW
	BEGIN
		IF NEW.membershipPlanName <> OLD.membershipPlanName THEN
			INSERT INTO Bill(dateOfPayment,amount,type,paymentMethodId) VALUES
			(CURRENT_TIMESTAMP(),
			(SELECT monthlyPrice
			 FROM MembershipPlan
			 WHERE NEW.membershipPlanName=MembershipPlan.name),
			 "membership",
			(SELECT paymentMethodId
			 FROM PaymentMethod
			 WHERE PaymentMethod.userId=NEW.userId));
		END IF;
	END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS adInStoreCheck$$
CREATE TRIGGER adInStoreCheck
BEFORE INSERT
ON Transaction
FOR EACH ROW
BEGIN
	IF NEW.adId NOT IN (SELECT adId FROM Ad_Store) THEN
		SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = "The ad is not in store";
	END IF;
END$$
DELIMITER ;

			
DELIMITER $$
DROP EVENT IF EXISTS membershipBill$$
CREATE EVENT membershipBill
ON SCHEDULE EVERY 1 MINUTE
DO
	INSERT INTO Bill(dateOfPayment,amount,type,paymentMethodId)
	(SELECT CURRENT_TIMESTAMP,monthlyPrice,"membership",paymentMethodId
	FROM BuyerSeller
	JOIN MembershipPlan ON BuyerSeller.membershipPlanName=MembershipPlan.name AND MembershipPlan.name<>"normal"
	JOIN PaymentMethod ON PaymentMethod.userId=BuyerSeller.userId);$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS preventAdPromotionUpdate$$
CREATE TRIGGER preventAdPromotionUpdate
BEFORE UPDATE
ON AdPromotion
FOR EACH ROW
BEGIN
	SIGNAL SQLSTATE '45000'
	SET MESSAGE_TEXT = "adPromotions cannot be updated";
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS generateBillForPromotion$$
CREATE TRIGGER generateBillForPromotion
BEFORE INSERT
ON AdPromotion
FOR EACH ROW
BEGIN
	INSERT INTO Bill(dateOfPayment,amount,type,paymentMethodId)
	VALUES(CURRENT_TIMESTAMP,
		(SELECT price FROM Promotion WHERE Promotion.duration=NEW.duration),
		"AdPromotion",
		(SELECT paymentMethodId
		 FROM Ad 
		 JOIN BuyerSeller ON BuyerSeller.userId=Ad.sellerId
		 JOIN PaymentMethod ON BuyerSeller.userId=PaymentMethod.userId
		 WHERE Ad.adId=NEW.adId));
	SET NEW.billId = LAST_INSERT_ID();
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS generateBillForAdStore$$
CREATE TRIGGER generateBillForAdStore
BEFORE INSERT
ON Ad_Store
FOR EACH ROW
BEGIN
	SET @hourlyPrice = (SELECT hourlyPrice FROM StorePrices WHERE momentOfWeek="weekend");
	IF (WEEKDAY(NEW.dateOfRent)<=5) THEN
		SET @hourlyPrice = (SELECT hourlyPrice FROM StorePrices WHERE momentOfWeek="week");
	END IF;
	SET @finalPrice = (HOUR(TIMEDIFF(NEW.timeStart, NEW.timeEnd))) * @hourlyPrice;
	INSERT INTO Bill(dateOfPayment,amount,type,paymentMethodId)
	VALUES(CURRENT_TIMESTAMP,@finalPrice,
		"AdStore",
		(SELECT paymentMethodId
		 FROM Ad 
		 JOIN BuyerSeller ON BuyerSeller.userId=Ad.sellerId
		 JOIN PaymentMethod ON BuyerSeller.userId=PaymentMethod.userId
		 WHERE Ad.adId=NEW.adId));
	SET NEW.billId = LAST_INSERT_ID();
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS generateBackup$$
CREATE PROCEDURE generateBackup()
BEGIN
	DROP TABLE paymentProcessingDepartment;
	CREATE TABLE paymentProcessingDepartment AS (SELECT * FROM Bill);
END$$
DELIMITER ;


DELIMITER $$
DROP EVENT IF EXISTS monthlyBackup$$
CREATE EVENT monthlyBackup
ON SCHEDULE EVERY 1 MINUTE
DO
	CALL generateBackup(); $$
DELIMITER ;








