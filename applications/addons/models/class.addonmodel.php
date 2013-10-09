<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/

require_once PATH_APPLICATIONS.'/dashboard/models/class.updatemodel.php';

class AddonModel extends Gdn_Model {
   public static $Types = array(
          'plugin' => ADDON_TYPE_PLUGIN,
          'theme' => ADDON_TYPE_THEME,
          'locale' => ADDON_TYPE_LOCALE,
          'application' => ADDON_TYPE_APPLICATION,
          'core' => ADDON_TYPE_CORE
      );
   public static $TypesPlural = array(
          'plugins' => ADDON_TYPE_PLUGIN,
          'themes' => ADDON_TYPE_THEME,
          'locales' => ADDON_TYPE_LOCALE,
          'applications' => ADDON_TYPE_APPLICATION,
          'core' => ADDON_TYPE_CORE
      );

   protected $_AddonCache = array();

   public function __construct() {
      parent::__construct('Addon');
   }
   
   public function AddonQuery($VersionSlug = FALSE) {
      $this->SQL
         ->Select('a.*')
         ->Select('t.Label', '', 'Type')
         ->Select('v.AddonVersionID, v.File, v.Version, v.DateReviewed, v.TestedWith, v.MD5, v.FileSize')
         ->Select('v.DateInserted', '', 'DateUploaded')
         ->Select('iu.Name', '', 'InsertName')
         ->From('Addon a')
         ->Join('AddonType t', 'a.AddonTypeID = t.AddonTypeID')
         ->Join('User iu', 'a.InsertUserID = iu.UserID')
         ->Where('a.Visible', '1');

      if (!$VersionSlug) {
         // Join in the current addon version.
         $this->SQL->Join('AddonVersion v', 'a.CurrentAddonVersionID = v.AddonVersionID', 'left');
      } else {
         // Join in the version based on the slug.
         if (is_int($VersionSlug)) {
            $On = $this->SQL->ConditionExpr('v.AddonVersionID', $VersionSlug);
         } else {
            $On = 'v.Deleted = 0 and a.AddonID = v.AddonID and '.$this->SQL->ConditionExpr('v.Version', $VersionSlug);
         }

         $this->SQL->Join('AddonVersion v', $On, 'left');
      }
   }
   
   public static function JoinAddons(&$Data, $Field = 'AddonID', $Columns = array('Name')) {
      $Columns = array_merge(array('table' => 'Addon', 'column' => 'Addon'), $Columns);
      Gdn_DataSet::Join($Data, $Columns, array('unique' => TRUE));
   }

   public static function Slug($Addon, $IncludeVersion = TRUE) {
      if (GetValue('AddonKey', $Addon) && (GetValue('Version', $Addon) || !$IncludeVersion)) {
         $Key = GetValue('AddonKey', $Addon);
         $Type = GetValue('Type', $Addon);
         if (!$Type) {
            $Type = GetValue(GetValue('AddonTypeID', $Addon), array_flip(self::$Types));
         }
         
         //$Slug = strtolower(GetValue('AddonKey', $Data).'-'.GetValue('Type', $Data).'-'.GetValue('Version', $Data));
         $Slug = strtolower($Key).'-'.strtolower($Type);
         if ($IncludeVersion === TRUE)
            $Slug .= '-'.GetValue('Version', $Addon, '');
         elseif (is_string($IncludeVersion))
            $Slug .= '-'.$IncludeVersion;
         elseif (is_array($IncludeVersion))
            $Slug .= '-'.$IncludeVersion['Version'];
         return urlencode($Slug);
      } else {
         return GetValue('AddonID', $Addon).'-'.Gdn_Format::Url(GetValue('Name', $Addon));
      }
   }

   public function DeleteVersion($VersionID) {
      $this->SQL->Put('AddonVersion', array('Deleted' => 1), array('AddonVersionID' => $VersionID));
   }
   
   public function Get($Offset = '0', $Limit = '', $Wheres = '') {
      if ($Limit == '') 
         $Limit = Gdn::Config('Vanilla.Discussions.PerPage', 50);

      $Offset = !is_numeric($Offset) || $Offset < 0 ? 0 : $Offset;
      
      $this->AddonQuery();
      
      if (is_array($Wheres))
         $this->SQL->Where($Wheres);

      return $this->SQL
         ->Limit($Limit, $Offset)
         ->Get();
   }

