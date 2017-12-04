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
DROP TABLE IF EXISTS AdPosition;
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
	active boolean NOT NULL DEFAULT 1,
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
	endDate date,
	priority int NOT NULL DEFAULT 2,
	isDeleted boolean NOT NULL DEFAULT 0,
	type varchar(255) NOT NULL,
	category varchar(255) NOT NULL,
	subCategory varchar(255) NOT NULL,
	PRIMARY KEY (adId),
	FOREIGN KEY (sellerId) REFERENCES BuyerSeller(userId),
	FOREIGN KEY (category,subCategory) REFERENCES SubCategory(category,subCategory)
		ON UPDATE CASCADE
);

CREATE TABLE AdPosition(
	position int AUTO_INCREMENT,
	subCategoryPosition int NOT NULL DEFAULT 1,
	adId int NOT NULL,
	PRIMARY KEY(position),
	FOREIGN KEY (adId) REFERENCES Ad(adId)
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
	startDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
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
	costPercent int NOT NULL,
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
	
);


CREATE TABLE PaymentExtra (
	cardType varchar(255),
	extraPercent int NOT NULL,
	PRIMARY KEY (cardType)
);

CREATE TABLE StorePrices(
	momentOfWeek varchar(255),
	hourlyPrice decimal(15,2) NOT NULL,
	deliveryHourlyPrice decimal(15,2) NOT NULL,
	PRIMARY KEY (momentOfWeek)
);

-- -----------------------------------------
-- TRIGGERS

-- when creating a payment method, check if it is expired
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

-- Before creating a Bill, check if the payment method is expired
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


-- after creating a transaction, create a default NULL rating for the Ad
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


-- after updating a membership. create a bill if the new membership is not 'normal'
DELIMITER $$
DROP TRIGGER IF EXISTS generateBill$$
CREATE TRIGGER generateBill
AFTER UPDATE
ON BuyerSeller
FOR EACH ROW
	BEGIN
		IF (NEW.membershipPlanName <> OLD.membershipPlanName AND NEW.membershipPlanName<>"normal") THEN
			INSERT INTO Bill(dateOfPayment,amount,type,paymentMethodId) VALUES
			(CURRENT_TIMESTAMP(),
			(SELECT monthlyPrice
			 FROM MembershipPlan
			 WHERE NEW.membershipPlanName=MembershipPlan.name),
			 "membership",
			(SELECT paymentMethodId
			 FROM PaymentMethod
			 WHERE PaymentMethod.userId=NEW.userId AND PaymentMethod.active=1));
		END IF;
	END$$
DELIMITER ;


-- before updating the membership plan of a user. check if they have a payment method. unless they change for 'normal'
DELIMITER $$
DROP TRIGGER IF EXISTS userHasPaymentMethodForMembershipChange$$
CREATE TRIGGER userHasPaymentMethodForMembershipChange
BEFORE UPDATE
ON BuyerSeller
FOR EACH ROW
	BEGIN
		IF ((SELECT COUNT(*) FROM PaymentMethod WHERE PaymentMethod.userId=OLD.userId)=0
			AND NEW.membershipPlanName <> "normal")
			AND NEW.membershipPlanName <> OLD.membershipPlanName THEN
			SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT = "The user does not have a payment method. Can't update the membership plan";
		END IF;
	END$$
DELIMITER ;


-- when adding a transaction, check if the ad is in store
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


-- When adding an AdPromotion, set the priority of the ad to 1
DELIMITER $$
DROP TRIGGER IF EXISTS packagePriority$$
CREATE TRIGGER packagePriority
AFTER INSERT
ON AdPromotion
FOR EACH ROW
BEGIN
	UPDATE Ad
	SET priority=1
	WHERE Ad.adId=NEW.adId;
END$$
DELIMITER ;


-- prevent update on a promotion package
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


-- generate a bill for a promotion. check if user has a payment method
DELIMITER $$
DROP TRIGGER IF EXISTS generateBillForPromotion$$
CREATE TRIGGER generateBillForPromotion
BEFORE INSERT
ON AdPromotion
FOR EACH ROW
BEGIN
	IF ((SELECT COUNT(*) FROM PaymentMethod
		 JOIN Ad ON PaymentMethod.userId=Ad.sellerId
		 WHERE Ad.adId=NEW.adId)=0) THEN
			SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT = "The user does not have a payment method. Can't add a promotion";
	END IF;

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


