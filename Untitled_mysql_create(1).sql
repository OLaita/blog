CREATE TABLE IF NOT EXISTS `users` (`id` INT NOT NULL AUTO_INCREMENT,`email` varchar(100) NOT NULL UNIQUE,`uname` varchar(100) NOT NULL UNIQUE, `passw` varchar(120) NOT NULL,`role` INT NOT NULL,PRIMARY KEY (`id`));

CREATE TABLE IF NOT EXISTS `comments` (`id` INT NOT NULL AUTO_INCREMENT UNIQUE,`comment` varchar(255),`user` varchar(100),`date_time` DATETIME NOT NULL, `post` INT(11) NOT NULL,PRIMARY KEY (`id`));

CREATE TABLE IF NOT EXISTS `post` (`id` INT(11) NOT NULL AUTO_INCREMENT,`title` varchar(100) NOT NULL,`cont` varchar(255) NOT NULL,`user` varchar(100) NOT NULL,`create_date` DATE NOT NULL,`modify_date` DATE,PRIMARY KEY (`id`));

CREATE TABLE IF NOT EXISTS `post_has_tags` (`post_id` INT(11) NOT NULL,`tags_id` INT NOT NULL);

CREATE TABLE IF NOT EXISTS `tags` (`id` INT NOT NULL AUTO_INCREMENT,`tag` varchar(45) NOT NULL UNIQUE,PRIMARY KEY (`id`));

CREATE TABLE IF NOT EXISTS `roles` (`id` INT NOT NULL AUTO_INCREMENT,`role` varchar(100) NOT NULL UNIQUE,PRIMARY KEY (`id`));

insert into roles(role) values("rol1");

ALTER TABLE `users` ADD CONSTRAINT `users_fk0` FOREIGN KEY (`role`) REFERENCES `roles`(`id`);

ALTER TABLE `comments` ADD CONSTRAINT `comments_fk0` FOREIGN KEY (`user`) REFERENCES `users`(`id`);

ALTER TABLE `comments` ADD CONSTRAINT `comments_fk1` FOREIGN KEY (`post`) REFERENCES `post`(`id`);

ALTER TABLE `post` ADD CONSTRAINT `post_fk0` FOREIGN KEY (`user`) REFERENCES `users`(`id`);

ALTER TABLE `post_has_tags` ADD CONSTRAINT `post_has_tags_fk0` FOREIGN KEY (`post_id`) REFERENCES `post`(`id`);

ALTER TABLE `post_has_tags` ADD CONSTRAINT `post_has_tags_fk1` FOREIGN KEY (`tags_id`) REFERENCES `tags`(`id`);