   /*
    * @return Gdn_DataSet
    */
   public function GetWhere($Where = FALSE, $OrderFields = '', $OrderDirection = 'asc', $Limit = FALSE, $Offset = FALSE) {
      $this->AddonQuery();
      
      if ($Where !== FALSE)
         $this->SQL->Where($Where);

      if ($OrderFields != '')
         $this->SQL->OrderBy($OrderFields, $OrderDirection);

      if ($Limit !== FALSE) {
         if ($Offset == FALSE || $Offset < 0)
            $Offset = 0;

         $this->SQL->Limit($Limit, $Offset);
      }

      $Result = $this->SQL->Get();
      $this->SetCalculatedFields($Result);
      return $Result;
   }
   
   public function GetCount($Wheres = '') {
      if (!is_array($Wheres))
         $Wheres = array();
         
      $Wheres['a.Visible'] = '1';
      return $this->SQL
         ->Select('a.AddonID', 'count', 'CountAddons')
         ->From('Addon a')
         ->Join('AddonType t', 'a.AddonTypeID = t.AddonTypeID')
         ->Where($Wheres)
         ->Get()
         ->FirstRow()
         ->CountAddons;
   }

   /**
    * Get an addon by ID or key.
    *
    * @param int|array $AddonID The addon ID which can be one of the following:
    *  - int: The AddonID.
    *  - array: An array where the first element is the addon's key and the second element is the addon type id.
    * @param bool $GetVersions Whether or not to get an array of all of the addon's versions.
    * @return object The addon.
    */
   public function GetID($AddonID, $GetVersions = FALSE) {
      // Look for the addon in the cache.
      foreach ($this->_AddonCache as $CachedAddon) {
         if (is_array($AddonID) && $CachedAddon['Key'] == $AddonID[0] && $CachedAddon['Type'] == $AddonID[1]) {
            $Addon = $CachedAddon;
            break;
         } elseif (is_numeric($AddonID) && $CachedAddon['AddonID'] == $AddonID) {
            $Addon = $CachedAddon;
            break;
         }
      }

      if (isset($Addon)) {
         $Result = $Addon;
      } else {
         $this->AddonQuery(GetValue(2, $AddonID, FALSE));

         if (is_array($AddonID))
            $this->SQL->Where(array('a.AddonKey' => $AddonID[0], 'a.AddonTypeID' => $AddonID[1]));
         else
            $this->SQL->Where('a.AddonID', $AddonID);

         $Result = $this->SQL->Get()->FirstRow(DATASET_TYPE_ARRAY);
         if (!$Result)
            return FALSE;


         $this->SetCalculatedFields($Result);
         $this->_AddonCache[] = $Result;
      }

      if ($GetVersions && !isset($Result['Versions'])) {
         $Versions = $this->SQL->GetWhere('AddonVersion', array('AddonID' => GetValue('AddonID', $Result), 'Deleted' => 0))->ResultArray();
         usort($Versions, array($this, 'VersionCompare'));

         foreach ($Versions as $Index => &$Version) {
            $this->SetCalculatedFields($Version);
         }

         $Result['Versions'] = $Versions;
      }

      return $Result;
   }

   public function GetIDs($IDs) {
      $AddonTypeIDs = array();
      $AddonIDs = array();

      // Loop through all of the IDs and parse them out.
      foreach ($IDs as $ID) {
         $Parts = explode('-', $ID, 3);

         if (is_numeric($Parts[0])) {
            $AddonIDs[] = $Parts[0];
         } else {
            $Key = $Parts[0];
            $Type = GetValue(1, $Parts);
            if (isset(self::$Types[$Type])) {
               $AddonTypeIDs[self::$Types[$Type]][] = $Key;
            }
         }
      }
      $Result = array();

      // Get all of the Addons by ID.
      if (count($AddonIDs) > 0) {
         $this->AddonQuery();
         $Addons = $this->SQL->WhereIn('a.AddonID', $AddonIDs)->Get()->Result();
         $Result = array_merge($Result, $Addons);
      }

      // Get all of the Addons by type.
      foreach ($AddonTypeIDs as $TypeID => $Keys) {
         $this->AddonQuery();
         $Addons = $this->SQL
            ->Where('a.AddonTypeID', $TypeID)
            ->WhereIn('a.AddonKey', $Keys)
            ->Get()->Result();
         $Result = array_merge($Result, $Addons);
      }

      $this->SetCalculatedFields($Result);
      $DataSet = new Gdn_DataSet($Result);
      return $DataSet;
   }

