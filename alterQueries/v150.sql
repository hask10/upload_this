-- 
ALTER TABLE `projects` ADD `custom_template_id` INT(11) NULL AFTER `template_id`;
-- 
ALTER TABLE `template_usages` ADD `custom_template_id` INT(11) NULL AFTER `template_id`;
-- 
ALTER TABLE `subscription_packages` ADD `allow_custom_templates` TINYINT(2) NOT NULL DEFAULT '0' AFTER `allow_text_to_speech`;
ALTER TABLE `subscription_packages` ADD `show_open_ai_model` TINYINT(2) NOT NULL DEFAULT '1' AFTER `allow_custom_templates`;