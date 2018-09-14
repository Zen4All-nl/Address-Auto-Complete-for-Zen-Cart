INSERT INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('Street number minimum length', 'ENTRY_STREET_NO_MIN_LENGTH', '1', 'Minimum length of the street number', '2', '18', NULL, NOW(), NULL, NULL);

ALTER TABLE `address_book` ADD `entry_street_only` VARCHAR( 64 ) NOT NULL ,
ADD `entry_street_no` VARCHAR( 16 ) NOT NULL ,
ADD `entry_street_no_add` VARCHAR( 32 ) NOT NULL ;

ALTER TABLE `orders` ADD `delivery_street_only` VARCHAR( 64 ) NOT NULL AFTER `delivery_street_address` ,
ADD `delivery_street_no` VARCHAR( 16 ) NOT NULL AFTER `delivery_street_only` ,
ADD `delivery_street_no_add` VARCHAR( 32 ) NOT NULL AFTER `delivery_street_no` ;

ALTER TABLE `orders` ADD `billing_street_only` VARCHAR( 64 ) NOT NULL AFTER `billing_street_address` ,
ADD `billing_street_no` VARCHAR( 16 ) NOT NULL AFTER `billing_street_only` ,
ADD `billing_street_no_add` VARCHAR( 32 ) NOT NULL AFTER `billing_street_no` ;

ALTER TABLE `orders` ADD `customers_street_only` VARCHAR( 64 ) NOT NULL AFTER `customers_street_address` ,
ADD `customers_street_no` VARCHAR( 16 ) NOT NULL AFTER `customers_street_only` ,
ADD `customers_street_no_add` VARCHAR( 32 ) NOT NULL AFTER `customers_street_no` ;