   /**
    * Get an addon based on its slug in the following form:
    *  - AddonID[-AddonName]
    *  - AddonType-AddonKey[-Version]
    *
    * @param string|int $Slug The slug to lookup
    * @param bool $GetVersions Whether or not to add an array of versions to the result.
    * @return array
    */
   public function GetSlug($Slug, $GetVersions = FALSE) {
      if (is_numeric($Slug)) {
         $Addon = $this->GetID($Slug, $GetVersions);
      } else {
         // This is a string identifier for the addon.
         $Parts = explode('-', $Slug, 3);
         $Key = GetValue(0, $Parts);

         if (is_numeric($Key)) {
            $Addon = $this->GetID($Key, $GetVersions);
         } else {
            $Type = strtolower(GetValue(1, $Parts));
            $TypeID = GetValue($Type, self::$Types, 0);
            $Version = GetValue(2, $Parts);

            $Addon = $this->GetID(array($Key, $TypeID, $Version), $GetVersions);
         }
      }
      
      if (!$Addon)
         return FALSE;

      if ($GetVersions) {
         // Find the latest stable version.
            $MaxVersion = GetValueR('Versions.0', $Addon);
            foreach ($Addon['Versions'] as $Version) {
               if (AddonModel::IsReleaseVersion($Version['Version'])) {
                  $MaxVersion = $Version;
                  break;
               }
            }

            // Find the version we are looking at.
            foreach ($Addon['Versions'] as $Version) {
               $Slug2 = AddonModel::Slug($Addon, $Version);
               if ($Slug2 == $Slug) {
                  $ViewingVersion = $Version;
                  break;
               }
            }
            if (!isset($ViewingVersion))
               $ViewingVersion = $MaxVersion;

            $Addon['CurrentAddonVersionID'] = $MaxVersion['AddonVersionID'];
            $Addon = array_merge($Addon, $ViewingVersion);
            $Addon['Slug'] = AddonModel::Slug($Addon, $ViewingVersion);
      }

      return $Addon;
   }

   public function VersionCompare($A, $B) {
      return -version_compare(GetValue('Version', $A), GetValue('Version', $B));
   }

   public function GetVersion($VersionID) {
      $Result = $this->SQL
         ->Select('a.*')
         ->Select('v.AddonVersionID, v.Version, v.File, v.MD5, v.FileSize, v.Checked')
         ->From('Addon a')
         ->Join('AddonVersion v', 'a.AddonID = v.AddonID')
         ->Where('v.AddonVersionID', $VersionID)
         ->Get()->FirstRow(DATASET_TYPE_ARRAY);
      return $Result;
   }

   public function SetCalculatedFields(&$Data, $Unset = TRUE) {
      if (!$Data)
         return;

      if (is_a($Data, 'Gdn_DataSet')) {
         $this->SetCalculatedFields($Data->Result());
      } elseif (is_object($Data) || !isset($Data[0])) {
         $File = GetValue('File', $Data);
         SetValue('Url', $Data, Gdn_Upload::Url($File));
         
         $Icon = GetValue('Icon', $Data, NULL);
         if ($Icon !== NULL) {
            // Fix the icon path.
            if ($Icon && strpos($Icon, '/') == FALSE) {
               $Icon = 'ai'.$Icon;
               SetValue('Icon', $Data, $Icon);
            }

            if (empty($Icon)) {
               SetValue('IconUrl', $Data, 'foo');
            } else {
               SetValue('IconUrl', $Data, Gdn_Upload::Url($Icon));
            }
         }

         if (GetValue('AddonKey', $Data) && GetValue('Checked', $Data)) {
            $Slug = strtolower(GetValue('AddonKey', $Data).'-'.GetValue('Type', $Data).'-'.GetValue('Version', $Data));
            SetValue('Slug', $Data, $Slug);
         }

         // Set the requirements.
         if (GetValue('Checked', $Data)) {
            $Requirements = GetValue('Requirements', $Data);
            try {
               $Requirements = unserialize($Requirements);
               if (is_array($Requirements))
                  SetValue('Requirements', $Data, $Requirements);
            } catch (Exception $Ex) {
            }
         }

         if ($Unset) {
//            unset($Data['File']);
         }

      } else {
         foreach ($Data as &$Row) {
            $this->SetCalculatedFields($Row);
         }
      }
   }

   public static function IsReleaseVersion($VersionString) {
      return !preg_match('`[a-z]`i', $VersionString);
   }

