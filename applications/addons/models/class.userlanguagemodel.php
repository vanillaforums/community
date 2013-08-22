<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/

class UserLanguageModel extends Gdn_Model {
   public function __construct() {
      parent::__construct('UserLanguage');
   }
   
   public function UserLanguageQuery() {
      $this->SQL
         ->Select('ul.*,l.Name,l.Code')
         ->Select('ul.UserID', '', 'InsertUserID')
         ->Select('iu.Name', '', 'InsertName')
         ->From('UserLanguage ul')
         ->Join('Language l', 'ul.LanguageID = l.LanguageID')
         ->Join('User iu', 'ul.UserID = iu.UserID')
         ->Where('ul.UserLanguageID <>', '1');
   }
   
   public function Get($Where = FALSE, $Limit = FALSE, $Offset = FALSE) {
      $this->UserLanguageQuery();
      
      if ($Where !== FALSE)
         $this->SQL->Where($Where);

      if ($Limit !== FALSE) {
         if ($Offset == FALSE || $Offset < 0)
            $Offset = 0;

         $this->SQL->Limit($Limit, $Offset);
      }

      return $this->SQL->Get();
   }
   
   public function GetCount($Wheres = '') {
      if (!is_array($Wheres))
         $Wheres = array();
         
      return $this->SQL
         ->Select('ul.UserLanguageID', 'count', 'CountUserLanguages')
         ->From('UserLanguage ul')
         ->Where($Wheres)
         ->Get()
         ->FirstRow()
         ->CountUserLanguages;
   }

   public function GetID($UserLanguageID, $Wheres = '') {
      $this->UserLanguageQuery();
      if (is_array($Wheres))
         $this->SQL->Where($Wheres);

      return $this->SQL
         ->Where('ul.UserLanguageID', $UserLanguageID)
         ->Get()
         ->FirstRow();
   }
   
   public function Save($FormPostValues) {
      $Session = Gdn::Session();
      $FormPostValues['UserID'] = $Session->UserID;
      
      // Define the primary key in this model's table.
      $this->DefineSchema();
      
      // Get the ID from the form so we know if we are inserting or updating.
      $UserLanguageID = ArrayValue('UserLanguageID', $FormPostValues, '');
      $Insert = $UserLanguageID == '' ? TRUE : FALSE;
      
      if ($Insert) {
         unset($FormPostValues['UserLanguageID']);
         $this->AddInsertFields($FormPostValues);
      } else {
         $this->AddUpdateFields($FormPostValues);
      }
      // Validate the form posted values
      if ($this->Validate($FormPostValues, $Insert)) {
         $Fields = $this->Validation->SchemaValidationFields(); // All fields on the form that relate to the schema
         $AddonID = intval(ArrayValue('UserLanguageID', $Fields, 0));
         $Fields = RemoveKeyFromArray($Fields, 'UserLanguageID'); // Remove the primary key from the fields for saving
         $UserLanguage = FALSE;
         $Activity = 'EditUserLanguage';
         if ($UserLanguageID > 0) {
            $this->SQL->Put($this->Name, $Fields, array($this->PrimaryKey => $UserLanguageID));
         } else {
            $UserLanguageID = $this->SQL->Insert($this->Name, $Fields);
            $Activity = 'AddUserLanguage';
         }
         
         if ($UserLanguageID > 0) {
            $UserLanguage = $this->GetID($UserLanguageID);

            // Record Activity
            AddActivity(
               $Session->UserID,
               $Activity,
               '',
               '',
               '/translation/'.$UserLanguageID.'/'
            );
         }
      }
      if (!is_numeric($UserLanguageID))
         $UserLanguageID = FALSE;
         
      return count($this->ValidationResults()) > 0 ? FALSE : $UserLanguageID;
   }
   
   public function SetProperty($UserLanguageID, $Property, $Value) {
      $Operator = FALSE;
      if (strlen($Value) > 1) {
         $Operator = substr($Value, 0, 1);
         if (in_array($Operator, array('+', '-')))
            $Value = substr($Value, 1);
         else
            $Operator = FALSE;
      }
      $this->SQL->Update('Addon');
      if ($Operator !== FALSE)
         $this->SQL->Set($Property, $Property . ' ' . $Operator . ' ' . $Value);
      else
         $this->SQL->Set($Property, $Value);
      
      $this->SQL->Where('UserLanguageID', $UserLanguageID)->Put();
   }
}