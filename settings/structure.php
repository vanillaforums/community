<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Mark O'Sullivan
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Mark O'Sullivan at mark [at] lussumo [dot] com
*/

// Use this file to construct tables and views necessary for your application.
// There are some examples below to get you started.

if (!isset($Drop))
   $Drop = FALSE;
   
if (!isset($Explicit))
   $Explicit = TRUE;
   
$SQL = $Database->SQL();
$Construct = $Database->Structure();

$Construct->Table('AddonType')
   ->Column('AddonTypeID', 'int', 4, FALSE, NULL, 'primary', TRUE)
   ->Column('Label', 'varchar', 30)
   ->Column('Visible', array('1','0'), '', FALSE, '1')
   ->Set($Explicit, $Drop);

if ($SQL->Select()->From('AddonType')->Get()->NumRows() == 0) {
   $SQL->Insert('AddonType', array('Label' => 'Plugin', 'Visible' => '1'));
   $SQL->Insert('AddonType', array('Label' => 'Theme', 'Visible' => '1'));
   $SQL->Insert('AddonType', array('Label' => 'Style', 'Visible' => '0'));
   $SQL->Insert('AddonType', array('Label' => 'Language', 'Visible' => '0'));
   $SQL->Insert('AddonType', array('Label' => 'Application', 'Visible' => '1'));
}

$Construct->Table('Addon')
   ->Column('AddonID', 'int', 11, FALSE, NULL, 'primary', TRUE)
   ->Column('CurrentAddonVersionID', 'int', 4, TRUE, NULL, 'key')
   ->Column('AddonTypeID', 'int', 4, FALSE, NULL, 'key')
   ->Column('InsertUserID', 'int', 10, FALSE, NULL, 'key')
   ->Column('UpdateUserID', 'int', 10, TRUE, NULL)
   ->Column('Name', 'varchar', 100)
   ->Column('Icon', 'varchar', 200, TRUE)
   ->Column('Description', 'text', '', TRUE)
   ->Column('Requirements', 'text', '', TRUE)
   ->Column('CountComments', 'int', 4, FALSE, '0')
   ->Column('CountDownloads', 'int', 4, FALSE, '0')
   ->Column('Visible', array('1', '0'), '', FALSE, '1')
   ->Column('Vanilla2', array('1', '0'), '', FALSE, '1')
   ->Column('DateInserted', 'datetime')
   ->Column('DateUpdated', 'datetime', '', TRUE)
   ->Set($Explicit, $Drop);

$Construct->Table('AddonComment')
   ->Column('AddonCommentID', 'int', 11, FALSE, NULL, 'primary', TRUE)
   ->Column('AddonID', 'int', 11, FALSE, NULL, 'key')
   ->Column('InsertUserID', 'int', 10, TRUE, NULL, 'key')
   ->Column('Body', 'text')
   ->Column('Format', 'varchar', 20, TRUE)
   ->Column('DateInserted', 'datetime')
   ->Set($Explicit, $Drop);

$Construct->Table('AddonVersion')
   ->Column('AddonVersionID', 'int', 11, FALSE, NULL, 'primary', TRUE)
   ->Column('AddonID', 'int', 11, FALSE, NULL, 'key')
   ->Column('File', 'varchar', 200, TRUE)
   ->Column('Version', 'varchar', 20, FALSE)
   ->Column('TestedWith', 'text')
   ->Column('InsertUserID', 'int', 10, TRUE, NULL, 'key')
   ->Column('DateInserted', 'datetime')
   ->Column('DateReviewed', 'datetime', '', TRUE)
   ->Set($Explicit, $Drop);

$Construct->Table('AddonPicture')
   ->Column('AddonPictureID', 'int', 11, FALSE, NULL, 'primary', TRUE)
   ->Column('AddonID', 'int', 11, FALSE, NULL, 'key')
   ->Column('File', 'varchar', 200, TRUE)
   ->Column('DateInserted', 'datetime')
   ->Set($Explicit, $Drop);

