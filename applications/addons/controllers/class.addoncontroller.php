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
 *
 */
class AddonController extends AddonsController {

    /** @var array  */
    public $Uses = array('Form', 'AddonModel', 'AddonCommentModel');

    /** @var string  */
    public $Filter = 'all';

    /** @var string  */
    public $Sort = 'recent';

    /** @var string  */
    public $Version = '0'; // The version of Vanilla to filter to (0 is no filter)

    /** @var Gdn_Form */
    public $Form;

    /** @var AddonModel */
    public $AddonModel;

    /**
     *
     */
    public function Initialize() {
        parent::Initialize();
        if ($this->Head) {
            $this->AddJsFile('jquery.js');
            $this->AddJsFile('jquery.form.js');
            $this->AddJsFile('jquery.popup.js');
            $this->AddJsFile('jquery.gardenhandleajaxform.js');
            $this->addJsFile('jquery.autosize.min.js');
            $this->AddJsFile('global.js');
        }
        $this->CountCommentsPerPage = 30;
    }

    /**
     * Homepage & single addon view.
     */
    public function Index($ID = '') {
        if ($ID != '') {
            $Addon = $this->AddonModel->GetSlug($ID, true);
            if (!is_array($Addon)) {
                throw NotFoundException('Addon');
            } else {
                $AddonID = $Addon['AddonID'];
                $this->SetData($Addon);

                $Description = val('Description', $Addon);
                if ($Description) {
                    $this->Head->AddTag('meta', array('name' => 'description', 'content' => Gdn_Format::PlainText($Description, false)));
                }

//                if ($MaxVersion) {
//                    $this->SetData('CurrentVersion', val('Version', $MaxVersion));
//                }

                $this->AddCssFile('popup.css');
                $this->AddCssFile('fancyzoom.css');
                $this->AddJsFile('fancyzoom.js');
                $this->AddJsFile('addon.js');
                $PictureModel = new Gdn_Model('AddonPicture');
                $this->PictureData = $PictureModel->GetWhere(array('AddonID' => $AddonID));
                $DiscussionModel = new DiscussionModel();
                $this->DiscussionData = $DiscussionModel->Get(0, 50, array('AddonID' => $AddonID));

                $this->View = 'addon';
                $this->Title($this->Data('Name').' '.$this->Data('Version').' by '.$this->Data('InsertName'));

                // Set the canonical url.
                $this->CanonicalUrl(Url('/addon/'.AddonModel::Slug($Addon, false), true));
            }
        } else {
            $this->View = 'browse';
            $this->Browse();
            return;
        }
        $this->AddModule('AddonHelpModule');
        $this->SetData('_Types', AddonModel::$Types);
        $this->SetData('_TypesPlural', AddonModel::$TypesPlural);

        $this->Render();
    }

    /**
     *
     */
    public function Add() {
        $this->Permission('Addons.Addon.Add');
        $this->AddModule('AddonHelpModule', 'Panel');

        $this->Form->SetModel($this->AddonModel);

        if ($this->Form->IsPostBack()) {
            $Upload = new Gdn_Upload();
            $Upload->AllowFileExtension(null);
            $Upload->AllowFileExtension('zip');
            try {
                // Validate the upload
                $TmpFile = $Upload->ValidateUpload('File');
                $Extension = pathinfo($Upload->GetUploadedFileName(), PATHINFO_EXTENSION);

                // Generate the target name
                $TargetFile = $Upload->GenerateTargetName('addons', $Extension);
                $FileBaseName = pathinfo($TargetFile, PATHINFO_BASENAME);
                $TargetPath = PATH_UPLOADS.'/'.$TargetFile;

                if (!file_exists(dirname($TargetPath))) {
                    mkdir(dirname($TargetPath), 0777, true);
                }

                // Save the file to a temporary location for parsing...
                if (!move_uploaded_file($TmpFile, $TargetPath)) {
                    throw new Exception("We couldn't save the file you uploaded. Please try again later.", 400);
                }

                $AnalyzedAddon = UpdateModel::AnalyzeAddon($TargetPath, true);

                // Set the filename for the CDN...
                $Upload->EventArguments['OriginalFilename'] = AddonModel::Slug($AnalyzedAddon, true).'.zip';

                // Save the uploaded file
                $Parsed = $Upload->SaveAs(
                    $TargetPath,
                    $TargetFile
                );
                $AnalyzedAddon['File'] = $Parsed['SaveName'];
                unset($AnalyzedAddon['Path']);
                $AnalyzedAddon['Description2'] = $this->Form->GetFormValue('Description2');
                Trace($AnalyzedAddon, 'Analyzed Addon');

                // If the long description is blank, load up the readme if it exists
                if ($AnalyzedAddon['Description2'] == '') {
                    $AnalyzedAddon['Description2'] = $this->ParseReadme($TargetPath);
                }

                $this->Form->FormValues($AnalyzedAddon);
            } catch (Exception $ex) {
                $this->Form->AddError($ex);
            }

            if (isset($TargetPath) && file_exists($TargetPath)) {
                unlink($TargetPath);
            }

            // If there were no errors, save the addon
            if ($this->Form->ErrorCount() == 0) {
                // Set some additional values to save.
                $this->Form->SetFormValue('Vanilla2', true);

                // Save the addon
                $AddonID = $this->Form->Save();
                if ($AddonID !== false) {
                    $Addon = $this->AddonModel->GetID($AddonID);
                    $this->SetData('Addon', $Addon);

                    if ($this->DeliveryType() == DELIVERY_TYPE_ALL) {
                        // Redirect to the new addon.
                        Redirect("addon/".AddonModel::Slug($Addon, false));
                    }
                }
            } else {
                if (isset($TargetFile) && file_exists($TargetFile)) {
                    unlink($TargetFile);
                }
            }
        }

        $this->Render();
    }

