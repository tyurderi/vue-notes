USE notes;

CREATE TABLE IF NOT EXISTS notes (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `text` TEXT DEFAULT NULL,
  `changed` TIMESTAMP,
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `archived` tinyint(2) default 0
);
