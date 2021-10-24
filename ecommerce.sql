CREATE TABLE IF NOT EXISTS `User` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(255) UNIQUE,
  `password` VARCHAR(255),
  `avatar` VARCHAR(1024),
  `phone` VARCHAR(20),
  `name` VARCHAR(40),
  `sex` ENUM('male', 'female', 'others'),
  `role` ENUM('user', 'admin')
);

CREATE TABLE IF NOT EXISTS  `Product` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255),
  `description` VARCHAR(4096),
  `icon` VARCHAR(1024),
  `price` FLOAT(8,2),
  `quantity` INT(11)
);

CREATE TABLE IF NOT EXISTS `Cart` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `userId` INT(11),
  `productId` INT(11),
  `amount` INT(11),
  FOREIGN KEY(`userId`) REFERENCES User(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`productId`) REFERENCES Product(`id`) ON DELETE CASCADE
);

INSERT INTO `Product` (`title`, `description`, `icon`, `price`, `quantity`) VALUES
('IPhone 14', 'This IPhone is beyond the human', 'This is IPhone icon', 1500.90, 10 ),
('IPad Pro M2', 'This IPad is beyond the human', 'This is IPad icon', 2000.50, 10 ),
('Macbook Pro 16 M2', 'This Macbook is beyond the human', 'This is Macbook icon', 1500.90, 10 ),
('Dell XPS 17 Ultra 2050', 'This Dell XPS is beyond the human', 'This is Dell XPs icon', 2045.45, 10 ),
('Quantum PC', 'This Quantum PC is beyond the human', 'This is Quantum PC icon', 10000, 10 );