    /**
     *
     *
     * @param $AddonID
     * @param bool|false $SaveVersionID
     * @throws Exception
     */
    public function Check($AddonID, $SaveVersionID = false) {
        $this->Permission('Addons.Addon.Manage');

        if ($SaveVersionID !== false) {
            // Get the version data.
            $Version = $this->AddonModel->SQL->GetWhere('AddonVersion', array('AddonVersionID' => $SaveVersionID))->FirstRow(DATASET_TYPE_ARRAY);

            $this->AddonModel->Save($Version);
            $this->Form->SetValidationResults($this->AddonModel->ValidationResults());
        }

        $Addon = $this->AddonModel->GetID($AddonID, true);
        $AddonTypes = Gdn::SQL()->Get('AddonType')->ResultArray();
        $AddonTypes = Gdn_DataSet::Index($AddonTypes, 'AddonTypeID');

        if (!$Addon) {
            throw NotFoundException('Addon');
        }

        // Get the data for the most recent version of the addon.
        $Path = PATH_UPLOADS.'/'.$Addon['File'];

        $AddonData = ArrayTranslate((array)$Addon, array('AddonID', 'AddonKey', 'Name', 'Type', 'Description', 'Requirements', 'Checked'));
        try {
            $FileAddonData = UpdateModel::AnalyzeAddon($Path);
            if ($FileAddonData) {
                $AddonData = array_merge($AddonData, ArrayTranslate($FileAddonData, array('AddonKey' => 'File_AddonKey', 'Name' => 'File_Name', 'File_Type', 'Description' => 'File_Description', 'Requirements' => 'File_Requirements', 'Checked' => 'File_Checked')));
                $AddonData['File_Type'] = GetValueR($FileAddonData['AddonTypeID'].'.Label', $AddonTypes, 'Unknown');
            }
        } catch (Exception $Ex) {
            $AddonData['File_Error'] = $Ex->getMessage();
        }
        $this->SetData('Addon', $AddonData);

        // Go through the versions and make sure we get the versions to check out.
        $Versions = array();
        foreach ($Addon['Versions'] as $Version) {
            $Version = $Version;
            $Path = PATH_UPLOADS."/{$Version['File']}";

            try {
                $VersionData = ArrayTranslate((array)$Version, array('AddonVersionID', 'Version', 'AddonKey', 'Name', 'MD5', 'FileSize', 'Checked'));

                $FileVersionData = UpdateModel::AnalyzeAddon($Path);
                $FileVersionData = ArrayTranslate($FileVersionData, array('Version' => 'File_Version', 'AddonKey' => 'File_AddonKey', 'Name' => 'File_Name', 'MD5' => 'File_MD5', 'FileSize' => 'File_FileSize', 'Checked' => 'File_Checked'));
            } catch (Exception $Ex) {
                $FileVersionData = array('File_Error' => $Ex->getMessage());
            }
            $Versions[] = array_merge($VersionData, $FileVersionData);
        }
        $this->SetData('Versions', $Versions);

        $this->AddModule('AddonHelpModule');
        $this->Render();
    }

