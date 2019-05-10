CREATE TABLE IF NOT EXISTS `eticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `ticketQuantity` int(11) NOT NULL,
  `ticketType` varchar(20) NOT NULL,
  `referrals` text,
  `specialRequests` text,
  `purchaseAgreementSigned` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;