$Construct->Table('Download')
   ->Column('DownloadID', 'int', 11, FALSE, NULL, 'primary', TRUE)
   ->Column('AddonID', 'int', 11, TRUE, NULL, 'key')
   ->Column('DateInserted', 'datetime', '', TRUE)
   ->Column('RemoteIp', 'varchar', '50', TRUE)
   ->Set($Explicit, $Drop);

$Construct->Table('UpdateCheckSource')
   ->Column('SourceID', 'int', 11, FALSE, NULL, 'primary', TRUE)
   ->Column('Location', 'varchar', 255, TRUE)
   ->Column('DateInserted', 'datetime', '', TRUE)
   ->Column('RemoteIp', 'varchar', '50', TRUE)
   ->Set($Explicit, $Drop);

$Construct->Table('UpdateCheck')
   ->Column('UpdateCheckID', 'int', 11, FALSE, NULL, 'primary', TRUE)
   ->Column('SourceID', 'int', 11, TRUE, NULL, 'key')
   ->Column('CountUsers', 'int', 4, FALSE, '0')
   ->Column('CountDiscussions', 'int', 4, FALSE, '0')
   ->Column('CountComments', 'int', 4, FALSE, '0')
   ->Column('CountConversations', 'int', 4, FALSE, '0')
   ->Column('CountConversationMessages', 'int', 4, FALSE, '0')
   ->Column('DateInserted', 'datetime', '', TRUE)
   ->Column('RemoteIp', 'varchar', '50', TRUE)
   ->Set($Explicit, $Drop);

// Need to use this table instead of linking directly with the Addon table
// because we might not have all of the addons being checked for.
$Construct->Table('UpdateAddon')
   ->Column('UpdateAddonID', 'int', 11, FALSE, NULL, 'primary', TRUE)
   ->Column('AddonID', 'int', 11, TRUE, NULL, 'key')
   ->Column('Name', 'varchar', 255, TRUE)
   ->Column('Type', 'varchar', 255, TRUE)
   ->Column('Version', 'varchar', 255, TRUE)
   ->Set($Explicit, $Drop);
   
$Construct->Table('UpdateCheckAddon')
   ->Column('UpdateCheckID', 'int', 11, TRUE, NULL, 'key')
   ->Column('UpdateAddonID', 'int', 11, TRUE, NULL, 'key')
   ->Set($Explicit, $Drop);

// Insert some activity types
///  %1 = ActivityName
///  %2 = ActivityName Possessive
///  %3 = RegardingName
///  %4 = RegardingName Possessive
///  %5 = Link to RegardingName's Wall
///  %6 = his/her
///  %7 = he/she
///  %8 = RouteCode & Route

// X added an addon
if ($SQL->GetWhere('ActivityType', array('Name' => 'AddAddon'))->NumRows() == 0)
   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'Name' => 'AddAddon', 'FullHeadline' => '%1$s uploaded a new %8$s.', 'ProfileHeadline' => '%1$s uploaded a new %8$s.', 'RouteCode' => 'addon', 'Public' => '1'));
   
// X edited an addon
if ($SQL->GetWhere('ActivityType', array('Name' => 'EditAddon'))->NumRows() == 0)
   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'Name' => 'EditAddon', 'FullHeadline' => '%1$s edited an %8$s.', 'ProfileHeadline' => '%1$s edited an %8$s.', 'RouteCode' => 'addon', 'Public' => '1'));

// People's comments on addons
if ($SQL->GetWhere('ActivityType', array('Name' => 'AddonComment'))->NumRows() == 0)
   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'Name' => 'AddonComment', 'FullHeadline' => '%1$s commented on %4$s %8$s.', 'ProfileHeadline' => '%1$s commented on %4$s %8$s.', 'RouteCode' => 'addon', 'Notify' => '1', 'Public' => '1'));

// People mentioning others in addon comments
if ($SQL->GetWhere('ActivityType', array('Name' => 'AddonCommentMention'))->NumRows() == 0)
   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'Name' => 'AddonCommentMention', 'FullHeadline' => 'You mentioned %3$s in a %8$s.', 'ProfileHeadline' => '%1$s mentioned you in a %8$s.', 'RouteCode' => 'comment', 'Notify' => '1', 'Public' => '0'));
