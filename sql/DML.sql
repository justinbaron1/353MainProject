INSERT INTO Province(province) VALUES
("quebec");

INSERT INTO City(city,province) VALUES
("montreal","quebec"),
("laval","quebec");

INSERT INTO Address(civicNumber,street,postalCode,city) VALUES
(1234,"jeanne-mance","j4m2f5","laval"),
(5432,"saint-catherine","j5u1i8","montreal");

INSERT INTO MembershipPlan(name,visibleDuration,monthlyPrice) VALUES
("premium",25,20.99),
("gold",14,15.99);

INSERT INTO Users(firstName,lastName,phoneNumber,email,password,addressId) VALUES
("justin","baron","5147264522","justinbaron12345@hotmail.com","123456",1),
("mikael","moscato","7265357777","mikmoscato@hotmail.com","654263",2);

INSERT INTO BuyerSeller(userId,MembershipPlanName,contactEmail,contactPhone) VALUES
(1,"premium","justin@hotmail.com","6662538787"),
(2,"gold","mik@hotmail.com","8787266666");

INSERT INTO PaymentMethod(expiryMonth,expiryYear,userId) VALUES
(11,2019,1),
(6,2020,2);

INSERT INTO Bill(amount,type,paymentMethodId) VALUES
(20.99,"debit",1),
(15.99,"credit",2);

INSERT INTO CreditCard(paymentMethodId,cardNumber,securityCode) VALUES
(2,222333444,123);

INSERT INTO DebitCard(paymentMethodId,cardNumber) VALUES
(1,777222444);

INSERT INTO Category(category) VALUES
("electronics"),
("vehicles");

INSERT INTO SubCategory(category,subCategory) VALUES
("electronics","consoles"),
("vehicles","cars");

INSERT INTO Ad(sellerId,title,price,description,endDate,type,category,subCategory) VALUES
(1,"selling ps3",150.00,"ps3 good condition with 3 games",11-30-2017,"sell","electronics","consoles"),
(2,"selling audi a4",7999.99,"5 year old",11-29-2017,"sell","vehicles","cars");









