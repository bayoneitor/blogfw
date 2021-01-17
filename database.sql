CREATE TABLE `tags` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tag` varchar(45) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
);
CREATE TABLE `user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `email` varchar(255) NOT NULL UNIQUE,
  `passwd` varchar(64) NOT NULL,
  `idRol` INT NOT NULL DEFAULT '1',
  `creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);
CREATE TABLE `roles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `rol` varchar(45) NOT NULL UNIQUE,
  `desc` varchar(255) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
);
CREATE TABLE `comments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `comment` varchar(255) NOT NULL,
  `idUser` INT(11) NOT NULL,
  `idPost` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
);
CREATE TABLE `post` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `cont` TEXT NOT NULL,
  `user` INT(11) NOT NULL,
  `create-date` DATETIME NOT NULL,
  `modify-date` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
);
CREATE TABLE `post_has_tags` (
  `idPost` INT(11) NOT NULL,
  `idTags` INT NOT NULL
);
ALTER TABLE
  `user`
ADD
  CONSTRAINT `user_fk0` FOREIGN KEY (`idRol`) REFERENCES `roles`(`id`);
ALTER TABLE
  `comments`
ADD
  CONSTRAINT `comments_fk0` FOREIGN KEY (`idUser`) REFERENCES `user`(`id`);
ALTER TABLE
  `comments`
ADD
  CONSTRAINT `comments_fk1` FOREIGN KEY (`idPost`) REFERENCES `post`(`id`);
ALTER TABLE
  `post`
ADD
  CONSTRAINT `post_fk0` FOREIGN KEY (`user`) REFERENCES `user`(`id`);
ALTER TABLE
  `post_has_tags`
ADD
  CONSTRAINT `post_has_tags_fk0` FOREIGN KEY (`idPost`) REFERENCES `post`(`id`);
ALTER TABLE
  `post_has_tags`
ADD
  CONSTRAINT `post_has_tags_fk1` FOREIGN KEY (`idTags`) REFERENCES `tags`(`id`);
INSERT INTO
  `DB_blog`.`roles` (`rol`, `desc`)
VALUES
  (
    'default',
    'Usuario con permisos solo para comentar'
  ),('publisher', 'Tiene permisos para publicar');