    /**
     *
     *
     * @param $VersionID
     * @throws Gdn_UserException
     */
    public function DeleteVersion($VersionID) {
        $this->Permission('Addons.Addon.Manage');
        $Version = $this->AddonModel->GetVersion($VersionID);
        $this->Data = $Version;

        if ($this->Form->AuthenticatedPostBack() && $this->Form->GetFormValue('Yes')) {
            $this->AddonModel->DeleteVersion($VersionID);

            // Update the current version of the addon.
            $AddonID = val('AddonID', $Version);
            $this->AddonModel->UpdateCurrentVersion($AddonID);
            $this->RedirectUrl = Url('/addon/check/'.$AddonID);
        }
        $this->Render();
    }

    /**
     *
     *
     * @param string $AddonID
     * @throws Exception
     * @throws Gdn_UserException
     */
    public function Edit($AddonID = '') {
        $this->Permission('Addons.Addon.Add');

        $Session = Gdn::Session();
        $Addon = $this->AddonModel->GetID($AddonID);
        if (!$Addon) {
            throw NotFoundException('Addon');
        }

        if ($Addon['InsertUserID'] != $Session->UserID) {
            $this->Permission('Addons.Addon.Manage');
        }

        $this->Form->SetModel($this->AddonModel);
        $this->Form->AddHidden('AddonID', $AddonID);
        $AddonTypeModel = new Gdn_Model('AddonType');
        $this->TypeData = $AddonTypeModel->GetWhere(array('Visible' => '1'));

        if ($this->Form->AuthenticatedPostBack() === false) {
            $this->Form->SetData($Addon);
        } else {
            if ($this->Form->Save() !== false) {
                $Addon = $this->AddonModel->GetID($AddonID);
                $this->StatusMessage = T("Your changes have been saved successfully.");
                $this->RedirectUrl = Url('/addon/'.AddonModel::Slug($Addon));
            }
        }

        $this->Render();
    }

    /**
     *
     *
     * @param string $AddonID
     * @throws Exception
     */
    public function NewVersion($AddonID = '') {
        $this->_NewVersion($AddonID);
    }

    /**
     *
     *
     * @param string $AddonID
     * @param bool|false $V1
     * @throws Exception
     */
    protected function _NewVersion($AddonID = '', $V1 = false) {
        $Session = Gdn::Session();
        $Addon = $this->AddonModel->GetID($AddonID);
        if (!$Addon) {
            throw NotFoundException('Addon');
        }

        if ($Addon['InsertUserID'] != $Session->UserID) {
            $this->Permission('Garden.Settings.Manage');
        }

        $this->AddModule('AddonHelpModule');

        $this->Form->SetModel($this->AddonModel);
        $this->Form->AddHidden('AddonID', $AddonID);

        if ($this->Form->IsPostBack()) {
            $Upload = new Gdn_Upload();
            $Upload->AllowFileExtension(null);
            $Upload->AllowFileExtension('zip');
            try {
                // Validate the upload
                $TmpFile = $Upload->ValidateUpload('File');
                $Extension = pathinfo($Upload->GetUploadedFileName(), PATHINFO_EXTENSION);

                // Generate the target name
                $TargetFile = $Upload->GenerateTargetName('addons', $Extension);
                $FileBaseName = pathinfo($TargetFile, PATHINFO_BASENAME);
                $TargetPath = PATH_UPLOADS.'/'.$TargetFile;

                if (!file_exists(dirname($TargetPath))) {
                    mkdir(dirname($TargetPath), 0777, true);
                }

                // Save the file to a temporary location for parsing...
                if (!move_uploaded_file($TmpFile, $TargetPath)) {
                    throw new Exception("We couldn't save the file you uploaded. Please try again later.", 400);
                }

                $AnalyzedAddon = UpdateModel::AnalyzeAddon($TargetPath, true);

                // Set the filename for the CDN...
                $Upload->EventArguments['OriginalFilename'] = AddonModel::Slug($AnalyzedAddon, true).'.zip';

                // Save the uploaded file
                $Parsed = $Upload->SaveAs(
                    $TargetPath,
                    $TargetFile
                );
                $AnalyzedAddon['AddonID'] = $AddonID;
                $AnalyzedAddon['File'] = $Parsed['SaveName'];
                unset($AnalyzedAddon['Path']);
                Trace($AnalyzedAddon, 'Analyzed Addon');


                $this->Form->FormValues($AnalyzedAddon);
            } catch (Exception $ex) {
                $this->Form->AddError($ex);

                // Delete the erroneous file.
                try {
                    $Upload->Delete($AnalyzedAddon['File']);
                } catch (Exception $Ex2) {
                }
            }

            if (isset($TargetPath) && file_exists($TargetPath)) {
                unlink($TargetPath);
            }

            // If there were no errors, save the addonversion
            if ($this->Form->ErrorCount() == 0) {
                $NewVersionID = $this->Form->Save($V1);
                if ($NewVersionID) {
                    $this->SetData('Addon', $AnalyzedAddon);
                    $this->SetData('Url', Url('/addon/'.AddonModel::Slug($Addon, true), true));
                    if ($this->DeliveryType() == DELIVERY_TYPE_ALL) {
                        $this->RedirectUrl = $this->Data('Url');
                    }
                } else {
                    if (file_exists($Path)) {
                        unlink($Path);
                    }
                }
            }
        }
        $this->Render();
    }

