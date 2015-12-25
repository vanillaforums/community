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
    public function initialize() {
        parent::initialize();
        if ($this->Head) {
            $this->addJsFile('jquery.js');
            $this->addJsFile('jquery.form.js');
            $this->addJsFile('jquery.popup.js');
            $this->addJsFile('jquery.gardenhandleajaxform.js');
            $this->addJsFile('jquery.autosize.min.js');
            $this->addJsFile('global.js');
        }
        $this->CountCommentsPerPage = 30;
    }

    /**
     * Homepage & single addon view.
     */
    public function index($ID = '') {
        if ($ID != '') {
            $Addon = $this->AddonModel->getSlug($ID, true);
            if (!is_array($Addon)) {
                throw notFoundException('Addon');
            } else {
                $AddonID = $Addon['AddonID'];
                $this->setData($Addon);

                $Description = val('Description', $Addon);
                if ($Description) {
                    $this->Head->addTag('meta', array('name' => 'description', 'content' => Gdn_Format::plainText($Description, false)));
                }

                $this->addCssFile('popup.css');
                $this->addCssFile('fancyzoom.css');
                $this->addJsFile('fancyzoom.js');
                $this->addJsFile('addon.js');
                $PictureModel = new Gdn_Model('AddonPicture');
                $this->PictureData = $PictureModel->getWhere(array('AddonID' => $AddonID));
                $DiscussionModel = new DiscussionModel();
                $this->DiscussionData = $DiscussionModel->get(0, 50, array('AddonID' => $AddonID));

                $this->View = 'addon';
                $this->title($this->data('Name').' '.$this->data('Version').' by '.$this->data('InsertName'));

                // Set the canonical url.
                $this->canonicalUrl(url('/addon/'.AddonModel::slug($Addon, false), true));
            }
        } else {
            $this->View = 'browse';
            $this->browse();
            return;
        }

        $this->addModule('AddonHelpModule');
        $this->setData('_Types', AddonModel::$Types);
        $this->setData('_TypesPlural', AddonModel::$TypesPlural);

        $this->render();
    }

    /**
     *
     */
    public function add() {
        $this->permission('Addons.Addon.Add');
        $this->addModule('AddonHelpModule', 'Panel');

        $this->Form->setModel($this->AddonModel);

        if ($this->Form->isPostBack()) {
            $Upload = new Gdn_Upload();
            $Upload->allowFileExtension(null);
            $Upload->allowFileExtension('zip');
            try {
                // Validate the upload
                $TmpFile = $Upload->validateUpload('File');
                $Extension = pathinfo($Upload->getUploadedFileName(), PATHINFO_EXTENSION);

                // Generate the target name
                $TargetFile = $Upload->generateTargetName('addons', $Extension);
                $TargetPath = PATH_UPLOADS.'/'.$TargetFile;

                if (!file_exists(dirname($TargetPath))) {
                    mkdir(dirname($TargetPath), 0777, true);
                }

                // Save the file to a temporary location for parsing...
                if (!move_uploaded_file($TmpFile, $TargetPath)) {
                    throw new Exception("We couldn't save the file you uploaded. Please try again later.", 400);
                }

                $AnalyzedAddon = UpdateModel::analyzeAddon($TargetPath, true);

                // Set the filename for the CDN...
                $Upload->EventArguments['OriginalFilename'] = AddonModel::slug($AnalyzedAddon, true).'.zip';

                // Save the uploaded file
                $Parsed = $Upload->saveAs(
                    $TargetPath,
                    $TargetFile
                );
                $AnalyzedAddon['File'] = $Parsed['SaveName'];
                unset($AnalyzedAddon['Path']);
                $AnalyzedAddon['Description2'] = $this->Form->getFormValue('Description2');
                trace($AnalyzedAddon, 'Analyzed Addon');

                // If the long description is blank, load up the readme if it exists
                if ($AnalyzedAddon['Description2'] == '') {
                    $AnalyzedAddon['Description2'] = $this->parseReadme($TargetPath);
                }

                $this->Form->formValues($AnalyzedAddon);
            } catch (Exception $ex) {
                $this->Form->addError($ex);
            }

            if (isset($TargetPath) && file_exists($TargetPath)) {
                unlink($TargetPath);
            }

            // If there were no errors, save the addon
            if ($this->Form->errorCount() == 0) {
                // Set some additional values to save.
                $this->Form->setFormValue('Vanilla2', true);

                // Save the addon
                $AddonID = $this->Form->save();
                if ($AddonID !== false) {
                    $Addon = $this->AddonModel->getID($AddonID);
                    $this->setData('Addon', $Addon);

                    if ($this->deliveryType() == DELIVERY_TYPE_ALL) {
                        // Redirect to the new addon.
                        redirect("addon/".AddonModel::slug($Addon, false));
                    }
                }
            } else {
                if (isset($TargetFile) && file_exists($TargetFile)) {
                    unlink($TargetFile);
                }
            }
        }

        $this->render();
    }

    /**
     *
     *
     * @param $AddonID
     * @param bool|false $SaveVersionID
     * @throws Exception
     */
    public function check($AddonID, $SaveVersionID = false) {
        $this->permission('Addons.Addon.Manage');

        if ($SaveVersionID !== false) {
            // Get the version data.
            $Version = $this->AddonModel->SQL->getWhere('AddonVersion',
                array('AddonVersionID' => $SaveVersionID))->firstRow(DATASET_TYPE_ARRAY);

            $this->AddonModel->save($Version);
            $this->Form->setValidationResults($this->AddonModel->validationResults());
        }

        $Addon = $this->AddonModel->getID($AddonID, true);
        $AddonTypes = Gdn::sql()->get('AddonType')->resultArray();
        $AddonTypes = Gdn_DataSet::index($AddonTypes, 'AddonTypeID');

        if (!$Addon) {
            throw notFoundException('Addon');
        }

        // Get the data for the most recent version of the addon.
        $Path = PATH_UPLOADS.'/'.$Addon['File'];

        $AddonData = arrayTranslate((array)$Addon,
            array('AddonID', 'AddonKey', 'Name', 'Type', 'Description', 'Requirements', 'Checked'));

        try {
            $FileAddonData = UpdateModel::analyzeAddon($Path);
            if ($FileAddonData) {
                $AddonData = array_merge($AddonData, arrayTranslate($FileAddonData,
                    array('AddonKey' => 'File_AddonKey', 'Name' => 'File_Name', 'File_Type', 'Description' => 'File_Description', 'Requirements' => 'File_Requirements', 'Checked' => 'File_Checked')));
                $AddonData['File_Type'] = valr($FileAddonData['AddonTypeID'].'.Label', $AddonTypes, 'Unknown');
            }
        } catch (Exception $Ex) {
            $AddonData['File_Error'] = $Ex->getMessage();
        }
        $this->setData('Addon', $AddonData);

        // Go through the versions and make sure we get the versions to check out.
        $Versions = array();
        foreach ($Addon['Versions'] as $Version) {
            $Version = $Version;
            $Path = PATH_UPLOADS."/{$Version['File']}";

            try {
                $VersionData = arrayTranslate((array)$Version,
                    array('AddonVersionID', 'Version', 'AddonKey', 'Name', 'MD5', 'FileSize', 'Checked'));

                $FileVersionData = UpdateModel::analyzeAddon($Path);
                $FileVersionData = arrayTranslate($FileVersionData,
                    array('Version' => 'File_Version', 'AddonKey' => 'File_AddonKey', 'Name' => 'File_Name', 'MD5' => 'File_MD5', 'FileSize' => 'File_FileSize', 'Checked' => 'File_Checked'));
            } catch (Exception $Ex) {
                $FileVersionData = array('File_Error' => $Ex->getMessage());
            }
            $Versions[] = array_merge($VersionData, $FileVersionData);
        }
        $this->setData('Versions', $Versions);

        $this->addModule('AddonHelpModule');
        $this->render();
    }

    /**
     *
     *
     * @param $VersionID
     * @throws Gdn_UserException
     */
    public function deleteVersion($VersionID) {
        $this->permission('Addons.Addon.Manage');
        $Version = $this->AddonModel->getVersion($VersionID);
        $this->Data = $Version;

        if ($this->Form->authenticatedPostBack() && $this->Form->getFormValue('Yes')) {
            $this->AddonModel->deleteVersion($VersionID);

            // Update the current version of the addon.
            $AddonID = val('AddonID', $Version);
            $this->AddonModel->updateCurrentVersion($AddonID);
            $this->RedirectUrl = url('/addon/check/'.$AddonID);
        }
        $this->render();
    }

    /**
     *
     *
     * @param string $AddonID
     * @throws Exception
     * @throws Gdn_UserException
     */
    public function edit($AddonID = '') {
        $this->permission('Addons.Addon.Add');

        $Session = Gdn::session();
        $Addon = $this->AddonModel->GetID($AddonID);
        if (!$Addon) {
            throw notFoundException('Addon');
        }

        if ($Addon['InsertUserID'] != $Session->UserID) {
            $this->Permission('Addons.Addon.Manage');
        }

        $this->Form->setModel($this->AddonModel);
        $this->Form->addHidden('AddonID', $AddonID);
        $AddonTypeModel = new Gdn_Model('AddonType');
        $this->TypeData = $AddonTypeModel->getWhere(array('Visible' => '1'));

        if ($this->Form->authenticatedPostBack() === false) {
            $this->Form->setData($Addon);
        } else {
            if ($this->Form->save() !== false) {
                $Addon = $this->AddonModel->getID($AddonID);
                $this->StatusMessage = t("Your changes have been saved successfully.");
                $this->RedirectUrl = url('/addon/'.AddonModel::slug($Addon));
            }
        }

        $this->render();
    }

    /**
     *
     *
     * @param string $AddonID
     * @throws Exception
     */
    public function newVersion($AddonID = '') {
        $this->_newVersion($AddonID);
    }

    /**
     *
     *
     * @param string $AddonID
     * @param bool|false $V1
     * @throws Exception
     */
    protected function _newVersion($AddonID = '') {
        $Session = Gdn::session();
        $Addon = $this->AddonModel->getID($AddonID);
        if (!$Addon) {
            throw notFoundException('Addon');
        }

        if ($Addon['InsertUserID'] != $Session->UserID) {
            $this->permission('Garden.Settings.Manage');
        }

        $this->addModule('AddonHelpModule');

        $this->Form->setModel($this->AddonModel);
        $this->Form->addHidden('AddonID', $AddonID);

        if ($this->Form->isPostBack()) {
            $Upload = new Gdn_Upload();
            $Upload->allowFileExtension(null);
            $Upload->allowFileExtension('zip');
            try {
                // Validate the upload
                $TmpFile = $Upload->validateUpload('File');
                $Extension = pathinfo($Upload->getUploadedFileName(), PATHINFO_EXTENSION);

                // Generate the target name
                $TargetFile = $Upload->generateTargetName('addons', $Extension);
                $TargetPath = PATH_UPLOADS.'/'.$TargetFile;

                if (!file_exists(dirname($TargetPath))) {
                    mkdir(dirname($TargetPath), 0777, true);
                }

                // Save the file to a temporary location for parsing...
                if (!move_uploaded_file($TmpFile, $TargetPath)) {
                    throw new Exception("We couldn't save the file you uploaded. Please try again later.", 400);
                }

                $AnalyzedAddon = UpdateModel::analyzeAddon($TargetPath, true);

                // Set the filename for the CDN...
                $Upload->EventArguments['OriginalFilename'] = AddonModel::slug($AnalyzedAddon, true).'.zip';

                // Save the uploaded file
                $Parsed = $Upload->saveAs(
                    $TargetPath,
                    $TargetFile
                );
                $AnalyzedAddon['AddonID'] = $AddonID;
                $AnalyzedAddon['File'] = $Parsed['SaveName'];
                unset($AnalyzedAddon['Path']);
                trace($AnalyzedAddon, 'Analyzed Addon');

                $this->Form->formValues($AnalyzedAddon);
            } catch (Exception $ex) {
                $this->Form->addError($ex);

                // Delete the erroneous file.
                try {
                    $Upload->delete($AnalyzedAddon['File']);
                } catch (Exception $Ex2) {
                }
            }

            if (isset($TargetPath) && file_exists($TargetPath)) {
                unlink($TargetPath);
            }

            // If there were no errors, save the addonversion
            if ($this->Form->errorCount() == 0) {
                $NewVersionID = $this->Form->save(false);
                if ($NewVersionID) {
                    $this->setData('Addon', $AnalyzedAddon);
                    $this->setData('Url', url('/addon/'.AddonModel::slug($Addon, true), true));
                    if ($this->deliveryType() == DELIVERY_TYPE_ALL) {
                        $this->RedirectUrl = $this->data('Url');
                    }
                } else {
                    if (file_exists($TargetPath)) {
                        unlink($TargetPath);
                    }
                }
            }
        }
        $this->render();
    }

    /**
     *
     */
    public function notFound() {
        $this->render();
    }

    /**
     *
     *
     * @param string $AddonID
     * @param string $AddonVersionID
     * @throws Gdn_UserException
     */
    public function approve($AddonID = '', $AddonVersionID = '') {
        $this->permission('Addons.Addon.Manage');
        $Session = Gdn::session();

        $transientKey = Gdn::request()->get('TransientKey', false);
        if (!$Session->isValid() || !$Session->validateTransientKey($transientKey)) {
            throw new Gdn_UserException('The CSRF token is invalid.', 403);
        }

        if ($AddonVersionID) {
            $AddonID = $this->AddonModel->SQL->getWhere('AddonVersion', array('AddonVersionID' => $AddonVersionID))->value('AddonID');
            $Addon = $this->Addon = $this->AddonModel->getID($AddonID);
        } else {
            $Addon = $this->Addon = $this->AddonModel->getID($AddonID);
            $AddonVersionID = $Addon['AddonVersionID'];
        }
        $VersionModel = new Gdn_Model('AddonVersion');
        $AddonVersion = $VersionModel->getID($AddonVersionID, DATASET_TYPE_ARRAY);

        if (!$AddonVersion['DateReviewed']) {
            $VersionModel->save(array('AddonVersionID' => $AddonVersionID, 'DateReviewed' => Gdn_Format::toDateTime()));
        } else {
            $VersionModel->update(array('DateReviewed' => null), array('AddonVersionID' => $AddonVersionID));
        }

        safeRedirect('/addon/'.AddonModel::slug($Addon));
    }

    /**
     *
     *
     * @param null $DiscussionID
     * @throws Exception
     * @throws Gdn_UserException
     */
    public function attachToDiscussion($DiscussionID = null) {
          $this->permission('Addons.Addon.Manage');
          $DiscussionModel = new DiscussionModel();
          $Discussion = $DiscussionModel->getID($DiscussionID);
        if ($Discussion) {
              $Addon = $this->AddonModel->getID($Discussion->AddonID);
              $this->Form->setData($Addon);
              $RedirectUrl = 'discussion/' . $Discussion->DiscussionID;
        } else {
            throw notFoundException('Discussion');
        }

        if ($this->Form->authenticatedPostBack()) {
            // Look up for an existing addon
            $FormValues = $this->Form->formValues();
            $Addon = false;
            if (val('Name', $FormValues, false)) {
                 $Addon = $this->AddonModel->getWhere(array('a.Name' => $FormValues['Name']))->firstRow(DATASET_TYPE_ARRAY);
            }

            if ($Addon == false && val('AddonID', $FormValues, false)) {
                 $Addon = $this->AddonModel->getID($FormValues['AddonID']);
            }

            if ($Addon == false) {
                 $this->Form->addError(t('Unable to find addon via Name or ID'));
            }

            if ($this->Form->errorCount() == 0) {
                 $DiscussionModel->setField($DiscussionID, 'AddonID', $Addon['AddonID']);
                if ($this->deliveryType() === DELIVERY_TYPE_ALL) {
                     safeRedirect($RedirectUrl ? : 'addon/' . $Addon['AddonID']);
                } else {
                     $this->informMessage(t('Successfully updated Attached Addon!'));
                     $this->jsonTarget('.Warning.AddonAttachment', null, 'Remove');
                     $this->jsonTarget('.ItemDiscussion .Message', renderDiscussionAddonWarning($Addon['AddonID'], $Addon['Name'], $Discussion->DiscussionID), 'Prepend');
                     $this->jsonTarget('a.AttachAddonDiscussion.Popup', t('Edit Addon Attachment...'), 'Text');
                }
            }
        }

        $this->render('attach');
    }

    /**
     *
     *
     * @param null $DiscussionID
     * @throws Exception
     * @throws Gdn_UserException
     */
    public function detachFromDiscussion($DiscussionID = null) {
         $this->permission('Addons.Addon.Manage');
         $DiscussionModel = new DiscussionModel();
         $Discussion = $DiscussionModel->getID($DiscussionID);
        if ($Discussion) {
              $Addon = $this->AddonModel->getID($Discussion->AddonID);
              $this->Form->setData($Addon);
              $RedirectUrl = 'discussion/' . $Discussion->DiscussionID;
        } else {
            throw notFoundException('Discussion');
        }

        if ($this->Form->authenticatedPostBack()) {

            if (!$this->Form->getFormValue('DetachConfirm', false)) {
                 $this->Form->addError(t('You must confirm the detachment'), 'DetachConfirm');
            } else {
                $DiscussionModel->setField($DiscussionID, 'AddonID', null);
                if ($this->deliveryType() === DELIVERY_TYPE_ALL) {
                     redirect($RedirectUrl);
                } else {
                    $this->informMessage(t('Successfully detached addon'));
                    $this->jsonTarget('.Warning.AddonAttachment', null, 'Remove');
                    $this->jsonTarget('a.AttachAddonDiscussion.Popup', t('Attach Addon...'), 'Text');
                }
            }
        }
        $this->render('detach');
    }

    /**
     *
     *
     * @param $Code
     * @param $Item
     * @return string
     */
    protected static function notFoundString($Code, $Item) {
        return sprintf(t('%1$s "%2$s" not found.'), t($Code), $Item);
    }

    /**
     *
     *
     * @param $AddonID
     * @throws Exception
     */
    public function changeOwner($AddonID) {
        $this->permission('Garden.Settings.Manage');
        $Addon = $this->AddonModel->getSlug($AddonID);

        if (!$Addon) {
            throw notFoundException('Addon');
        }

        if ($this->Form->isPostBack()) {
            $this->Form->validateRule('User', 'ValidateRequired');

            if ($this->Form->errorCount() == 0) {
                $NewUser = $this->Form->getFormValue('User');
                if (is_numeric($NewUser)) {
                    $User = Gdn::userModel()->getID($NewUser, DATASET_TYPE_ARRAY);
                } else {
                    $User = Gdn::userModel()->getByUsername($NewUser);
                }

                if (!$User) {
                    $this->Form->addError('@'.self::notFoundString('User', $NewUser));
                }
            }

            if ($this->Form->errorCount() == 0) {
                $this->AddonModel->setField($Addon['AddonID'], 'InsertUserID', val('UserID', $User));
            }
        } else {
            $this->Form->addError('You must POST to this page.');
        }

        $this->render();
    }

    /**
     *
     *
     * @param string $AddonID
     * @throws Gdn_UserException
     */
    public function delete($AddonID = '') {
        $this->permission('Addons.Addon.Manage');
        $Session = Gdn::session();
        if (!$Session->isValid()) {
            $this->Form->addError('You must be authenticated in order to use this form.');
        } else {
            $transientKey = Gdn::request()->get('TransientKey', false);
            if (!$Session->validateTransientKey($transientKey)) {
                throw new Gdn_UserException('The CSRF token is invalid.', 403);
            }
        }

        $Addon = $this->AddonModel->getID($AddonID);
        if (!$Addon) {
            safeRedirect('dashboard/home/filenotfound');
        }

        if ($Session->UserID != $Addon['InsertUserID']) {
            $this->permission('Addons.Addon.Manage');
        }

        if (is_numeric($AddonID)) {
            $this->AddonModel->delete($AddonID);
        }

        if ($this->_DeliveryType === DELIVERY_TYPE_ALL) {
            safeRedirect(GetIncomingValue('Target', Gdn_Url::webRoot()));
        }

        $this->View = 'index';
        $this->render();
    }

    /**
     * Add a comment to an addon.
     *
     * @param string $AddonID
     * @throws Exception
     * @throws Gdn_UserException
     */
    public function addComment($AddonID = '') {
        $Render = true;
        $this->Form->setModel($this->AddonCommentModel);
        $AddonID = $this->Form->getFormValue('AddonID', $AddonID);

        if (is_numeric($AddonID) && $AddonID > 0) {
            $this->Form->addHidden('AddonID', $AddonID);
        }

        if ($this->Form->authenticatedPostBack()) {
            $NewCommentID = $this->Form->save();
            // Comment not saving for some reason - no errors reported
            if ($NewCommentID > 0) {
                // Update the Comment count
                $this->AddonModel->setProperty($AddonID, 'CountComments', $this->AddonCommentModel->getCount(array('AddonID' => $AddonID)));
                if ($this->deliveryType() == DELIVERY_TYPE_ALL) {
                    safeRedirect('addon/'.$AddonID.'/#Comment_'.$NewCommentID);
                }

                $this->setJson('CommentID', $NewCommentID);
                // If this was not a full-page delivery type, return the partial response
                // Load all new messages that the user hasn't seen yet (including theirs)
                $LastCommentID = $this->Form->getFormValue('LastCommentID');
                if (!is_numeric($LastCommentID)) {
                    $LastCommentID = $NewCommentID - 1;
                }

                $this->Addon = $this->AddonModel->getID($AddonID);
                $this->CommentData = $this->AddonCommentModel->getNew($AddonID, $LastCommentID);
                $this->View = 'comments';
            } else {
                // Handle ajax based errors...
                if ($this->deliveryType() != DELIVERY_TYPE_ALL) {
                    $this->StatusMessage = $this->Form->errors();
                } else {
                    $Render = false;
                    $this->index($AddonID);
                }
            }
        }

        if ($Render) {
            $this->render();
        }
    }

    /**
     * Filter the list of addons.
     *
     * @param string $FilterToType
     * @param string $Sort
     * @param string $VanillaVersion
     * @param string $Page
     * @throws Exception
     */
    public function browse($FilterToType = '', $Sort = '', $VanillaVersion = '', $Page = '') {
        $Checked = GetIncomingValue('checked', false);

        // Implement user prefs
        $Session = Gdn::session();
        if ($Session->isValid()) {
            if ($FilterToType != '') {
                $Session->setPreference('Addons.FilterType', $FilterToType);
            }
            if ($VanillaVersion != '') {
                $Session->setPreference('Addons.FilterVanilla', $VanillaVersion);
            }
            if ($Sort != '') {
                $Session->setPreference('Addons.Sort', $Sort);
            }
            if ($Checked !== false) {
                $Session->setPreference('Addons.FilterChecked', $Checked);
            }

            $FilterToType = $Session->getPreference('Addons.FilterType', 'all');
            $VanillaVersion = $Session->getPreference('Addons.FilterVanilla', '2');
            $Sort = $Session->getPreference('Addons.Sort', 'recent');
            $Checked = $Session->getPreference('Addons.FilterChecked');
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

        $this->addJsFile('/js/library/jquery.gardenmorepager.js');
        $this->addJsFile('browse.js');

        list($Offset, $Limit) = offsetLimit($Page, c('Garden.Search.PerPage', 20));

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
        $this->setData('Title', $Title);

        $Search = GetIncomingValue('Keywords', '');
        $this->_buildBrowseWheres($Search);

        $SortField = $Sort == 'recent' ? 'DateUpdated' : 'CountDownloads';
        $ResultSet = $this->AddonModel->getWhere(false, $SortField, 'desc', $Limit, $Offset);
        $this->setData('Addons', $ResultSet);
        $this->_buildBrowseWheres($Search);
        $NumResults = $this->AddonModel->getCount(false);
        $this->setData('TotalAddons', $NumResults);

        // Build a pager
        $PagerFactory = new Gdn_PagerFactory();
        $Pager = $PagerFactory->getPager('Pager', $this);
        $Pager->MoreCode = '›';
        $Pager->LessCode = '‹';
        $Pager->ClientID = 'Pager';
        $Pager->configure(
            $Offset,
            $Limit,
            $NumResults,
            'addon/browse/'.$FilterToType.'/'.$Sort.'/'.$this->Version.'/%1$s/?Keywords='.urlencode($Search)
        );
        $this->setData('_Pager', $Pager);

        if ($this->_DeliveryType != DELIVERY_TYPE_ALL) {
            $this->setJson('MoreRow', $Pager->toString('more'));
        }

        $this->addModule('AddonHelpModule');

        $this->render();
    }

    /**
     *
     *
     * @param string $Search
     */
    private function _buildBrowseWheres($Search = '') {
        if ($Search != '') {
            $this->AddonModel
                ->SQL
                ->beginWhereGroup()
                ->like('a.Name', $Search)
                ->orLike('a.Description', $Search)
                ->endWhereGroup();
        }

        if ($this->Version != 0) {
            $this->AddonModel
                ->SQL
                ->where('a.Vanilla2', $this->Version == '1' ? '0' : '1');
        }

        $Ch = array('unchecked' => 0, 'checked' => 1);
        if (isset($Ch[$this->FilterChecked])) {
            $this->AddonModel->SQL->where('a.Checked', $Ch[$this->FilterChecked]);
        }

        if ($Types = $this->Request->get('Types')) {
            $Types = explode(',', $Types);
            foreach ($Types as $Index => $Type) {
                if (isset(AddonModel::$Types[trim($Type)])) {
                    $Types[$Index] = AddonModel::$Types[trim($Type)];
                } else {
                    unset($Types[$Index]);
                }
            }
            $this->AddonModel->SQL->whereIn('a.AddonTypeID', $Types);
        }

        $AddonTypeID = val($this->Filter, AddonModel::$TypesPlural);
        if ($AddonTypeID) {
            $this->AddonModel
                ->SQL
                ->where('a.AddonTypeID', $AddonTypeID);
        }
    }

    /**
     *
     *
     * @param string $AddonID
     * @throws Exception
     * @throws Gdn_UserException
     */
    public function addPicture($AddonID = '') {
        $Session = Gdn::session();
        if (!$Session->isValid()) {
            $this->Form->addError('You must be authenticated in order to use this form.');
        }

        $Addon = $this->AddonModel->getID($AddonID);
        if (!$Addon) {
            throw notFoundException('Addon');
        }

        if ($Session->UserID != $Addon['InsertUserID']) {
            $this->permission('Addons.Addon.Manage');
        }

        $this->addModule('AddonHelpModule', 'Panel');
        $AddonPictureModel = new Gdn_Model('AddonPicture');
        $this->Form->setModel($AddonPictureModel);
        $this->Form->addHidden('AddonID', $AddonID);
        if ($this->Form->authenticatedPostBack() === true) {
            $UploadImage = new Gdn_UploadImage();
            try {
                // Validate the upload
                $TmpImage = $UploadImage->validateUpload('Picture');

                // Generate the target image name
                $TargetImage = $UploadImage->generateTargetName(PATH_UPLOADS, '');
                $ImageBaseName = 'addons/screens/'.pathinfo($TargetImage, PATHINFO_BASENAME);

                // Save the uploaded image in large size
                $ImgParsed = $UploadImage->saveImageAs(
                    $TmpImage,
                    changeBaseName($ImageBaseName, 'ao%s'),
                    700,
                    1000
                );

                // Save the uploaded image in thumbnail size
                $ThumbSize = 150;
                $ThumbParsed = $UploadImage->saveImageAs(
                    $TmpImage,
                    changeBasename($ImageBaseName, 'at%s'),
                    $ThumbSize,
                    $ThumbSize
                );
                $ImageBaseName = sprintf($ImgParsed['SaveFormat'], $ImageBaseName);
            } catch (Exception $ex) {
                $this->Form->addError($ex->getMessage());
            }
            // If there were no errors, insert the picture
            if ($this->Form->errorCount() == 0) {
                $AddonPictureModel = new Gdn_Model('AddonPicture');
                $AddonPictureModel->insert(array('AddonID' => $AddonID, 'File' => $ImageBaseName));
            }

            // If there were no problems, redirect back to the addon
            if ($this->Form->errorCount() == 0) {
                $this->RedirectUrl = url('/addon/'.AddonModel::slug($Addon));
            }
        }
        $this->render();
    }

    /**
     *
     *
     * @param string $AddonPictureID
     * @throws Exception
     * @throws Gdn_UserException
     */
    public function deletePicture($AddonPictureID = '') {
        $AddonPictureModel = new Gdn_Model('AddonPicture');
        $Picture = $AddonPictureModel->getWhere(array('AddonPictureID' => $AddonPictureID))->firstRow();
        $AddonModel = new AddonModel();
        $Addon = $AddonModel->getID($Picture->AddonID);
        $Session = Gdn::session();

        if ($Session->UserID != $Addon['InsertUserID'] && !$Session->checkPermission('Addons.Addon.Manage')) {
            throw permissionException();
        }

        if ($this->Form->authenticatedPostBack() && $this->Form->getFormValue('Yes')) {
            if ($Picture) {
                $Upload = new Gdn_Upload();
                $Upload->delete(changeBasename($Picture->File, 'ao%s'));
                $Upload->delete(changeBasename($Picture->File, 'at%s'));
                $AddonPictureModel->delete(array('AddonPictureID' => $AddonPictureID));
            }
            $this->RedirectUrl = url('/addon/'.$Picture->AddonID);
        }
        $this->render('deleteversion');
    }

    /**
     *
     *
     * @param $IDs
     */
    public function getList($IDs) {
        $IDs = explode(',', $IDs);
        array_map('trim', $IDs);

        $Addons = $this->AddonModel->getIDs($IDs);
        $this->setData('Addons', $Addons);

        $this->render('browse');
    }

    /**
     *
     *
     * @param string $AddonID
     * @throws Exception
     */
    public function icon($AddonID = '') {
        $Session = Gdn::session();
        if (!$Session->isValid()) {
            $this->Form->addError('You must be authenticated in order to use this form.');
        }

        $Addon = $this->AddonModel->getID($AddonID);
        if (!$Addon) {
            throw notFoundException('Addon');
        }

        if ($Session->UserID != $Addon['InsertUserID']) {
            $this->permission('Addons.Addon.Manage');
        }

        $this->addModule('AddonHelpModule', 'Panel');
        $this->Form->setModel($this->AddonModel);
        $this->Form->addHidden('AddonID', $AddonID);
        if ($this->Form->isPostBack()) {
            $UploadImage = new Gdn_UploadImage();
            try {
                // Validate the upload
                $TmpImage = $UploadImage->validateUpload('Icon');

                // Generate the target image name
                $TargetImage = $UploadImage->generateTargetName('addons/icons', '');

                // Save the uploaded icon
                $Parsed = $UploadImage->saveImageAs($TmpImage, $TargetImage, 256, 256, false, false);
                $TargetImage = $Parsed['SaveName'];
            } catch (Exception $ex) {
                $this->Form->addError($ex);
            }
            // If there were no errors, remove the old picture and insert the picture
            if ($this->Form->errorCount() == 0) {
                if ($Addon['Icon']) {
                    $UploadImage->delete($Addon['Icon']);
                }

                $this->AddonModel->save(array('AddonID' => $AddonID, 'Icon' => $TargetImage));
            }

            // If there were no problems, redirect back to the addon
            if ($this->Form->errorCount() == 0) {
                $this->RedirectUrl = Url('/addon/'.AddonModel::slug($Addon));
            }
        }
        $this->render();
    }

    /**
     *
     *
     * @param $Path
     * @return string
     */
    protected function parseReadme($Path) {
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
            $Description = Gdn_Format::markdown($ReadMeContents);
        }

        $FolderPath = substr($Path, 0, -4);
        Gdn_FileSystem::removeFolder($FolderPath);

        return $Description;
    }
}
