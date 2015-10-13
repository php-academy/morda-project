CREATE TABLE IF NOT EXISTS `city` (
  `code` varchar(10) NOT NULL,
  `name` varchar(256) NOT NULL,
  `lat` float NOT NULL,
  `long` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;