    /**
     *
     */
    public function NotFound() {
        $this->Render();
    }

    /**
     *
     *
     * @param string $AddonID
     * @param string $AddonVersionID
     * @throws Gdn_UserException
     */
    public function Approve($AddonID = '', $AddonVersionID = '') {
        $this->Permission('Addons.Addon.Manage');
        $Session = Gdn::Session();

        $transientKey = Gdn::request()->get('TransientKey', false);
        if (!$Session->isValid() || !$Session->validateTransientKey($transientKey)) {
            throw new Gdn_UserException('The CSRF token is invalid.', 403);
        }

        if ($AddonVersionID) {
            $AddonID = $this->AddonModel->SQL->GetWhere('AddonVersion', array('AddonVersionID' => $AddonVersionID))->Value('AddonID');
            $Addon = $this->Addon = $this->AddonModel->GetID($AddonID);
        } else {
            $Addon = $this->Addon = $this->AddonModel->GetID($AddonID);
            $AddonVersionID = $Addon['AddonVersionID'];
        }
        $VersionModel = new Gdn_Model('AddonVersion');
        $AddonVersion = $VersionModel->GetID($AddonVersionID, DATASET_TYPE_ARRAY);

        if (!$AddonVersion['DateReviewed']) {
            $VersionModel->Save(array('AddonVersionID' => $AddonVersionID, 'DateReviewed' => Gdn_Format::ToDateTime()));
        } else {
            $VersionModel->Update(array('DateReviewed' => null), array('AddonVersionID' => $AddonVersionID));
        }

        Redirect('/addon/'.AddonModel::Slug($Addon));
    }

    /**
     *
     *
     * @param null $DiscussionID
     * @throws Exception
     * @throws Gdn_UserException
     */
    public function AttachToDiscussion($DiscussionID = null) {
          $this->Permission('Addons.Addon.Manage');
          $DiscussionModel = new DiscussionModel();
          $Discussion = $DiscussionModel->GetID($DiscussionID);
        if ($Discussion) {
              $Addon = $this->AddonModel->GetID($Discussion->AddonID);
              $this->Form->SetData($Addon);
              $RedirectUrl = 'discussion/' . $Discussion->DiscussionID;
        } else {
            throw NotFoundException('Discussion');
        }

        if ($this->Form->AuthenticatedPostBack()) {
            // Look up for an existing addon
            $FormValues = $this->Form->FormValues();
            $Addon = false;
            if (val('Name', $FormValues, false)) {
                 $Addon = $this->AddonModel->GetWhere(array('a.Name' => $FormValues['Name']))->FirstRow(DATASET_TYPE_ARRAY);
            }

            if ($Addon == false && val('AddonID', $FormValues, false)) {
                 $Addon = $this->AddonModel->GetID($FormValues['AddonID']);
            }

            if ($Addon == false) {
                 $this->Form->AddError(T('Unable to find addon via Name or ID'));
            }

            if ($this->Form->ErrorCount() == 0) {
                 $DiscussionModel->SetField($DiscussionID, 'AddonID', $Addon['AddonID']);
                if ($this->DeliveryType() === DELIVERY_TYPE_ALL) {
                     Redirect($RedirectUrl ? : 'addon/' . $Addon['AddonID']);
                } else {
                     $this->InformMessage(T('Successfully updated Attached Addon!'));
                     $this->JsonTarget('.Warning.AddonAttachment', null, 'Remove');
                     $this->JsonTarget('.ItemDiscussion .Message', RenderDiscussionAddonWarning($Addon['AddonID'], $Addon['Name'], $Discussion->DiscussionID), 'Prepend');
                     $this->JsonTarget('a.AttachAddonDiscussion.Popup', T('Edit Addon Attachment...'), 'Text');
                }
            }
        }

            $this->Render('attach');
    }

