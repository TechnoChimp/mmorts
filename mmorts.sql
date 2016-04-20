-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.6.17 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for mmorts
CREATE DATABASE IF NOT EXISTS `mmorts` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `mmorts`;


-- Dumping structure for table mmorts.characters
CREATE TABLE IF NOT EXISTS `characters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(32) COLLATE latin1_general_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `char_img_id` int(11) NOT NULL,
  `char_type_id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `exp` int(11) NOT NULL,
  `silver` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `id` (`id`),
  KEY `user_id` (`user_id`),
  KEY `city_id` (`city_id`),
  KEY `FK_characters_image` (`char_img_id`),
  CONSTRAINT `characters_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `characters_ibfk_2` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `FK_characters_image` FOREIGN KEY (`char_img_id`) REFERENCES `image` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.character_quest
CREATE TABLE IF NOT EXISTS `character_quest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `char_id` int(11) NOT NULL,
  `quest_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `char_id` (`char_id`),
  KEY `quest_id` (`quest_id`),
  KEY `status_id` (`status_id`),
  CONSTRAINT `FK_character_quest_characters` FOREIGN KEY (`char_id`) REFERENCES `characters` (`id`),
  CONSTRAINT `FK_character_quest_quest` FOREIGN KEY (`quest_id`) REFERENCES `quest` (`id`),
  CONSTRAINT `FK_character_quest_quest_status` FOREIGN KEY (`status_id`) REFERENCES `quest_status` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.cities
CREATE TABLE IF NOT EXISTS `cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(16) COLLATE latin1_general_ci NOT NULL,
  `bg_image_id` int(11) DEFAULT NULL,
  `citytype_id` int(11) DEFAULT NULL,
  `position_1` int(11) DEFAULT NULL,
  `position_2` int(11) DEFAULT NULL,
  `position_3` int(11) DEFAULT NULL,
  `position_4` int(11) DEFAULT NULL,
  `position_5` int(11) DEFAULT NULL,
  `tax_level` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `id` (`id`),
  KEY `position_1` (`position_1`),
  KEY `position_2` (`position_2`),
  KEY `position_3` (`position_3`),
  KEY `position_4` (`position_4`),
  KEY `position_5` (`position_5`),
  KEY `bg_image_id` (`bg_image_id`),
  CONSTRAINT `cities_ibfk_1` FOREIGN KEY (`position_1`) REFERENCES `characters` (`id`),
  CONSTRAINT `cities_ibfk_2` FOREIGN KEY (`position_2`) REFERENCES `characters` (`id`),
  CONSTRAINT `cities_ibfk_3` FOREIGN KEY (`position_3`) REFERENCES `characters` (`id`),
  CONSTRAINT `cities_ibfk_4` FOREIGN KEY (`position_4`) REFERENCES `characters` (`id`),
  CONSTRAINT `cities_ibfk_5` FOREIGN KEY (`position_5`) REFERENCES `characters` (`id`),
  CONSTRAINT `FK_cities_image` FOREIGN KEY (`bg_image_id`) REFERENCES `image` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.city_map_tiles
CREATE TABLE IF NOT EXISTS `city_map_tiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city_id` int(11) NOT NULL,
  `tile_id` int(11) NOT NULL,
  `x_coord` int(2) NOT NULL,
  `y_coord` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `cities_id` (`city_id`),
  KEY `tile_id` (`tile_id`),
  CONSTRAINT `FK_city_map_tiles_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `FK_city_map_tiles_tile` FOREIGN KEY (`tile_id`) REFERENCES `tile` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.goal
CREATE TABLE IF NOT EXISTS `goal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goal_type_id` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `quantity` int(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `goal_type_id` (`goal_type_id`),
  KEY `object_id` (`object_id`),
  CONSTRAINT `FK_goal_goal_type` FOREIGN KEY (`goal_type_id`) REFERENCES `goal_type` (`id`),
  CONSTRAINT `FK_goal_object` FOREIGN KEY (`object_id`) REFERENCES `object` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.goal_type
CREATE TABLE IF NOT EXISTS `goal_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.image
CREATE TABLE IF NOT EXISTS `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE latin1_general_ci NOT NULL,
  `filename` varchar(32) COLLATE latin1_general_ci NOT NULL,
  `img_type_id` int(11),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `filename` (`filename`),
  KEY `id` (`id`),
  KEY `img_type_id` (`img_type_id`),
  CONSTRAINT `FK_images_img_type` FOREIGN KEY (`img_type_id`) REFERENCES `img_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.img_type
