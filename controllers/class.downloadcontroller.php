<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/

class DownloadController extends VFOrgController {
   
   public $Uses = array('Database', 'Form', 'UserModel');
   
   /**
    * "Sign up & Download or go to hosting" page.
    */
   public function Index() {
      include(CombinePaths(array(PATH_LIBRARY, 'vendors/recaptcha', 'functions.recaptchalib.php')));
      $this->AddJsFile('jquery.js');
      $this->AddJsFile('jquery.livequery.js');
      $this->AddJsFile('global.js');
      $this->AddJsFile('download.js');
      $this->Head->AddScript('http://vanillaforums.com/applications/vfcom/js/cufon-yui.js');
      $this->Head->AddScript('http://vanillaforums.com/applications/vfcom/js/archer.font.js');
      $this->Head->AddString("
<script type=\"text/javascript\">
   Cufon.replace('h1', { textShadow: '0px 1px 1px #ffffff;' });
</script>");
      
      // Create an account for the user if they fill out the form.
      if ($this->Form->IsPostBack() === TRUE) {
         $CreateAccount = $this->Form->GetFormValue('CreateAccount', '0');
         
         if ($CreateAccount == '0') {
            // If not creating an account, did they enter an email address to save to the Newsletter table.
            $Email = $this->Form->GetFormValue('Email');
            $Subscribe = $this->Form->GetFormValue('Newsletter', '0');
            if ($Email)
               $this->Database->SQL()->Insert('Newsletter', array(
                  'Email' => $Email,
                  'Subscribe' => $Subscribe,
                  'DateInserted' => Gdn_Format::ToDateTime()
               ));

            // ... and redirect them appropriately
            if ($this->_DeliveryType != DELIVERY_TYPE_ALL) {
               $this->RedirectUrl = Url('download/get/stable');
            } else {
               Redirect('download/get/stable');
            }
            
         } else {
            // If creating an account, do it
            // Add validation rules that are not enforced by the model
            $this->UserModel->DefineSchema();
            $this->UserModel->Validation->ApplyRule('Name', 'Username', 'Username can only contain letters, numbers, underscores, and must be between 3 and 20 characters long.');
            $this->UserModel->Validation->ApplyRule('TermsOfService', 'Required', 'You must agree to the terms of service.');
            $this->UserModel->Validation->ApplyRule('Password', 'Required');
            $this->UserModel->Validation->ApplyRule('Password', 'Match');
            
            if (!$this->UserModel->InsertForBasic($this->Form->FormValues())) {
               $this->Form->SetValidationResults($this->UserModel->ValidationResults());
            } else {
               // The user has been created successfully, so sign in now
               $Authenticator = Gdn::Authenticator()->AuthenticateWith('password');
               $Authenticator->FetchData($this->Form);
               $AuthUserID = $Authenticator->Authenticate();
               
               // ... and redirect them appropriately
               if ($this->_DeliveryType != DELIVERY_TYPE_ALL) {
                  $this->RedirectUrl = Url('download/get/stable');
               } else {
                  Redirect('download/get/stable');
               }
            }
         }
      }
      
      $this->Render();
   }
   
   public function Get($Version = '', $Serve = '0') {
      if (InArrayI($Version, array('nightly', 'unstable'))) {
         Redirect('http://github.com/vanillaforums/Garden/archives/unstable');
         return;
      }
      
      if ($Serve != '1') {
         $this->AddJsFile('jquery.js');
			$this->AddJsFile('get.js');      
      }
      
      // Serve the zip
		$AddonID = 465;

  		// Find the requested addon
      $AddonModel = new AddonModel();
		$this->Addon = $AddonModel->GetID($AddonID);
		if (!is_object($this->Addon)) {
			$this->Addon = new stdClass();
			$this->Addon->Name = 'Not Found';
			$this->Addon->Version = 'undefined';
			$this->Addon->File = '';
      } else if ($Serve == '1') {
         // Record this download
         $this->Database->SQL()->Insert('Download', array(
            'AddonID' => $this->Addon->AddonID,
            'DateInserted' => Gdn_Format::ToDateTime(),
            'RemoteIp' => @$_SERVER['REMOTE_ADDR']
         ));
         
         $AddonModel->SetProperty($AddonID, 'CountDownloads', $this->Addon->CountDownloads + 1);
         Gdn_FileSystem::ServeFile('uploads/'.$this->Addon->File, Gdn_Format::Url($this->Addon->Name.'-'.$this->Addon->Version));
      }
      
      $this->AddModule('DownloadHelpModule');      
      $this->Render();
   }   

   public function Nightly() {
      $this->Get('nightly');
   }   
}