    /**
     *
     *
     * @param null $DiscussionID
     * @throws Exception
     * @throws Gdn_UserException
     */
    public function DetachFromDiscussion($DiscussionID = null) {
         $this->Permission('Addons.Addon.Manage');
         $DiscussionModel = new DiscussionModel();
         $Discussion = $DiscussionModel->GetID($DiscussionID);
        if ($Discussion) {
              $Addon = $this->AddonModel->GetID($Discussion->AddonID);
              $this->Form->SetData($Addon);
              $RedirectUrl = 'discussion/' . $Discussion->DiscussionID;
        } else {
            throw NotFoundException('Discussion');
        }

        if ($this->Form->AuthenticatedPostBack()) {

            if (!$this->Form->GetFormValue('DetachConfirm', false)) {
                 $this->Form->AddError(T('You must confirm the detachment'), 'DetachConfirm');
            } else {
                $DiscussionModel->SetField($DiscussionID, 'AddonID', null);
                if ($this->DeliveryType() === DELIVERY_TYPE_ALL) {
                     Redirect($RedirectUrl);
                } else {
                    $this->InformMessage(T('Successfully detached addon'));
                    $this->JsonTarget('.Warning.AddonAttachment', null, 'Remove');
                    $this->JsonTarget('a.AttachAddonDiscussion.Popup', T('Attach Addon...'), 'Text');
                }
            }
        }
            $this->Render('detach');
    }

    /**
     *
     *
     * @param $Code
     * @param $Item
     * @return string
     */
    protected static function NotFoundString($Code, $Item) {
        return sprintf(T('%1$s "%2$s" not found.'), T($Code), $Item);
    }

    /**
     *
     *
     * @param $AddonID
     * @throws Exception
     */
    public function ChangeOwner($AddonID) {
        $this->Permission('Garden.Settings.Manage');
        $Addon = $this->AddonModel->GetSlug($AddonID);

        if (!$Addon) {
            throw NotFoundException('Addon');
        }

        if ($this->Form->IsPostBack()) {
            $this->Form->ValidateRule('User', 'ValidateRequired');

            if ($this->Form->ErrorCount() == 0) {
                $NewUser = $this->Form->GetFormValue('User');
                if (is_numeric($NewUser)) {
                    $User = Gdn::UserModel()->GetID($NewUser, DATASET_TYPE_ARRAY);
                } else {
                    $User = Gdn::UserModel()->GetByUsername($NewUser);
                }

                if (!$User) {
                    $this->Form->AddError('@'.self::NotFoundString('User', $NewUser));
                }
            }

            if ($this->Form->ErrorCount() == 0) {
                $this->AddonModel->SetField($Addon['AddonID'], 'InsertUserID', val('UserID', $User));
            }
        } else {
            $this->Form->AddError('You must POST to this page.');
        }

        $this->Render();
    }

    /**
     *
     *
     * @param string $AddonID
     * @throws Gdn_UserException
     */
    public function Delete($AddonID = '') {
        $this->Permission('Addons.Addon.Manage');
        $Session = Gdn::Session();
        if (!$Session->IsValid()) {
            $this->Form->AddError('You must be authenticated in order to use this form.');
        } else {
            $transientKey = Gdn::request()->get('TransientKey', false);
            if (!$Session->validateTransientKey($transientKey)) {
                throw new Gdn_UserException('The CSRF token is invalid.', 403);
            }
        }

        $Addon = $this->AddonModel->GetID($AddonID);
        if (!$Addon) {
            Redirect('dashboard/home/filenotfound');
        }

        if ($Session->UserID != $Addon['InsertUserID']) {
            $this->Permission('Addons.Addon.Manage');
        }

        $Session = Gdn::Session();
        if (is_numeric($AddonID)) {
            $this->AddonModel->Delete($AddonID);
        }

        if ($this->_DeliveryType === DELIVERY_TYPE_ALL) {
            Redirect(GetIncomingValue('Target', Gdn_Url::WebRoot()));
        }

        $this->View = 'index';
        $this->Render();
    }

