INSERT INTO Province(province) VALUES
("quebec");

INSERT INTO City(city,province) VALUES
("montreal","quebec"),
("laval","quebec");

INSERT INTO Address(civicNumber,street,postalCode,city) VALUES
(1234,"jeanne-mance","j4m2f5","laval"),
(5432,"saint-catherine","j5u1i8","montreal"),
(7361,"du parc","j4g7f8","montreal");

INSERT INTO MembershipPlan(name,visibleDuration,monthlyPrice) VALUES
("premium",25,20.99),
("gold",14,15.99);

INSERT INTO Users(firstName,lastName,phoneNumber,email,password,addressId) VALUES
("john","beck","7362512121","john@hotmail.com","$2y$10$8yIi52XZMwCSu1XTG14ZeuEH54bsUEh5ysCtGxNx8DmpjBVVO3qCa",3), -- Password: 123567
("justin","baron","5147264522","justinbaron12345@hotmail.com","$2y$10$oDrD5dTS6nwkS76wnj5NmOZPwepF2LgiNd9Rcu61Qn8biTH/Kk2fq",1), -- Password: 123456
("mikael","moscato","7265357777","mikmoscato@hotmail.com","$2y$10$EECiyX.ONJaPUw4ZYS4XjOCq/45/xKj/27EbIEWSkCWKqiK8KGcAG",2); -- Password: 654263

INSERT INTO BuyerSeller(userId,MembershipPlanName,contactEmail,contactPhone) VALUES
(1,"premium","justin@hotmail.com","6662538787"),
(2,"gold","mik@hotmail.com","8787266666");

INSERT INTO PaymentMethod(expiryMonth,expiryYear,userId) VALUES
(11,2019,1),
(6,2020,2);

INSERT INTO Bill(amount,type,paymentMethodId) VALUES
(20.99,"membership",1),
(15.99,"membership",2);

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
(1,"selling ps3",150.00,"ps3 good condition with 3 games",'2017-12-13',"sell","electronics","consoles"),
(2,"selling audi a4",7999.99,"5 year old",'2017-12-13',"sell","vehicles","cars");

INSERT INTO StrategicLocation(name,clientsPerHour,weekendExtraCostPercent) VALUES
("downtown",50,10);

INSERT INTO StoreManager(userId) VALUES
(1);

INSERT INTO Store(addressId,locationName,userId) VALUES
(3,"downtown",1);

INSERT INTO Ad_Store(adId,storeId,dateOfRent,timeStart,timeEnd,includesDeliveryServices,billId) VALUES
(1,1,'2017-11-17','09:00:00','20:00:00',0,2);

INSERT INTO Promotion(duration,price) VALUES
(7,12.99),(14,16.99);

INSERT INTO AdPromotion(adId,duration,startDate) VALUES
(1,7,CURRENT_TIMESTAMP)

INSERT INTO Transaction(billId,adId) VALUES
(1,1);
