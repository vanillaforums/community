<?php
/**
 *
 *
 * @copyright 2009-2015 Vanilla Forums Inc.
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU GPL v2
 * @package Addons
 * @since 2.0
 */

/**
 * Class DownloadController
 */
class DownloadController extends VFOrgController {

    public $Uses = array('Database', 'Form', 'UserModel');

    /**
     * "Sign up & Download or go to hosting" page.
     */
    public function Index() {
        include(CombinePaths(array(PATH_LIBRARY, 'vendors/recaptcha', 'functions.recaptchalib.php')));
        $this->AddJsFile('jquery.js');
        $this->AddJsFile('global.js');
        $this->AddJsFile('download.js');

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
                    $this->RedirectUrl = Url('/get/vanilla-core');
                } else {
                    Redirect('get/vanilla-core');
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
                        $this->RedirectUrl = Url('/get/vanilla-core');
                    } else {
                        Redirect('get/vanilla-core');
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

        if (is_array($this->Addon) && sizeof($this->Addon))
            $this->Addon = (object)$this->Addon;

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
            Gdn_FileSystem::ServeFile('uploads/'.$this->Addon->File, Gdn_Format::Url($this->Addon->Name.'-'.$this->Addon->Version).'.zip');
        }

        $this->AddModule('DownloadHelpModule');
        $this->Render();
    }

    public function Nightly() {
        $this->Get('nightly');
    }
}