    /**
     * Add a comment to an addon.
     *
     * @param string $AddonID
     * @throws Exception
     * @throws Gdn_UserException
     */
    public function AddComment($AddonID = '') {
        $Render = true;
        $this->Form->SetModel($this->AddonCommentModel);
        $AddonID = $this->Form->GetFormValue('AddonID', $AddonID);

        if (is_numeric($AddonID) && $AddonID > 0) {
            $this->Form->AddHidden('AddonID', $AddonID);
        }

        if ($this->Form->AuthenticatedPostBack()) {
            $NewCommentID = $this->Form->Save();
            // Comment not saving for some reason - no errors reported
            if ($NewCommentID > 0) {
                // Update the Comment count
                $this->AddonModel->SetProperty($AddonID, 'CountComments', $this->AddonCommentModel->GetCount(array('AddonID' => $AddonID)));
                if ($this->DeliveryType() == DELIVERY_TYPE_ALL) {
                    Redirect('addon/'.$AddonID.'/#Comment_'.$NewCommentID);
                }

                $this->SetJson('CommentID', $NewCommentID);
                // If this was not a full-page delivery type, return the partial response
                // Load all new messages that the user hasn't seen yet (including theirs)
                $LastCommentID = $this->Form->GetFormValue('LastCommentID');
                if (!is_numeric($LastCommentID)) {
                    $LastCommentID = $NewCommentID - 1;
                }

                $Session = Gdn::Session();
                $this->Addon = $this->AddonModel->GetID($AddonID);
                $this->CommentData = $this->AddonCommentModel->GetNew($AddonID, $LastCommentID);
                $this->View = 'comments';
            } else {
                // Handle ajax based errors...
                if ($this->DeliveryType() != DELIVERY_TYPE_ALL) {
                    $this->StatusMessage = $this->Form->Errors();
                } else {
                    $Render = false;
                    $this->Index($AddonID);
                }
            }
        }

        if ($Render) {
            $this->Render();
        }
    }

    /**
     *
     *
     * @param string $FilterToType
     * @param string $Sort
     * @param string $VanillaVersion
     * @param string $Page
     * @throws Exception
     */
    public function Browse($FilterToType = '', $Sort = '', $VanillaVersion = '', $Page = '') {
        $Checked = GetIncomingValue('checked', false);

        // Implement user prefs
        $Session = Gdn::Session();
        if ($Session->IsValid()) {
            if ($FilterToType != '') {
                $Session->SetPreference('Addons.FilterType', $FilterToType);
            }
            if ($VanillaVersion != '') {
                $Session->SetPreference('Addons.FilterVanilla', $VanillaVersion);
            }
            if ($Sort != '') {
                $Session->SetPreference('Addons.Sort', $Sort);
            }
            if ($Checked !== false) {
                $Session->SetPreference('Addons.FilterChecked', $Checked);
            }

            $FilterToType = $Session->GetPreference('Addons.FilterType', 'all');
            $VanillaVersion = $Session->GetPreference('Addons.FilterVanilla', '2');
            $Sort = $Session->GetPreference('Addons.Sort', 'recent');
            $Checked = $Session->GetPreference('Addons.FilterChecked');
        }

        if (!array_key_exists($FilterToType, AddonModel::$TypesPlural)) {
            $FilterToType = 'all';
        }

        if ($Sort != 'popular') {
            $Sort = 'recent';
        }

        if (!in_array($VanillaVersion, array('1', '2'))) {
            $VanillaVersion = '2';
        }

        $this->Version = $VanillaVersion;

        $this->Sort = $Sort;

        $this->FilterChecked = $Checked;

        $this->AddJsFile('/js/library/jquery.gardenmorepager.js');
        $this->AddJsFile('browse.js');

        list($Offset, $Limit) = OffsetLimit($Page, Gdn::Config('Garden.Search.PerPage', 20));

        $this->Filter = $FilterToType;

        if ($this->Filter == 'themes') {
            $Title = 'Browse Themes';
        } elseif ($this->Filter == 'plugins') {
            $Title = 'Browse Plugins';
        } elseif ($this->Filter == 'applications') {
            $Title = 'Browse Applications';
        } else {
            $Title = 'Browse Addons';
        }
        $this->SetData('Title', $Title);

        $Search = GetIncomingValue('Keywords', '');
        $this->_BuildBrowseWheres($Search);

        $SortField = $Sort == 'recent' ? 'DateUpdated' : 'CountDownloads';
        $ResultSet = $this->AddonModel->GetWhere(false, $SortField, 'desc', $Limit, $Offset);
        $this->SetData('Addons', $ResultSet);
        $this->_BuildBrowseWheres($Search);
        $NumResults = $this->AddonModel->GetCount(false);
        $this->SetData('TotalAddons', $NumResults);

        // Build a pager
        $PagerFactory = new Gdn_PagerFactory();
        $Pager = $PagerFactory->GetPager('Pager', $this);
        $Pager->MoreCode = '›';
        $Pager->LessCode = '‹';
        $Pager->ClientID = 'Pager';
        $Pager->Configure(
            $Offset,
            $Limit,
            $NumResults,
            'addon/browse/'.$FilterToType.'/'.$Sort.'/'.$this->Version.'/%1$s/?Keywords='.urlencode($Search)
        );
        $this->SetData('_Pager', $Pager);

        if ($this->_DeliveryType != DELIVERY_TYPE_ALL) {
            $this->SetJson('MoreRow', $Pager->ToString('more'));
        }

        $this->AddModule('AddonHelpModule');

        $this->Render();
    }

