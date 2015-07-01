<?php if (!defined('APPLICATION')) exit();

$Database->Structure()->Table('Newsletter')
    ->PrimaryKey('NewsletterID')
    ->Column('Email', 'varchar(200)', NULL)
    ->Column('Subscribe', 'tinyint(1)', '0')
    ->Column('DateInserted', 'datetime')
    ->Set(0, 0);

$Database->Structure()->Table('User')
    ->Column('Newsletter', 'tinyint(1)', '0')
    ->Set(0, 0);