<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/

class AddonCommentModel extends Gdn_Model {
   public function __construct() {
      parent::__construct('AddonComment');
   }
   
   public function AddonCommentQuery() {
      $this->SQL->Select('c.*')
         ->Select('iu.Name', '', 'InsertName')
         ->Select('iu.Photo', '', 'InsertPhoto')
         ->From('AddonComment c')
         ->Join('User iu', 'c.InsertUserID = iu.UserID', 'left');
   }
   
   public function Get($AddonID, $Limit, $Offset = 0) {
      $this->AddonCommentQuery();
      $this->FireEvent('BeforeGet');
      return $this->SQL
         ->Where('c.AddonID', $AddonID)
         ->OrderBy('c.DateInserted', 'asc')
         ->Limit($Limit, $Offset)
         ->Get();
   }
   
   public function GetID($AddonCommentID) {
      $this->CommentQuery();
      return $this->SQL
         ->Where('c.AddonCommentID', $AddonCommentID)
         ->Get()
         ->FirstRow();
   }
   
   public function GetNew($AddonID, $LastCommentID) {
      $this->CommentQuery(); 
      return $this->SQL
         ->Where('c.AddonID', $AddonID)
         ->Where('c.AddonCommentID >', $LastCommentID)
         ->OrderBy('c.DateInserted', 'asc')
         ->Get();
   }
   
   /// <summary>
   /// Returns the offset of the specified comment in it's related discussion.
   /// </summary>
   /// <param name="CommentID" type="int">
   /// The comment id for which the offset is being defined.
   /// </param>
   public function GetOffset($AddonCommentID) {
      return $this->SQL
         ->Select('c2.AddonCommentID', 'count', 'CountComments')
         ->From('AddonComment c')
         ->Join('Addon a', 'c.AddonID = a.AddonID')
         ->Join('AddonComment c2', 'a.AddonID = c2.AddonID')
         ->Where('c2.AddonCommentID <=', $AddonCommentID)
         ->Where('c.AddonCommentID', $AddonCommentID)
         ->Get()
         ->FirstRow()
         ->CountComments;
   }
   
   public function Save($FormPostValues) {
      $Session = Gdn::Session();
      
      // Define the primary key in this model's table.
      $this->DefineSchema();
      
      // Add & apply any extra validation rules:      
      $this->Validation->ApplyRule('Body', 'Required');
      $MaxCommentLength = Gdn::Config('Vanilla.Comment.MaxLength');
      if (is_numeric($MaxCommentLength) && $MaxCommentLength > 0) {
         $this->Validation->SetSchemaProperty('Body', 'Length', $MaxCommentLength);
         $this->Validation->ApplyRule('Body', 'Length');
      }
      
      $AddonCommentID = ArrayValue('AddonCommentID', $FormPostValues);
      $AddonCommentID = is_numeric($AddonCommentID) && $AddonCommentID > 0 ? $AddonCommentID : FALSE;
      $Insert = $AddonCommentID === FALSE;
      if ($Insert)
         $this->AddInsertFields($FormPostValues);
      else
         $this->AddUpdateFields($FormPostValues);
      
      // Validate the form posted values
      if ($this->Validate($FormPostValues, $Insert)) {
         // If the post is new
         $Fields = $this->Validation->SchemaValidationFields();
         $Fields = RemoveKeyFromArray($Fields, $this->PrimaryKey);
         $AddonID = ArrayValue('AddonID', $Fields);
         if ($Insert === FALSE) {
            $this->SQL->Put($this->Name, $Fields, array('AddonCommentID' => $AddonCommentID));
         } else {
            // Make sure that the comments get formatted in the method defined by Garden
            $Fields['Format'] = Gdn::Config('Garden.InputFormatter', '');
            $AddonCommentID = $this->SQL->Insert($this->Name, $Fields);
            
            // Notify any users who were mentioned in the comment
            $Usernames = GetMentions($Fields['Body']);
            $UserModel = Gdn::UserModel();
            foreach ($Usernames as $Username) {
               $User = $UserModel->GetByUsername($Username);
               if ($User && $User->UserID != $Session->UserID) {
                  AddActivity(
                     $Session->UserID,
                     'AddonCommentMention',
                     '',
                     $User->UserID,
                     'addon/'.$AddonID.'/#Comment_'.$AddonCommentID
                  );
               }
            }
         }
         // Record user-comment activity
         if ($AddonID !== FALSE)
            $this->RecordActivity($AddonID, $Session->UserID, $AddonCommentID);
      }
      return $AddonCommentID;
   }
      
   public function RecordActivity($AddonID, $ActivityUserID, $AddonCommentID) {
      // Get the author of the discussion
      $AddonModel = new AddonModel();
      $Addon = $AddonModel->GetID($AddonID);
      if ($Addon->InsertUserID != $ActivityUserID) 
         AddActivity(
            $ActivityUserID,
            'AddonComment',
            '',
            $Addon->InsertUserID,
            'addon/'.$AddonID.'/'.Gdn_Format::Url($Addon->Name).'/#Comment_'.$AddonCommentID
         );
   }
   
   public function Delete($AddonCommentID) {
      $this->SQL->Delete('AddonComment', array('AddonCommentID' => $AddonCommentID));
      return TRUE;
   }   
}