    /**
     *
     *
     * @param string $Search
     */
    private function _BuildBrowseWheres($Search = '') {
        if ($Search != '') {
            $this->AddonModel
                ->SQL
                ->BeginWhereGroup()
                ->Like('a.Name', $Search)
                ->OrLike('a.Description', $Search)
                ->EndWhereGroup();
        }

        if ($this->Version != 0) {
            $this->AddonModel
                ->SQL
                ->Where('a.Vanilla2', $this->Version == '1' ? '0' : '1');
        }

        $Ch = array('unchecked' => 0, 'checked' => 1);
        if (isset($Ch[$this->FilterChecked])) {
            $this->AddonModel->SQL->Where('a.Checked', $Ch[$this->FilterChecked]);
        }

        if ($Types = $this->Request->Get('Types')) {
            $Types = explode(',', $Types);
            foreach ($Types as $Index => $Type) {
                if (isset(AddonModel::$Types[trim($Type)])) {
                    $Types[$Index] = AddonModel::$Types[trim($Type)];
                } else {
                    unset($Types[$Index]);
                }
            }
            $this->AddonModel->SQL->WhereIn('a.AddonTypeID', $Types);
        }

        $AddonTypeID = val($this->Filter, AddonModel::$TypesPlural);
        if ($AddonTypeID) {
            $this->AddonModel
                ->SQL
                ->Where('a.AddonTypeID', $AddonTypeID);
        }
    }

    /**
     *
     *
     * @param string $AddonID
     * @throws Exception
     * @throws Gdn_UserException
     */
    public function AddPicture($AddonID = '') {
        $Session = Gdn::Session();
        if (!$Session->IsValid()) {
            $this->Form->AddError('You must be authenticated in order to use this form.');
        }

        $Addon = $this->AddonModel->GetID($AddonID);
        if (!$Addon) {
            throw NotFoundException('Addon');
        }

        if ($Session->UserID != $Addon['InsertUserID']) {
            $this->Permission('Addons.Addon.Manage');
        }

        $this->AddModule('AddonHelpModule', 'Panel');
        $AddonPictureModel = new Gdn_Model('AddonPicture');
        $this->Form->SetModel($AddonPictureModel);
        $this->Form->AddHidden('AddonID', $AddonID);
        if ($this->Form->AuthenticatedPostBack() === true) {
            $UploadImage = new Gdn_UploadImage();
            try {
                // Validate the upload
                $TmpImage = $UploadImage->ValidateUpload('Picture');

                // Generate the target image name
                $TargetImage = $UploadImage->GenerateTargetName(PATH_UPLOADS, '');
                $ImageBaseName = 'addons/screens/'.pathinfo($TargetImage, PATHINFO_BASENAME);

                // Save the uploaded image in large size
                $ImgParsed = $UploadImage->SaveImageAs(
                    $TmpImage,
                    ChangeBaseName($ImageBaseName, 'ao%s'),
                    700,
                    1000
                );

                // Save the uploaded image in thumbnail size
                $ThumbSize = 150;
                $ThumbParsed = $UploadImage->SaveImageAs(
                    $TmpImage,
                    ChangeBasename($ImageBaseName, 'at%s'),
                    $ThumbSize,
                    $ThumbSize
                );
                $ImageBaseName = sprintf($ImgParsed['SaveFormat'], $ImageBaseName);
            } catch (Exception $ex) {
                $this->Form->AddError($ex->getMessage());
            }
            // If there were no errors, insert the picture
            if ($this->Form->ErrorCount() == 0) {
                $AddonPictureModel = new Gdn_Model('AddonPicture');
                $AddonPictureID = $AddonPictureModel->Insert(array('AddonID' => $AddonID, 'File' => $ImageBaseName));
            }

            // If there were no problems, redirect back to the addon
            if ($this->Form->ErrorCount() == 0) {
                $this->RedirectUrl = Url('/addon/'.AddonModel::Slug($Addon));
            }
        }
        $this->Render();
    }

