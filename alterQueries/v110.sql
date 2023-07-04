-- affiliate 
ALTER TABLE `users` ADD `referral_code` VARCHAR(255) NULL AFTER `user_balance`, ADD `num_of_clicks` INT(11) NOT NULL DEFAULT '0' AFTER `referral_code`, ADD `referred_by` INT(11) NULL AFTER `num_of_clicks`, ADD `is_commission_calculated` TINYINT(2) NOT NULL DEFAULT '1' AFTER `referred_by`;
