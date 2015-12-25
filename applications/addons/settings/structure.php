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
$Explicit = true;

$SQL = Gdn::SQL();
$Construct = Gdn::Structure();
$Px = $Construct->DatabasePrefix();

$Construct->Table('AddonType')
    ->PrimaryKey('AddonTypeID')
    ->Column('Label', 'varchar(50)')
    ->Column('Visible', 'tinyint(1)', '1')
    ->Set($Explicit, $Drop);

$SQL->Replace('AddonType', array('Label' => 'Plugin', 'Visible' => '1'), array('AddonTypeID' => 1), true);
$SQL->Replace('AddonType', array('Label' => 'Theme', 'Visible' => '1'), array('AddonTypeID' => 2), true);
$SQL->Replace('AddonType', array('Label' => 'Style', 'Visible' => '0'), array('AddonTypeID' => 3), true);
$SQL->Replace('AddonType', array('Label' => 'Locale', 'Visible' => '1'), array('AddonTypeID' => 4), true);
$SQL->Replace('AddonType', array('Label' => 'Application', 'Visible' => '1'), array('AddonTypeID' => 5), true);
$SQL->Replace('AddonType', array('Label' => 'Core', 'Visible' => '1'), array('AddonTypeID' => 10), true);

$Construct->Table('Addon');
$Description2Exists = $Construct->ColumnExists('Description2');

$Construct->PrimaryKey('AddonID')
    ->Column('CurrentAddonVersionID', 'int', true, 'key')
    ->Column('AddonKey', 'varchar(50)', null, 'index')
    ->Column('AddonTypeID', 'int', false, 'key')
    ->Column('InsertUserID', 'int', false, 'key')
    ->Column('UpdateUserID', 'int', true)
    ->Column('Name', 'varchar(100)')
    ->Column('Icon', 'varchar(200)', true)
    ->Column('Description', 'text', true)
    ->Column('Description2', 'text', null)
    ->Column('Requirements', 'text', true)
    ->Column('CountComments', 'int', '0')
    ->Column('CountDownloads', 'int', '0')
    ->Column('Visible', 'tinyint(1)', '1')
    ->Column('Vanilla2', 'tinyint(1)', '1')
    ->Column('DateInserted', 'datetime')
    ->Column('DateUpdated', 'datetime', true)
    ->Column('Checked', 'tinyint(1)', '0')
    ->Set($Explicit, $Drop);

if (!$Description2Exists) {
    $Construct->Query("update {$Px}Addon set Description2 = Description where Checked = 0");
}

$Construct->Table('AddonVersion')
    ->PrimaryKey('AddonVersionID')
    ->Column('AddonID', 'int', false, 'key')
    ->Column('File', 'varchar(200)', true)
    ->Column('Version', 'varchar(20)')
    ->Column('TestedWith', 'text', null)
    ->Column('FileSize', 'int', null)
    ->Column('MD5', 'varchar(32)')
    ->Column('Notes', 'text', null)
    ->Column('Format', 'varchar(10)', 'Html')
    ->Column('InsertUserID', 'int', false, 'key')
    ->Column('DateInserted', 'datetime')
    ->Column('DateReviewed', 'datetime', true)
    ->Column('Checked', 'tinyint(1)', '0')
    ->Column('Deleted', 'tinyint(1)', '0')
    ->Set($Explicit, $Drop);

$Construct->Table('AddonPicture')
    ->PrimaryKey('AddonPictureID')
    ->Column('AddonID', 'int', false, 'key')
    ->Column('File', 'varchar(200)')
    ->Column('DateInserted', 'datetime')
    ->Set($Explicit, $Drop);

$Construct->Table('Download')
    ->PrimaryKey('DownloadID')
    ->Column('AddonID', 'int', false, 'key')
    ->Column('DateInserted', 'datetime')
    ->Column('RemoteIp', 'varchar(50)', true)
    ->Set($Explicit, $Drop);

$Construct->Table('UpdateCheckSource')
    ->PrimaryKey('SourceID')
    ->Column('Location', 'varchar(255)', true)
    ->Column('DateInserted', 'datetime', true)
    ->Column('RemoteIp', 'varchar(50)', true)
    ->Set($Explicit, $Drop);

$Construct->Table('UpdateCheck')
    ->PrimaryKey('UpdateCheckID')
    ->Column('SourceID', 'int', false, 'key')
    ->Column('CountUsers', 'int', '0')
    ->Column('CountDiscussions', 'int', '0')
    ->Column('CountComments', 'int', '0')
    ->Column('CountConversations', 'int', '0')
    ->Column('CountConversationMessages', 'int', '0')
    ->Column('DateInserted', 'datetime')
    ->Column('RemoteIp', 'varchar(50)', true)
    ->Set($Explicit, $Drop);

// Need to use this table instead of linking directly with the Addon table
// because we might not have all of the addons being checked for.
$Construct->Table('UpdateAddon')
    ->PrimaryKey('UpdateAddonID')
    ->Column('AddonID', 'int', false, 'key')
    ->Column('Name', 'varchar(255)', true)
    ->Column('Type', 'varchar(255)', true)
    ->Column('Version', 'varchar(255)', true)
    ->Set($Explicit, $Drop);

$Construct->Table('UpdateCheckAddon')
    ->Column('UpdateCheckID', 'int', false, 'key')
    ->Column('UpdateAddonID', 'int', false, 'key')
    ->Set($Explicit, $Drop);

$PermissionModel = Gdn::PermissionModel();
$PermissionModel->Database = Gdn::database();
$PermissionModel->SQL = $SQL;

// Define some global addon permissions.
$PermissionModel->Define(array(
    'Addons.Addon.Add',
    'Addons.Addon.Manage',
    'Addons.Comments.Manage'
    ));

if (isset($$PermissionTableExists) && $PermissionTableExists) {
    // Set the intial member permissions.
    $PermissionModel->Save(array(
        'RoleID' => 8,
        'Addons.Addon.Add' => 1
        ));

    // Set the initial administrator permissions.
    $PermissionModel->Save(array(
        'RoleID' => 16,
        'Addons.Addon.Add' => 1,
        'Addons.Addon.Manage' => 1,
        'Addons.Comments.Manage' => 1
        ));
}

$ActivityModel = new ActivityModel();
$ActivityModel->DefineType('Addon');

/*
    Apr 26th, 2010
    Changed all "enum" fields representing "bool" (0 or 1) to be tinyint.
    For some reason mysql makes 0's "2" during this change. Change them back to "0".
*/
if (!$Construct->CaptureOnly) {
    $SQL->Query("update GDN_AddonType set Visible = '0' where Visible = '2'");

    $SQL->Query("update GDN_Addon set Visible = '0' where Visible = '2'");
    $SQL->Query("update GDN_Addon set Vanilla2 = '0' where Vanilla2 = '2'");

    $SQL->Query("update GDN_UserLanguage set Owner = '0' where Owner = '2'");
}


// Add AddonID column to discussion table for allowing discussions on addons.
$Construct->Table('Discussion')
    ->Column('AddonID', 'int', null)
    ->Set();