-- generate a bill for AdStore
DELIMITER $$
DROP TRIGGER IF EXISTS generateBillForAdStore$$
CREATE TRIGGER generateBillForAdStore
BEFORE INSERT
ON Ad_Store
FOR EACH ROW
BEGIN
	CALL getAdStorePrice(@finalPrice,NEW.dateOfRent, NEW.timeStart, NEW.timeEnd,NEW.storeId,NEW.includesDeliveryServices);
	INSERT INTO Bill(dateOfPayment,amount,type,paymentMethodId)
	VALUES(CURRENT_TIMESTAMP,@finalPrice,"AdStore",
		(SELECT paymentMethodId
		 FROM Ad 
		 JOIN BuyerSeller ON BuyerSeller.userId=Ad.sellerId
		 JOIN PaymentMethod ON BuyerSeller.userId=PaymentMethod.userId
		 WHERE Ad.adId=NEW.adId AND PaymentMethod.active=1));
	SET NEW.billId = LAST_INSERT_ID();
END$$
DELIMITER ;


-- when adding an Ad, set its endDate based on the user's membership plan
DELIMITER $$
DROP TRIGGER IF EXISTS getAdEndDate$$
CREATE TRIGGER getAdEndDate
BEFORE INSERT
ON Ad
FOR EACH ROW
BEGIN
	SET @days = (SELECT visibleDuration
	 			 FROM MembershipPlan
	 			 JOIN BuyerSeller ON BuyerSeller.membershipPlanName=MembershipPlan.name
	 			 WHERE BuyerSeller.userId=NEW.sellerId);
	SET NEW.endDate = DATE(DATE_ADD(CURRENT_TIMESTAMP,INTERVAL @days DAY));
END$$
DELIMITER ;


-- when adding an Ad. set its position to the last one
DELIMITER $$
DROP TRIGGER IF EXISTS addAdPosition$$
CREATE TRIGGER addAdPosition
AFTER INSERT
ON Ad
FOR EACH ROW
BEGIN
	INSERT INTO AdPosition(adId) VALUES
	(NEW.adId);
END$$
DELIMITER ;


-- verify if rating is between 1 and 5
DELIMITER $$
DROP TRIGGER IF EXISTS verifyRating$$
CREATE TRIGGER verifyRating
BEFORE UPDATE
ON Rating
FOR EACH ROW
BEGIN
	IF NEW.rating<1 OR NEW.rating>5 THEN
		SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = "incorrect rating. Must be between 1 and 5";
	END IF;
END$$
DELIMITER ;

-- ----------------------------------------
-- PROCEDURES

-- get the price of a given Ad_Store
DELIMITER $$
DROP PROCEDURE IF EXISTS getAdStorePrice$$
CREATE PROCEDURE getAdStorePrice(OUT finalPrice decimal(15,2),IN dateOfRent date, IN timeStart time, IN timeEnd time,IN storeId int, IN includesDeliveryServices boolean)
BEGIN
	IF (WEEKDAY(dateOfRent)<=4) THEN
		BEGIN
			SET @hourlyPrice = (SELECT hourlyPrice FROM StorePrices WHERE momentOfWeek="week");
			IF (includesDeliveryServices) THEN
				SET @hourlyPrice:= @hourlyPrice + (SELECT deliveryHourlyPrice FROM StorePrices WHERE momentOfWeek="week");
			END IF;
		END;
	ELSE
		BEGIN
			SET @hourlyPrice = (SELECT hourlyPrice FROM StorePrices WHERE momentOfWeek="weekend");
			IF (includesDeliveryServices) THEN
				SET @hourlyPrice:= @hourlyPrice + (SELECT deliveryHourlyPrice FROM StorePrices WHERE momentOfWeek="weekend");
			END IF;
		END;
	END IF;

	SET @costPercent = (SELECT costPercent
						FROM StrategicLocation
						JOIN Store ON name=locationName
						WHERE Store.storeId=storeId);

	SET @price = (HOUR(TIMEDIFF(timeStart, timeEnd))) * @hourlyPrice;
	SET finalPrice = @price + (@price*@costPercent/100);
	
END$$
DELIMITER ;