   public function Save($Stub, $V1 = FALSE) {
      Trace('AddonModel->Save()');
      
      $Session = Gdn::Session();

      $this->DefineSchema();

      // Most of the values come from the file itself.
      if (isset($Stub['Path'])) {
         $Path = $Stub['Path'];
      } elseif (GetValue('Checked', $Stub)) {
         $Addon = $Stub;
      } elseif (isset($Stub['File'])) {
         $Path = CombinePaths(array(PATH_UPLOADS, $Stub['File']));
      } else {
         if (!$Session->CheckPermission('Addons.Addon.Manage') && isset($Stub['Filename'])) {
            // Only admins can modify plugin attributes without the file.
            $this->Validation->AddValidationResult('Filename', 'ValidateRequired');
            return FALSE;
         }
      }
      
      // Analyze and fix the file.
      if (!isset($Addon)) {
         if (isset($Path) && !$V1) {
            try {
               $Addon = UpdateModel::AnalyzeAddon($Path, FALSE);
            } catch (Exception $Ex) {
               $Addon = FALSE;
               $this->Validation->AddValidationResult('File', '@'.$Ex->getMessage());
            }
            if (!is_array($Addon)) {
               $this->Validation->AddValidationResult('File', 'Could not analyze the addon file.');
               return FALSE;
            }
            $Addon = array_merge($Stub, $Addon);
         } else {
            $Addon = $Stub;
            if (isset($Path)) {
               $Addon['MD5'] = md5_file($Path);
               $Addon['FileSize'] = filesize($Path);
            }
         }
      }

      // Get an existing addon.
      if (isset($Addon['AddonID']))
         $CurrentAddon = $this->GetID($Addon['AddonID'], TRUE);
      elseif (isset($Addon['AddonKey']) && isset($Addon['AddonTypeID']))
         $CurrentAddon = $this->GetID(array($Addon['AddonKey'], $Addon['AddonTypeID']), TRUE);
      else
         $CurrentAddon = FALSE;
      
      Trace($CurrentAddon, 'CurentAddon');

      $Insert = !$CurrentAddon;
      if ($Insert)
         $this->AddInsertFields ($Addon);

      $this->AddUpdateFields($Addon); // always add update fields

      if (!$this->Validate($Addon, $Insert)) {
         Trace('Addon did not validate');
         return FALSE;
      }

      // Search for the current version.
      $MaxVersion = FALSE;
      $CurrentVersion = FALSE;
      if ($CurrentAddon && isset($Addon['Version'])) {
         // Search for a current version.
         foreach ($CurrentAddon['Versions'] as $Index => $Version) {
            if (isset($Addon['AddonVersionID'])) {
               if ($Addon['AddonVersionID'] == $Version['AddonVersionID'])
                  $CurrentVersion = $Version;
            } elseif (version_compare($Addon['Version'], $Version['Version']) == 0) {
               $CurrentVersion = $Version;
            }

            // Only check for a current version if the version has been checked.
            if (!$Version['Checked'])
               continue;

            if (!$MaxVersion || version_compare($MaxVersion['Version'], $Version['Version'], '<')) {
               $MaxVersion = $Version;
            }
         }
      }

      // Save the addon.
      $Fields = $this->FilterSchema($Addon);
      if ($Insert) {
         $AddonID = $this->SQL->Insert($this->Name, $Fields);
         
         // Add the activity.
         $ActivityModel = new ActivityModel();
         $Activity = array(
             'ActivityType' => 'Addon',
             'ActivityUserID' => $Fields['InsertUserID'],
             'NotifyUserID' => ActivityModel::NOTIFY_PUBLIC,
             'HeadlineFormat' => '{ActivityUserID,user} added the <a href="{Url,html}">{Data.Name}</a> addon.',
             'Story' => Gdn_Format::Html($Fields['Description']),
             'Route' => '/addon/'.rawurlencode(self::Slug($Fields, FALSE)),
             'Data' => array('Name' => $Fields['Name'])
         );
         $ActivityModel->Save($Activity);
      } else {
         $AddonID = GetValue('AddonID', $CurrentAddon);

         // Only save the addon if it is the current version.
         if (!$MaxVersion || version_compare($Addon['Version'], $MaxVersion['Version'], '>=')) {
            Trace('Uploaded version is the most recent version.');
            $this->SQL->Put($this->Name, $Fields, array('AddonID' => $AddonID));
         } else {
            $this->SQL->Reset();
         }
      }

      // Save the version.
      if ($AddonID && isset($Path) || isset($Addon['File'])) {
         Trace('Saving addon version');
         $Addon['AddonID'] = $AddonID;
         
         if (isset($Path)) {
            if (!StringBeginsWith($Path, PATH_UPLOADS.'/addons/')) {
               // The addon must be copied into the uploads folder.
               $NewPath = PATH_UPLOADS.'/addons/'.basename($Path);
               //rename($Path, $NewPath);
               $Path = $NewPath;
               $this->_AddonCache = array();
            }
            $File = substr($Path, strlen(PATH_UPLOADS.'/'));
            $Addon['File'] = $File;
         }

         if ($CurrentVersion) {
            $Addon['AddonVersionID'] = GetValue('AddonVersionID', $CurrentVersion);
         }

         // Insert or update the version.
         $VersionModel = new Gdn_Model('AddonVersion');
         $AddonVersionID = $VersionModel->Save($Addon);
         $this->Validation->AddValidationResult($VersionModel->ValidationResults());

         if (!$AddonVersionID) {
            return FALSE;
         }

         // Update the current version in the addon.
         if (!$MaxVersion || version_compare($CurrentAddon['Version'], $Addon['Version'], '<')) {
            $this->SQL->Put($this->Name,
               array('CurrentAddonVersionID' => $AddonVersionID),
               array('AddonID' => $AddonID));
         }
      }
      $this->_AddonCache = array();

      return $AddonID;
   }
   
