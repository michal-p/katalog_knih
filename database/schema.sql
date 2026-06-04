CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `year` int(4) NOT NULL,
  `annotation` text DEFAULT NULL,
  `rating` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title_author` (`title`, `author`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default admin account.
-- Password is "admin123" hashed with PHP password_hash(..., PASSWORD_BCRYPT).
-- To change it, generate a new hash: docker exec ebook_web php -r "echo password_hash('newpassword', PASSWORD_BCRYPT);"
-- Then update this INSERT and re-run: docker exec -i ebook_db mysql -uroot -proot ebook_catalog < database/schema.sql
INSERT IGNORE INTO `users` (`username`, `password`)
VALUES ('admin', '$2y$10$ovVXaeXxYgYJriDa.e20c.SjwdnO5R168BZndKf5FjGpGYlNMZdCG');
