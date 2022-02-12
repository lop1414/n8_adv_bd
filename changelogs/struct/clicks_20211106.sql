ALTER TABLE `n8_adv_bd`.`clicks`
    MODIFY COLUMN `bd_vid` varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `link`;
