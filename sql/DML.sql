INSERT INTO Province(province) VALUES
("Quebec"),
("Ontario");

INSERT INTO City(city,province) VALUES
("Montreal","Quebec"),
("Laval","Quebec"),
("Toronto","Ontario"),
("Longueuil","Quebec");

INSERT INTO Address(civicNumber,street,postalCode,city) VALUES
(1234,"jeanne-mance","j4m2f5","Laval"),
(5432,"saint-catherine","j5u1i8","Montreal"),
(7361,"du parc","j4g7f8","Montreal"),
(8765,"amherst","k9h3j1","Toronto"),
(4444,"rue de tom","g8d8j2","Longueuil"),
(3333,"saint-catherine","g4g6h6","Montreal"),
(5555,"saint-catherine","9j98j7","Montreal"),
(8888,"rue sherbrooke", "3h38j8","Montreal"),
(9999,"watson street", "9k7h1w","Toronto"),
(1266,"rue peel", "9h7k9j","Montreal"),
(8765,"rue rachel","9h5f7j","Montreal");

INSERT INTO MembershipPlan(name,visibleDuration,monthlyPrice) VALUES
("normal",7,0.0),
("silver",14,15.99),
("premium",25,20.99);


INSERT INTO Users(firstName,lastName,phoneNumber,email,password,addressId) VALUES
("john","beck","7362512121","john@hotmail.com","$2y$10$8yIi52XZMwCSu1XTG14ZeuEH54bsUEh5ysCtGxNx8DmpjBVVO3qCa",3), -- Password: 123567
("justin","baron","5147264522","justinbaron12345@hotmail.com","$2y$10$oDrD5dTS6nwkS76wnj5NmOZPwepF2LgiNd9Rcu61Qn8biTH/Kk2fq",1), -- Password: 123456
("mikael","moscato","7265357777","mikmoscato@hotmail.com","$2y$10$EECiyX.ONJaPUw4ZYS4XjOCq/45/xKj/27EbIEWSkCWKqiK8KGcAG",2), -- Password: 654263
("luke","brown","8171234355","luke@hotmail.com","$2y$10$WbX1c8OiOrApeAkXm/HEw.CW3vxj5gbdMXmwWVbJcFb73ah.u1Jhi",4), -- Password: 777777
("tom","lebreux","9991321888","tom@hotmail.com","$2y$10$/3x8upST6XCI.wruhDAuWOd93vXw.9JbdPcfAWwGmyGwmtz4NLMey",5), -- Password: 987678
("george", "bordean","9815674444", "george@hotmail.com","$2y$10$ayPKFz.DoVCj57aPhk6Z3.zYtdnSzxQOmpVxkYPcMYxyMVOPjNwFS",8), -- Password: 999999
("bob","brook","7776786788", "bob@hotmail.com","$2y$10$EdRBI2WOeBfPY.GYvzxl0uLL63jjg8oM.x34lMh9DohmrehFedrR6",9); -- Password: 000000

INSERT INTO BuyerSeller(userId,MembershipPlanName,contactEmail,contactPhone) VALUES
(2,"premium","justin@hotmail.com","6662538787"),
(3,"silver","mik@hotmail.com","8787266666"),
(5,"normal","tomleb@hotmail.com","8766789090"),
(6,"silver","bordean@hotmail.com","1234321232"),
(7,"premium","brook@hotmail.com","8786788766");

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
("sl1",400,20),
("sl2",300,15),
("sl3",200,10),
("sl4",100,5);

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
(4,2,'2017-12-02','10:00:00','19:00:00',1);

INSERT INTO Promotion(duration,price) VALUES
(7,12.99),
(14,50.00),
(30,90.00);

INSERT INTO AdPromotion(adId,duration,startDate) VALUES
(1,7,CURRENT_TIMESTAMP);

CALL createTransaction(1,1);
CALL createTransaction(4,1);


UPDATE Rating
SET rating=5
WHERE userId=2 AND adId=1;

UPDATE Rating
SET rating=3
WHERE userId=2 AND adId=4;