-- delete an Ad by setting isDeleted=1
DELIMITER $$
DROP PROCEDURE IF EXISTS deleteAd$$
CREATE PROCEDURE deleteAd(IN adId int)
BEGIN
	UPDATE Ad
	SET isDeleted=1
	WHERE Ad.adId=adId;
	TRUNCATE TABLE AdPosition;
	INSERT INTO AdPosition
	(SELECT 0,1,Ad.adId FROM Ad WHERE Ad.isDeleted=0 ORDER BY priority);
	CALL resetAdCategoryPosition();
END$$
DELIMITER ;

-- create a transaction for an add using a payment method
DELIMITER $$
DROP PROCEDURE IF EXISTS createTransaction$$
CREATE PROCEDURE createTransaction(IN adId int, IN paymentMethodId int)
BEGIN
	SET @amount = (SELECT price FROM Ad WHERE Ad.adId=adId);
	IF (EXISTS(SELECT * FROM DebitCard WHERE DebitCard.paymentMethodId=paymentMethodId)) THEN
		SET @amount:=@amount * (1 + (SELECT extraPercent FROM PaymentExtra WHERE cardType="debit")/100);
	ELSEIF(EXISTS(SELECT * FROM CreditCard WHERE CreditCard.paymentMethodId=paymentMethodId)) THEN
		SET @amount:=@amount * (1 + (SELECT extraPercent FROM PaymentExtra WHERE cardType="credit")/100);
	END IF;
	INSERT INTO Bill(dateOfPayment,amount,type,paymentMethodId) VALUES
	(CURRENT_TIMESTAMP, @amount, "transaction",paymentMethodId);
	INSERT INTO Transaction(billId,adId) VALUES
	(LAST_INSERT_ID(),adId);
END$$
DELIMITER ;


-- create an Ad and reset the positions
DELIMITER $$
DROP PROCEDURE IF EXISTS createAd$$
CREATE PROCEDURE createAd(OUT adId int,IN sellerId int, IN title varchar(255),IN price decimal(15,2), IN description varchar(255),
IN type varchar(255),IN category varchar(255),IN subCategory varchar(255))
BEGIN
	INSERT INTO Ad(sellerId,title,price,description,type,category,subCategory) VALUES
	(sellerId,title,price,description,type,category,subCategory);
	SET adId=LAST_INSERT_ID();
	TRUNCATE TABLE AdPosition;
	INSERT INTO AdPosition
	(SELECT 0,1, Ad.adId FROM Ad WHERE Ad.isDeleted=0 ORDER BY priority);
	CALL resetAdCategoryPosition();
END$$
DELIMITER ;


-- create a promotion and reset the positions
DELIMITER $$
DROP PROCEDURE IF EXISTS createPromotion$$
CREATE PROCEDURE createPromotion(IN adId int, IN duration int)
BEGIN
	INSERT INTO AdPromotion(adId,duration) VALUES
	(adId,duration);
	TRUNCATE TABLE AdPosition;
	INSERT INTO AdPosition
	(SELECT 0,1,Ad.adId FROM Ad WHERE Ad.isDeleted=0 ORDER BY priority);
	CALL resetAdCategoryPosition();
END$$
DELIMITER ;

-- make a payment method active and make old payment method inactive
DELIMITER $$
DROP PROCEDURE IF EXISTS setActivePaymentMethod$$
CREATE PROCEDURE setActivePaymentMethod(IN userId int, IN paymentMethodId int)
BEGIN
	UPDATE PaymentMethod
	SET active=0
	WHERE PaymentMethod.userId=userId;

	UPDATE PaymentMethod
	SET active=1
	WHERE PaymentMethod.paymentMethodId=paymentMethodId;
END;$$
DELIMITER ;


-- generate a backup for bills
DELIMITER $$
DROP PROCEDURE IF EXISTS generateBackup$$
CREATE PROCEDURE generateBackup()
BEGIN
	DROP TABLE paymentProcessingDepartment;
	CREATE TABLE paymentProcessingDepartment AS (SELECT * FROM Bill);
END$$
DELIMITER ;


-- create a debit card payment method and set it as active
DELIMITER $$
DROP PROCEDURE IF EXISTS createNewDebitCard$$
CREATE PROCEDURE createNewDebitCard(IN cardNumber int, IN expiryMonth int,IN expiryYear int, IN userId int)
BEGIN
	INSERT INTO PaymentMethod(expiryMonth,expiryYear,userId) VALUES
	(expiryMonth,expiryYear,userId);
	INSERT INTO DebitCard(paymentMethodId,cardNumber) VALUES
	(LAST_INSERT_ID(),cardNumber);
	CALL setActivePaymentMethod(userId,LAST_INSERT_ID());
