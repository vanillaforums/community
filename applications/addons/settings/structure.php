<?php if (!defined('APPLICATION')) { exit(); }
/**
 *
 *
 * @copyright 2009-2015 Vanilla Forums Inc.
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
    ->set($Explicit, $Drop);

if (!$Description2Exists) {
    $Construct->query("update {$Px}Addon set Description2 = Description where Checked = 0");
}

$Construct->table('AddonVersion')
    ->primaryKey('AddonVersionID')
    ->column('AddonID', 'int', false, 'key')
    ->column('File', 'varchar(200)', true)
    ->column('Version', 'varchar(20)')
    ->column('TestedWith', 'text', null)
    ->column('FileSize', 'int', null)
    ->column('MD5', 'varchar(32)')
    ->column('Notes', 'text', null)
    ->column('Format', 'varchar(10)', 'Html')
    ->column('InsertUserID', 'int', false, 'key')
    ->column('DateInserted', 'datetime')
    ->column('DateReviewed', 'datetime', true)
    ->column('Checked', 'tinyint(1)', '0')
    ->column('Deleted', 'tinyint(1)', '0')
    ->set($Explicit, $Drop);

$Construct->table('AddonPicture')
    ->primaryKey('AddonPictureID')
    ->column('AddonID', 'int', false, 'key')
    ->column('File', 'varchar(200)')
    ->column('DateInserted', 'datetime')
    ->set($Explicit, $Drop);

$Construct->table('Download')
    ->primaryKey('DownloadID')
    ->column('AddonID', 'int', false, 'key')
    ->column('DateInserted', 'datetime')
    ->column('RemoteIp', 'varchar(50)', true)
    ->set($Explicit, $Drop);

$Construct->table('UpdateCheckSource')
    ->primaryKey('SourceID')
    ->column('Location', 'varchar(255)', true)
    ->column('DateInserted', 'datetime', true)
    ->column('RemoteIp', 'varchar(50)', true)
    ->set($Explicit, $Drop);

$Construct->table('UpdateCheck')
    ->primaryKey('UpdateCheckID')
    ->column('SourceID', 'int', false, 'key')
    ->column('CountUsers', 'int', '0')
    ->column('CountDiscussions', 'int', '0')
    ->column('CountComments', 'int', '0')
    ->column('CountConversations', 'int', '0')
    ->column('CountConversationMessages', 'int', '0')
    ->column('DateInserted', 'datetime')
    ->column('RemoteIp', 'varchar(50)', true)
    ->set($Explicit, $Drop);

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

if (isset($$PermissionTableExists) && $PermissionTableExists) {
    // Set the intial member permissions.
    $PermissionModel->save(array(
        'RoleID' => 8,
        'Addons.Addon.Add' => 1
        ));

    // Set the initial administrator permissions.
    $PermissionModel->save(array(
        'RoleID' => 16,
        'Addons.Addon.Add' => 1,
        'Addons.Addon.Manage' => 1,
        'Addons.Comments.Manage' => 1
        ));
}

$ActivityModel = new ActivityModel();
$ActivityModel->defineType('Addon');

// Add AddonID column to discussion table for allowing discussions on addons.
$Construct->table('Discussion')
    ->column('AddonID', 'int', null)
    ->set();