CREATE TABLE IF NOT EXISTS `img_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(16) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.inventory
CREATE TABLE IF NOT EXISTS `inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `character_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_count` tinyint(3) unsigned NOT NULL,
  `slot` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `player_id` (`character_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `FK_inventory_characters` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`),
  CONSTRAINT `FK_inventory_item` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.item
CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(128) COLLATE latin1_general_ci NOT NULL,
  `stackable` tinyint(1) unsigned NOT NULL,
  `icon` varchar(32) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.object
CREATE TABLE IF NOT EXISTS `object` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(16) NOT NULL,
  `description` varchar(256) NOT NULL,
  `obj_type_id` int(11) NOT NULL,
  `sprite_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `id` (`id`),
  KEY `obj_type_id` (`obj_type_id`),
  KEY `image_id` (`sprite_id`),
  CONSTRAINT `FK_object_obj_type` FOREIGN KEY (`obj_type_id`) REFERENCES `obj_type` (`id`),
  CONSTRAINT `FK_object_sprite` FOREIGN KEY (`sprite_id`) REFERENCES `sprite` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.obj_data
CREATE TABLE IF NOT EXISTS `obj_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `value` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `obj_id` (`obj_id`),
  KEY `field_id` (`field_id`),
  CONSTRAINT `FK_obj_data_obj_field` FOREIGN KEY (`field_id`) REFERENCES `obj_field` (`id`),
  CONSTRAINT `FK_obj_data_object` FOREIGN KEY (`obj_id`) REFERENCES `object` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.obj_field
CREATE TABLE IF NOT EXISTS `obj_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Column 1` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.obj_type
CREATE TABLE IF NOT EXISTS `obj_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.quest
CREATE TABLE IF NOT EXISTS `quest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL,
  `description` varchar(256) NOT NULL,
  `quest_type_id` int(11) NOT NULL,
  `first_step_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  KEY `id` (`id`),
  KEY `quest_type_id` (`quest_type_id`),
  KEY `first_step_id` (`first_step_id`),
  CONSTRAINT `FK_quest_quest_step` FOREIGN KEY (`first_step_id`) REFERENCES `quest_step` (`id`),
  CONSTRAINT `FK_quest_quest_type` FOREIGN KEY (`quest_type_id`) REFERENCES `quest_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.quest_reward
CREATE TABLE IF NOT EXISTS `quest_reward` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quest_id` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `qty` int(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `quest_id` (`quest_id`),
  KEY `object_id` (`object_id`),
  CONSTRAINT `FK_quest_reward_quest` FOREIGN KEY (`quest_id`) REFERENCES `quest` (`id`),
  CONSTRAINT `FK_quest_reward_object` FOREIGN KEY (`object_id`) REFERENCES `object` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.quest_status
CREATE TABLE IF NOT EXISTS `quest_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.quest_step
CREATE TABLE IF NOT EXISTS `quest_step` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL,
  `goal_id` int(11) NOT NULL,
  `next_step_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `goal_id` (`goal_id`),
  KEY `next_step_id` (`next_step_id`),
  CONSTRAINT `FK_quest_step_goal` FOREIGN KEY (`goal_id`) REFERENCES `goal` (`id`),
  CONSTRAINT `FK_quest_step_quest_step` FOREIGN KEY (`next_step_id`) REFERENCES `quest_step` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.quest_type
CREATE TABLE IF NOT EXISTS `quest_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.sprite
CREATE TABLE IF NOT EXISTS `sprite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(16) NOT NULL,
  `image_id` int(1) NOT NULL,
  `sprite` varchar(8) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `id` (`id`),
  KEY `image_id` (`image_id`),
  CONSTRAINT `FK_sprite_image` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.tile
CREATE TABLE IF NOT EXISTS `tile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `tile_type` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `sheet_x_pos` int(3) NOT NULL,
  `sheet_y_pos` int(3) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `id` (`id`),
  KEY `image_id` (`image_id`),
  KEY `tile_type` (`tile_type`),
  CONSTRAINT `FK_tile_image` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`),
  CONSTRAINT `FK_tile_tile_type` FOREIGN KEY (`tile_type`) REFERENCES `tile_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.tile_type
CREATE TABLE IF NOT EXISTS `tile_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(16) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Data exporting was unselected.


-- Dumping structure for table mmorts.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(16) COLLATE latin1_general_ci NOT NULL,
  `password` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `active` varchar(1) COLLATE latin1_general_ci NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