    /**
     *
     *
     * @param string $AddonPictureID
     * @throws Exception
     * @throws Gdn_UserException
     */
    public function DeletePicture($AddonPictureID = '') {
        $AddonPictureModel = new Gdn_Model('AddonPicture');
        $Picture = $AddonPictureModel->GetWhere(array('AddonPictureID' => $AddonPictureID))->FirstRow();
        $AddonModel = new AddonModel();
        $Addon = $AddonModel->GetID($Picture->AddonID);
        $Session = Gdn::Session();

        if ($Session->UserID != $Addon['InsertUserID'] && !$Session->CheckPermission('Addons.Addon.Manage')) {
            throw PermissionException();
        }

        if ($this->Form->AuthenticatedPostBack() && $this->Form->GetFormValue('Yes')) {
            if ($Picture) {
                $Upload = new Gdn_Upload();
                $Upload->Delete(ChangeBasename($Picture->File, 'ao%s'));
                $Upload->Delete(ChangeBasename($Picture->File, 'at%s'));
                $AddonPictureModel->Delete(array('AddonPictureID' => $AddonPictureID));
            }
            $this->RedirectUrl = Url('/addon/'.$Picture->AddonID);
        }
        $this->Render('deleteversion');
    }

    /**
     *
     *
     * @param $IDs
     */
    public function GetList($IDs) {
        $IDs = explode(',', $IDs);
        array_map('trim', $IDs);

        $Addons = $this->AddonModel->GetIDs($IDs);
        $this->SetData('Addons', $Addons);

        $this->Render('browse');
    }

    /**
     *
     *
     * @param string $AddonID
     * @throws Exception
     */
    public function Icon($AddonID = '') {
        $Session = Gdn::Session();
        if (!$Session->IsValid()) {
            $this->Form->AddError('You must be authenticated in order to use this form.');
        }

        $Addon = $this->AddonModel->GetID($AddonID);
        if (!$Addon) {
            throw NotFoundException('Addon');
        }

        if ($Session->UserID != $Addon['InsertUserID']) {
            $this->Permission('Addons.Addon.Manage');
        }

        $this->AddModule('AddonHelpModule', 'Panel');
        $this->Form->SetModel($this->AddonModel);
        $this->Form->AddHidden('AddonID', $AddonID);
        if ($this->Form->IsPostBack()) {
            $UploadImage = new Gdn_UploadImage();
            try {
                // Validate the upload
                $TmpImage = $UploadImage->ValidateUpload('Icon');

                // Generate the target image name
                $TargetImage = $UploadImage->GenerateTargetName('addons/icons', '');
                $ImageBaseName = pathinfo($TargetImage, PATHINFO_BASENAME);

                // Save the uploaded icon
                $Parsed = $UploadImage->SaveImageAs(
                    $TmpImage,
                    $TargetImage,
                    256,
                    256,
                    false,
                    false
                );
                $TargetImage = $Parsed['SaveName'];
            } catch (Exception $ex) {
                $this->Form->AddError($ex);
            }
            // If there were no errors, remove the old picture and insert the picture
            if ($this->Form->ErrorCount() == 0) {
//                $Addon = $this->AddonModel->GetID($AddonID);
                if ($Addon['Icon']) {
                    $UploadImage->Delete($Addon['Icon']);
                }

                $this->AddonModel->Save(array('AddonID' => $AddonID, 'Icon' => $TargetImage));
            }

            // If there were no problems, redirect back to the addon
            if ($this->Form->ErrorCount() == 0) {
                $this->RedirectUrl = Url('/addon/'.AddonModel::Slug($Addon));
            }
        }
        $this->Render();
    }

    /**
     *
     *
     * @param $Path
     * @return string
     */
    protected function ParseReadme($Path) {
        $ReadmePaths = array(
            '/readme',
            '/README',
            '/readme.md',
            '/README.md',
            '/readme.txt',
            '/README.txt',
        );

        // Get the list of potential files to analyze.
        // TODO: get rid of this invoke call once UpdateModel is more modular
        $UpdateModel = new UpdateModel();
        $GetInfo = new ReflectionMethod('UpdateModel', '_GetInfoZip');
        $GetInfo->setAccessible(true);
        $Entries = $GetInfo->invoke($UpdateModel, $Path, $ReadmePaths, false, true);
        
        if ($Entries === false) {
            return '';
        }

        foreach ($Entries as $Entry) {
            $ReadMeContents = file_get_contents($Entry['Path']);
            $Description = Gdn_Format::Markdown($ReadMeContents);
        }

        $FolderPath = substr($Path, 0, -4);
        Gdn_FileSystem::RemoveFolder($FolderPath);

        return $Description;
    }
}
