<?php if (!defined('APPLICATION')) exit();

Gdn::structure()
    ->table('Newsletter')
    ->primaryKey('NewsletterID')
    ->column('Email', 'varchar(200)', null)
    ->column('Subscribe', 'tinyint(1)', '0')
    ->column('DateInserted', 'datetime')
    ->set();

Gdn::structure()
    ->table('User')
    ->column('Newsletter', 'tinyint(1)', '0')
    ->set();