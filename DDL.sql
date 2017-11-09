#lvc353_2
#Yan Ming Hu - 40005813
#Tom Lebreux - 40031710
#Simon Dubé - 40005153
#Daniel-Anthony Romero - 27776861
#Justin Baron - 40018436

CREATE TABLE Users (
	userId int AUTO_INCREMENT PRIMARY KEY,
	firstName varchar(255) NOT NULL,
	lastName varchar(255) NOT NULL,
	phoneNumber varchar(255) NOT NULL,
	email varchar(255) NOT NULL,
	password varchar(255) NOT NULL,
	addressID int NOT NULL,
	FOREIGN KEY (addressId) REFERENCES Address(addressId),
	UNIQUE(email)
);

CREATE TABLE BuyerSeller (
	userId int PRIMARY KEY,
	membershipPlanName varchar(255) NOT NULL,
	FOREIGN KEY (userId) REFERENCES Users(userId),
	FOREIGN KEY (membershipPlanName) REFERENCES MembershipPlan(name)
	#TODO TRIGGER
	#Upon change, a bill must be generated marking the membership plan change.
	#Every month, a bill is generated according to the current membership.
);

CREATE TABLE Admin (
	userId int PRIMARY KEY,
	FOREIGN KEY (userId) REFERENCES Users(userId),
);

CREATE TABLE StoreManager (
	userId int PRIMARY KEY,
	FOREIGN KEY (userId) REFERENCES Users(userId),
);


CREATE TABLE MembershipPlan (
	name varchar(255) AUTO_INCREMENT PRIMARY KEY,
	visibleDuration date NOT NULL,
	monthlyPrice decimal(15,2) NOT NULL,
);

CREATE TABLE PaymentMethod (
	paymentMethodId int AUTO_INCREMENT PRIMARY KEY,
	expiryMonth int NOT NULL,
	expiryYear int NOT NULL,
	userId int NOT NULL,
	billId int NOT NULL,
	FOREIGN KEY (userId) REFERENCES Users(userId),
	FOREIGN KEY (billId) REFERENCES Bill(billId)
	#TODO CONSTRAINTS
	#ExpiryMonth must be an integer value between 1 and 12 inclusively.
	#ExpiryMonth and ExpiryYear must be in the future.

);

CREATE TABLE CreditCard (
	paymentMethodId int PRIMARY KEY,
	cardNumber int NOT NULL,
	securityCode int NOT NULL,
	FOREIGN KEY (paymentMethodId) REFERENCES PaymentMethod(paymentMethodId)
);

CREATE TABLE DebitCard (
	paymentMethodId int PRIMARY KEY,
	cardNumber int NOT NULL,
	FOREIGN KEY (paymentMethodId) REFERENCES PaymentMethod(paymentMethodId)
);

CREATE TABLE Bill (
	billId int AUTO_INCREMENT PRIMARY KEY,
	dateOfPayment date NOT NULL,
	amount decimal(15,2) NOT NULL,
	type ENUM('debit','credit') NOT NULL,
	billMethodId int NOT NULL,
	FOREIGN KEY (paymentMethodId) REFERENCES PaymentMethod(paymentMethodId)
	#TODO CONTRAINT
	#The paymentMethod must not be expired.
);

CREATE TABLE Transaction (
	billId int PRIMARY KEY,
	adId int NOT NULL,
	FOREIGN KEY (billId) REFERENCES Bill(paymentId)
	#TODO CONTRAINT
	#For a transaction to be stored, the related ad must have at least 1 Ad_Store.
);

CREATE TABLE Ad (
	adId int AUTO_INCREMENT PRIMARY KEY,
	title varchar(255) NOT NULL,
	description text(1000),
	startDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	endDate date NOT NULL,
	type varchar(255) NOT NULL,
	category varchar(255) NOT NULL,
	subCategory varchar(255) NOT NULL,
	FOREIGN KEY (category,subCategory) REFERENCES SubCategory(category,subCategory)
);

CREATE TABLE Ad_AdImage (
	adImageUrl varchar(255) PRIMARY KEY,
	adId int PRIMARY KEY,
	FOREIGN KEY (url) REFERENCES AdImage(url),
	FOREIGN KEY (adId) REFERENCES Ad(adId)
);

CREATE TABLE AdImage (
	url varchar(255) PRIMARY KEY
);

CREATE TABLE Rating(
	userId int PRIMARY KEY,
	adId int PRIMARY KEY,
	rating int NOT NULL,
	FOREIGN KEY (userId) REFERENCES Users(userId),
	FOREIGN KEY (adId) REFERENCES Ad(adId)
	#TODO CONTRAINT
	#For a user to rate an ad, there must be at least one Transaction marked as purchasedInStore made by this BuyerSeller for this Ad.
);

CREATE TABLE Promotion(
	duration int PRIMARY KEY,
	price decimal(15,2) NOT NULL,
);

CREATE TABLE AdPromotion(
	adId int PRIMARY KEY,
	duration int PRIMARY KEY,
	startDate date PRIMARY KEY,
	billId int NOT NULL,
	FOREIGN KEY (adId) REFERENCES Ad(adId),
	FOREIGN KEY (duration) REFERENCES Promotion(duration),
	FOREIGN KEY (billId) REFERENCES Bill(billId)
);

CREATE TABLE SubCategory (
	category varchar(255) PRIMARY KEY,
	subCategory varchar(255) PRIMARY KEY,
	FOREIGN KEY (category) REFERENCES category(category)
);

CREATE TABLE category (
	category varchar(255) PRIMARY KEY
);

CREATE TABLE Store (
	storeId int AUTO_INCREMENT PRIMARY KEY,
	addressId int NOT NULL,
	locationName varchar(255) NOT NULL,
	userId int NOT NULL,
	FOREIGN KEY (locationName) REFERENCES StoreLocation(name),
	FOREIGN KEY (userId) REFERENCES StoreManager(userId),
	UNIQUE(addressId)
);

CREATE TABLE StoreLocation (
	name varchar(255) PRIMARY KEY,
	clientsPerHour int NOT NULL,
);

CREATE TABLE Ad_Store (
	adId int AUTO_INCREMENT PRIMARY KEY,
	storeId int NOT NULL,
	dateOfRent date NOT NULL, 
	timeStart time NOT NULL,
	timeEnd time NOT NULL,
	includesDeliveryServices boolean NOT NULL DEFAULT 0,
	billId int NOT NULL,
	FOREIGN KEY (adId) REFERENCES Ad(adId),
	FOREIGN KEY (storeId) REFERENCES Store(storeId),
	FOREIGN KEY (billId) REFERENCES Bill(billId),
	UNIQUE(billId)
);