   public function SaveBak($FormPostValues, $FileName = '') {
      $Session = Gdn::Session();
      
      // Define the primary key in this model's table.
      $this->DefineSchema();

      if (array_key_exists('AddonKey', $FormPostValues))
         $this->Validation->ApplyRule('AddonKey', 'Required');
      
      // Add & apply any extra validation rules:
      if (array_key_exists('Description', $FormPostValues))
         $this->Validation->ApplyRule('Description', 'Required');

      if (array_key_exists('Version', $FormPostValues))
         $this->Validation->ApplyRule('Version', 'Required');
/*
      if (array_key_exists('TestedWith', $FormPostValues))
         $this->Validation->ApplyRule('TestedWith', 'Required');
*/      
      // Get the ID from the form so we know if we are inserting or updating.
      $AddonID = ArrayValue('AddonID', $FormPostValues, '');
      $Insert = $AddonID == '' ? TRUE : FALSE;
      
      if ($Insert) {
         if(!array_key_exists('Vanilla2', $FormPostValues))
            $FormPostValues['Vanilla2'] = '0';
         
         unset($FormPostValues['AddonID']);
         $this->AddInsertFields($FormPostValues);
      } else if (!array_key_exists('Vanilla2', $FormPostValues)) {
         $Tmp = $this->GetID($AddonID);
         $FormPostValues['Vanilla2'] = $Tmp->Vanilla2 ? '1' : '0';
      }
      $this->AddUpdateFields($FormPostValues);
      // Validate the form posted values
      if ($this->Validate($FormPostValues, $Insert)) {
         $Fields = $this->Validation->SchemaValidationFields(); // All fields on the form that relate to the schema
         $AddonID = intval(ArrayValue('AddonID', $Fields, 0));
         $Fields = RemoveKeyFromArray($Fields, 'AddonID'); // Remove the primary key from the fields for saving
         $Addon = FALSE;
         $Activity = 'EditAddon';
         if ($AddonID > 0) {
            $this->SQL->Put($this->Name, $Fields, array($this->PrimaryKey => $AddonID));
         } else {
            $AddonID = $this->SQL->Insert($this->Name, $Fields);
            $Activity = 'AddAddon';
         }
         // Save the version
         if ($AddonID > 0 && $FileName != '') {
            // Save the addon file & version
            $AddonVersionModel = new Gdn_Model('AddonVersion');
            $AddonVersionID = $AddonVersionModel->Save(array(
               'AddonID' => $AddonID,
               'File' => $FileName,
               'Version' => ArrayValue('Version', $FormPostValues, ''),
               'TestedWith' => ArrayValue('TestedWith', $FormPostValues, 'Empty'),
               'DateInserted' => Gdn_Format::ToDateTime()
            ));
            // Mark the new addon file & version as the current version
            $this->SQL->Put($this->Name, array('CurrentAddonVersionID' => $AddonVersionID), array($this->PrimaryKey => $AddonID));
         }
         
         if ($AddonID > 0) {
            $Addon = $this->GetID($AddonID);

            // Record Activity
            AddActivity(
               $Session->UserID,
               $Activity,
               '',
               '',
               '/addon/'.$AddonID.'/'.Gdn_Format::Url($Addon['Name'])
            );
         }
      }
      if (!is_numeric($AddonID))
         $AddonID = FALSE;
         
      return count($this->ValidationResults()) > 0 ? FALSE : $AddonID;
   }
   