END;$$
DELIMITER ;


-- create a credit card payment method and set it as active
DELIMITER $$
DROP PROCEDURE IF EXISTS createNewCreditCard$$
CREATE PROCEDURE createNewCreditCard(IN cardNumber int, securityCode int, IN expiryMonth int,IN expiryYear int, IN userId int)
BEGIN
	INSERT INTO PaymentMethod(expiryMonth,expiryYear,userId) VALUES
	(expiryMonth,expiryYear,userId);
	INSERT INTO CreditCard(paymentMethodId,cardNumber,securityCode) VALUES
	(LAST_INSERT_ID(),cardNumber,securityCode);
	CALL setActivePaymentMethod(userId,LAST_INSERT_ID());
END;$$
DELIMITER ;


-- set the correct position for a given subCategory
DELIMITER $$
DROP PROCEDURE IF EXISTS setSubCategoryPosition$$
CREATE PROCEDURE setSubCategoryPosition(IN subCategory varchar(255))
BEGIN
	SET @count = 0;
	UPDATE AdPosition
	SET subCategoryPosition=@count:=@count+1
	WHERE((SELECT Ad.subCategory FROM Ad
		   WHERE Ad.adId=AdPosition.adId)=subCategory);
END;$$
DELIMITER ;

-- reset the subCategory position for each sub category
DELIMITER $$
DROP PROCEDURE IF EXISTS resetAdCategoryPosition$$
CREATE PROCEDURE resetAdCategoryPosition()
BEGIN
	CALL setSubCategoryPosition("clothing");
	CALL setSubCategoryPosition("books");
	CALL setSubCategoryPosition("electronics");
	CALL setSubCategoryPosition("car");
	CALL setSubCategoryPosition("sport equipment");
	CALL setSubCategoryPosition("jewlry");
	CALL setSubCategoryPosition("wedding - dresses");
	CALL setSubCategoryPosition("apartments");
	CALL setSubCategoryPosition("tutors");
	CALL setSubCategoryPosition("musical instruments");
	CALL setSubCategoryPosition("photographers");
	CALL setSubCategoryPosition("event planners");
	CALL setSubCategoryPosition("personal trainers");
END$$
DELIMITER ;

-- ----------------------------------------
-- EVENTS

-- generate a backup of bills every month
DELIMITER $$
DROP EVENT IF EXISTS monthlyBackup$$
CREATE EVENT monthlyBackup
ON SCHEDULE EVERY 1 SECOND STARTS (CURRENT_DATE + INTERVAL 23 HOUR)
DO
	CALL generateBackup();$$
DELIMITER ;


-- sets the priority of ads who no longer have a promotion back to (2)
DELIMITER $$
DROP EVENT IF EXISTS checkPromotionExpired$$
CREATE EVENT checkPromotionExpired
ON SCHEDULE EVERY 1 DAY STARTS (CURRENT_DATE())
DO
	BEGIN
		UPDATE Ad
		SET priority=2
		WHERE (DATEDIFF(CURRENT_TIMESTAMP,Ad.startDate)>(SELECT Promotion.duration FROM Promotion
														 JOIN AdPromotion ON Promotion.duration=AdPromotion.duration
														 WHERE Ad.adId=AdPromotion.adId));
	END$$
DELIMITER ;


-- CREATE a bill for the membership plan every month
DELIMITER $$
DROP EVENT IF EXISTS membershipBill$$
CREATE EVENT membershipBill
ON SCHEDULE EVERY 1 MONTH STARTS (CURRENT_DATE())
DO
	INSERT INTO Bill(dateOfPayment,amount,type,paymentMethodId)
	(SELECT CURRENT_TIMESTAMP,monthlyPrice,"membership",paymentMethodId
	FROM BuyerSeller
	JOIN MembershipPlan ON BuyerSeller.membershipPlanName=MembershipPlan.name AND MembershipPlan.name<>"normal"
	JOIN PaymentMethod ON PaymentMethod.userId=BuyerSeller.userId WHERE PaymentMethod.active=1);$$
DELIMITER ;







