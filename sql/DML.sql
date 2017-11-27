INSERT INTO Province(province) VALUES
("quebec"),
("ontario");

INSERT INTO City(city,province) VALUES
("montreal","quebec"),
("laval","quebec"),
("toronto","ontario"),
("longueuil","quebec");

INSERT INTO Address(civicNumber,street,postalCode,city) VALUES
(1234,"jeanne-mance","j4m2f5","laval"),
(5432,"saint-catherine","j5u1i8","montreal"),
(7361,"du parc","j4g7f8","montreal"),
(8765,"amherst","k9h3j1","toronto"),
(4444,"rue de tom","g8d8j2","longueuil"),
(3333,"saint-catherine","g4g6h6","montreal"),
(5555,"saint-catherine","9j98j7","montreal");

INSERT INTO MembershipPlan(name,visibleDuration,monthlyPrice) VALUES
("normal",7,0.0),
("silver",14,15.99),
("premium",25,20.99);


INSERT INTO Users(firstName,lastName,phoneNumber,email,password,addressId) VALUES
("john","beck","7362512121","john@hotmail.com","$2y$10$8yIi52XZMwCSu1XTG14ZeuEH54bsUEh5ysCtGxNx8DmpjBVVO3qCa",3), -- Password: 123567
("justin","baron","5147264522","justinbaron12345@hotmail.com","$2y$10$oDrD5dTS6nwkS76wnj5NmOZPwepF2LgiNd9Rcu61Qn8biTH/Kk2fq",1), -- Password: 123456
("mikael","moscato","7265357777","mikmoscato@hotmail.com","$2y$10$EECiyX.ONJaPUw4ZYS4XjOCq/45/xKj/27EbIEWSkCWKqiK8KGcAG",2), -- Password: 654263
("luke","brown","8171234355","luke@hotmail.com","777777",4),
("tom","lebreux","9991321888","tom@hotmail.com","987678",5);

INSERT INTO BuyerSeller(userId,MembershipPlanName,contactEmail,contactPhone) VALUES
(2,"premium","justin@hotmail.com","6662538787"),
(3,"silver","mik@hotmail.com","8787266666"),
(5,"normal","tomleb@hotmail.com","8766789090");

INSERT INTO PaymentMethod(expiryMonth,expiryYear,userId) VALUES
(11,2019,2),
(06,2020,3),
(03,2021,5);

INSERT INTO Bill(amount,type,paymentMethodId) VALUES
(20.99,"membership",1),
(15.99,"membership",3);


INSERT INTO CreditCard(paymentMethodId,cardNumber,securityCode) VALUES
(2,222333444,123);

INSERT INTO DebitCard(paymentMethodId,cardNumber) VALUES
(1,777222444),
(3,888123123);

INSERT INTO Category(category) VALUES
("buy and sell"),
("services"),
("rent"),
("category4");

INSERT INTO SubCategory(category,subCategory) VALUES
("buy and sell","clothing"),
("buy and sell","books"),
("buy and sell","electronics"),
("buy and sell","musical instruments"),
("services","tutors"),
("services","event planners"),
("services","photographers"),
("services","personal trainers"),
("rent","electronics"),
("rent","car"),
("rent","apartments"),
("rent","wedding - dresses"),
("category4","subCategory1"),
("category4","subCategory2"),
("category4","subCategory3"),
("category4","subCategory4");

INSERT INTO Ad(sellerId,title,price,description,endDate,type,category,subCategory) VALUES
(2,"selling ps3",150.00,"ps3 good condition with 3 games",'2017-12-13',"sell","buy and sell","electronics"),
(3,"selling audi a4",7999.99,"5 year old",'2017-12-13',"sell","rent","car"),
(5,"photo shoot",99.99,"family photo shoot","2017-12-10","sell","services","photographers"),
(2,"c++ book",49.99,"programming book in c++. good condition","2017-12-03","buy","buy and sell","books");

INSERT INTO StrategicLocation(name,clientsPerHour,weekendExtraCostPercent) VALUES
("sl1",400,50),
("sl2",300,50),
("sl3",200,50),
("sl4",100,50);

INSERT INTO StoreManager(userId) VALUES
(1);

INSERT INTO Admin(userId) VALUES
(4);

INSERT INTO Store(addressId,locationName,userId) VALUES
(6,"sl2",1),
(7,"sl1",1);

INSERT INTO PaymentExtra(cardType,extraPercent) VALUES
("debit",1),
("credit",3);

INSERT INTO StorePrices(momentOfWeek,hourlyPrice) VALUES
("week",10.00),
("weekend",15.00);

INSERT INTO Ad_Store(adId,storeId,dateOfRent,timeStart,timeEnd,includesDeliveryServices) VALUES
(1,1,'2017-11-17','09:00:00','20:00:00',0),
(4,2,'2017-12-01','10:00:00','19:00:00',1);

INSERT INTO Promotion(duration,price) VALUES
(7,12.99),
(30,50.00),
(60,90.00);

INSERT INTO AdPromotion(adId,duration,startDate) VALUES
(1,7,CURRENT_TIMESTAMP);

INSERT INTO Transaction(billId,adId) VALUES
(1,1),
(5,4);

UPDATE Rating
SET rating=5
WHERE userId=2 AND adId=1;

UPDATE Rating
SET rating=3
WHERE userId=2 AND adId=4;