   public function SetProperty($AddonID, $Property, $ForceValue = FALSE) {
      if ($ForceValue !== FALSE) {
         $Value = $ForceValue;
      } else {
         $Value = '1';
         $Addon = $this->GetID($AddonID);
         $Value = ($Addon[$Property] == '1' ? '0' : '1');
      }
      $this->SQL
         ->Update('Addon')
         ->Set($Property, $Value)
         ->Where('AddonID', $AddonID)
         ->Put();
      return $Value;
   }

   public function Validate($Post, $Insert) {
      $this->Validation->AddRule('AddonKey', 'function:ValidateAddonKey');

      if (GetValue('Checked', $Post) && ($Insert || isset($Post['AddonKey']))) {
         $this->Validation->ApplyRule('AddonKey', 'Required');
         $this->Validation->ApplyRule('AddonKey', 'AddonKey');
      }

      if ($Insert || isset($Post['Description']))
         $this->Validation->ApplyRule('Description', 'Required');

      if ($Insert || isset($Post['Version'])) {
         $this->Validation->ApplyRule('Version', 'Required');
         $this->Validation->ApplyRule('Version', 'Version');
      }

      parent::Validate($Post, $Insert);

      // Validate against an existing addon.
      if ($AddonID = GetValue('AddonID', $Post)) {
         $CurrentAddon = $this->GetID($AddonID, TRUE);
         if ($CurrentAddon) {
            if (GetValue('AddonKey', $CurrentAddon) && isset($Post['AddonKey']) && GetValue('AddonKey', $Post) != GetValue('AddonKey', $CurrentAddon))
               $this->Validation->AddValidationResult('AddonKey', '@'.sprintf(T('The addon\'s key cannot be changed. The uploaded file has a key of <b>%s</b>, but it must be <b>%s</b>.'), GetValue('AddonKey', $Post), GetValue('AddonKey', $CurrentAddon)));
            else {
               // Make sure this version doesn't match.
               foreach ($CurrentAddon['Versions'] as $Version) {
                  if ($Version['Deleted'])
                     continue;

                  if (version_compare(GetValue('Version', $Version), GetValue('Version', $Post)) == 0) {
                     // This version matches a previous version.
                     if (GetValue('Checked', $Version) && GetValue('MD5', $Version) != GetValue('MD5', $Post))
                        $this->Validation->AddValidationResult('Version', '@'.sprintf(T('Version %s of this addon already exists.'), GetValue('Version', $Version)));
                  }
               }
            }
         }
      }

      // Make sure there isn't another addon with the same key as this one.
      if (ValidateRequired(GetValue('AddonKey', $Post))) {
         $CountSame = $this->GetCount(array('AddonKey' => $Post['AddonKey'], 'AddonID <>' => GetValue('AddonID', $Post), 'a.AddonTypeID' => GetValue('AddonTypeID', $Post)));
         if ($CountSame > 0) {
            $this->Validation->AddValidationResult('AddonKey', '@'.sprintf(T('The addon key %s is already taken.'), $Post['AddonKey']));
         }
      }

      return count($this->Validation->Results()) == 0;
   }
      
   public function Delete($AddonID) {
      $this->SetProperty($AddonID, 'Visible', '0');
   }

   public function UpdateCurrentVersion($AddonID) {
      $Addon = $this->GetID($AddonID, TRUE);

      $MaxVersion = FALSE;
      foreach ($Addon['Versions'] as $Version) {
         if (!$Version['Checked'] || $Version['Deleted'])
            continue;
         if (!$MaxVersion || version_compare($Version['Version'], $MaxVersion['Version'], '>')) {
            $MaxVersion = $Version;
         }
      }
      if ($MaxVersion) {
         $this->SQL->History()->Put('Addon', array('CurrentAddonVersionID' => $MaxVersion->Version), array('AddonID' => $AddonID));
      }
   }
}

function ValidateAddonKey($Value) {
	if (is_numeric($Value))
      return FALSE;
   elseif (preg_match('`[-,;:/]`', $Value) || strpos($Value, '\\') !== FALSE)
      return FALSE;
   return TRUE;
}