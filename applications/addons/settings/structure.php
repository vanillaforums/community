<?php if (!defined('APPLICATION')) { exit(); }
/**
 *
 *
 * @copyright 2009-2016 Vanilla Forums Inc.
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU GPL v2
 * @package Addons
 * @since 2.0
 */

$Drop = false;
$Explicit = false;

$SQL = Gdn::sql();
$Construct = Gdn::structure();
$Px = $Construct->databasePrefix();

$Construct->table('AddonType')
    ->primaryKey('AddonTypeID')
    ->column('Label', 'varchar(50)')
    ->column('Visible', 'tinyint(1)', '1')
    ->set($Explicit, $Drop);

$SQL->replace('AddonType', array('Label' => 'Plugin', 'Visible' => '1'), array('AddonTypeID' => 1), true);
$SQL->replace('AddonType', array('Label' => 'Theme', 'Visible' => '1'), array('AddonTypeID' => 2), true);
$SQL->replace('AddonType', array('Label' => 'Style', 'Visible' => '0'), array('AddonTypeID' => 3), true);
$SQL->replace('AddonType', array('Label' => 'Locale', 'Visible' => '1'), array('AddonTypeID' => 4), true);
$SQL->replace('AddonType', array('Label' => 'Application', 'Visible' => '1'), array('AddonTypeID' => 5), true);
$SQL->replace('AddonType', array('Label' => 'Core', 'Visible' => '1'), array('AddonTypeID' => 10), true);

$Construct->table('Addon');
$Description2Exists = $Construct->columnExists('Description2');

$Construct->primaryKey('AddonID')
    ->column('CurrentAddonVersionID', 'int', true, 'key')
    ->column('AddonKey', 'varchar(50)', null, 'index')
    ->column('AddonTypeID', 'int', false, 'key')
    ->column('InsertUserID', 'int', false, 'key')
    ->column('UpdateUserID', 'int', true)
    ->column('Name', 'varchar(100)')
    ->column('Icon', 'varchar(200)', true)
    ->column('Description', 'text', true)
    ->column('Description2', 'text', null)
    ->column('Requirements', 'text', true)
    ->column('CountComments', 'int', '0')
    ->column('CountDownloads', 'int', '0')
    ->column('Visible', 'tinyint(1)', '1')
    ->column('Vanilla2', 'tinyint(1)', '1')
    ->column('DateInserted', 'datetime')
    ->column('DateUpdated', 'datetime', true)
    ->column('Checked', 'tinyint(1)', '0')
    ->column('Official', 'tinyint(1)', '0')
    ->column('License', 'varchar(100)')
    ->column('GitHub', 'varchar(100)', null)
    ->set($Explicit, $Drop);

if (!$Description2Exists) {
    $Construct->query("update {$Px}Addon set Description2 = Description where Checked = 0");
}

/*
$Construct->Table('AddonComment')
    ->PrimaryKey('AddonCommentID')
    ->Column('AddonID', 'int', FALSE, 'key')
    ->Column('InsertUserID', 'int', FALSE, 'key')
    ->Column('Body', 'text')
    ->Column('Format', 'varchar(20)', TRUE)
    ->Column('DateInserted', 'datetime')
    ->Set($Explicit, $Drop);
*/

$Construct->Table('AddonVersion')
    ->PrimaryKey('AddonVersionID')
    ->Column('AddonID', 'int', FALSE, 'key')
    ->Column('File', 'varchar(200)', TRUE)
    ->Column('Version', 'varchar(20)')
    ->Column('TestedWith', 'text', NULL)
    ->Column('FileSize', 'int', NULL)
    ->Column('MD5', 'varchar(32)')
    ->Column('Notes', 'text', NULL)
    ->Column('Format', 'varchar(10)', 'Html')
    ->Column('InsertUserID', 'int', FALSE, 'key')
    ->Column('DateInserted', 'datetime')
    ->Column('DateReviewed', 'datetime', TRUE)
    ->Column('Checked', 'tinyint(1)', '0')
    ->Column('Deleted', 'tinyint(1)', '0')
    ->Set($Explicit, $Drop);

$Construct->Table('AddonPicture')
    ->PrimaryKey('AddonPictureID')
    ->Column('AddonID', 'int', FALSE, 'key')
    ->Column('File', 'varchar(200)')
    ->Column('DateInserted', 'datetime')
    ->Set($Explicit, $Drop);

$Construct->Table('AddonConfidence')
   ->PrimaryKey('AddonConfidenceID')
   ->Column('AddonVersionID', 'int', FALSE, 'key')
   ->Column('CoreVersionID', 'int', FALSE, 'key')
   ->Column('UserID', 'int', FALSE, 'key')
   ->Column('Confidence', 'int', NULL)
   ->Set($Explicit, $Drop);

$Construct->Table('Download')
    ->PrimaryKey('DownloadID')
    ->Column('AddonID', 'int', FALSE, 'key')
    ->Column('DateInserted', 'datetime')
    ->Column('RemoteIp', 'varchar(50)', TRUE)
    ->Set($Explicit, $Drop);

$Construct->Table('UpdateCheckSource')
    ->PrimaryKey('SourceID')
    ->Column('Location', 'varchar(255)', TRUE)
    ->Column('DateInserted', 'datetime', TRUE)
    ->Column('RemoteIp', 'varchar(50)', TRUE)
    ->Set($Explicit, $Drop);

$Construct->Table('UpdateCheck')
    ->PrimaryKey('UpdateCheckID')
    ->Column('SourceID', 'int', FALSE, 'key')
    ->Column('CountUsers', 'int', '0')
    ->Column('CountDiscussions', 'int', '0')
    ->Column('CountComments', 'int', '0')
    ->Column('CountConversations', 'int', '0')
    ->Column('CountConversationMessages', 'int', '0')
    ->Column('DateInserted', 'datetime')
    ->Column('RemoteIp', 'varchar(50)', TRUE)
    ->Set($Explicit, $Drop);

// Need to use this table instead of linking directly with the Addon table
// because we might not have all of the addons being checked for.
$Construct->table('UpdateAddon')
    ->primaryKey('UpdateAddonID')
    ->column('AddonID', 'int', false, 'key')
    ->column('Name', 'varchar(255)', true)
    ->column('Type', 'varchar(255)', true)
    ->column('Version', 'varchar(255)', true)
    ->set($Explicit, $Drop);

$Construct->table('UpdateCheckAddon')
    ->column('UpdateCheckID', 'int', false, 'key')
    ->column('UpdateAddonID', 'int', false, 'key')
    ->set($Explicit, $Drop);

$PermissionModel = Gdn::permissionModel();
$PermissionModel->Database = Gdn::database();
$PermissionModel->SQL = $SQL;

// Define some global addon permissions.
$PermissionModel->define(array(
    'Addons.Addon.Add',
    'Addons.Addon.Manage',
    'Addons.Comments.Manage'
    ));

$ActivityModel = new ActivityModel();
$ActivityModel->defineType('Addon');

// Add AddonID column to discussion table for allowing discussions on addons.
$Construct->table('Discussion')
    ->column('AddonID', 'int', null)
    ->set();