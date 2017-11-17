INSERT INTO website2.Province(province) VALUES
("quebec");

INSERT INTO website2.City(city,province) VALUES
("montreal","quebec"),
("laval","quebec");

INSERT INTO website2.Address(civicNumber,street,postalCode,city) VALUES
(1234,"jeanne-mance","j4m2f5","laval"),
(5432,"saint-catherine","j5u1i8","montreal");

INSERT INTO website2.MembershipPlan(name,visibleDuration,monthlyPrice) VALUES
("premium",25,20.99),
("gold",14,15.99);

INSERT INTO website2.Users(firstName,lastName,phoneNumber,email,password,addressId) VALUES
("justin","baron","5147264522","justinbaron12345@hotmail.com","123456",1),
("mikael","moscato","7265357777","mikmoscato@hotmail.com","654263",2);

INSERT INTO website2.BuyerSeller(userId,MembershipPlanName,contactEmail,contactPhone) VALUES
(1,"premium","justin@hotmail.com","6662538787"),
(2,"gold","mik@hotmail.com","8787266666");

INSERT INTO website2.PaymentMethod(expiryMonth,expiryYear,userId) VALUES
(11,2019,1),
(6,2020,2);

INSERT INTO website2.Bill(dateOfPayment,amount,type,paymentMethodId) VALUES
(2017-11-14,20.99,"debit",1),
(2017-11-14,15.99,"credit",2);

INSERT INTO website2.CreditCard(paymentMethodId,cardNumber,securityCode) VALUES
(2,222333444,123);

INSERT INTO website2.DebitCard(paymentMethodId,cardNumber) VALUES
(1,777222444);

INSERT INTO website2.Category(category) VALUES
("electronics"),
("vehicles");

INSERT INTO website2.SubCategory(category,subCategory) VALUES
("electronics","consoles"),
("vehicles","cars");

INSERT INTO website2.Ad(sellerId,title,price,description,endDate,type,category,subCategory) VALUES
(1,"selling ps3",150.00,"ps3 good condition with 3 games",11-30-2017,"sell","electronics","consoles"),
(2,"selling audi a4",7999.99,"5 year old",11-29-2017,"sell","vehicles","cars");









