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
 * Class AddonsHooks
 */
class AddonsHooks implements Gdn_IPlugin {

    /** @var string  */
    private $_EnabledApplication = 'Vanilla';

    /** @var bool  */
    private $_Translations = false;

    /**
     * Hook for discussion prefixes in /discussions.
     */
    public function Base_BeforeDiscussionMeta_Handler($Sender, $Args) {
        if (Gdn::Controller()->ControllerName == 'addoncontroller') {
            return;
        }
        $this->AddonDiscussionPrefix($Args['Discussion']);
    }

    /**
     * Add prefix to the passed controller's discussion names when they are re: an addon.
     *
     * Ex: [AddonName] Discussion original name
     */
    public function AddonDiscussionPrefix($Discussion) {
        $Addon = val('Addon', $Discussion);
        if ($Addon) {
            $Slug = AddonModel::Slug($Addon, false);
            $Url = "/addon/$Slug";
            $AddonName = val('Name', $Addon);
            echo ' '.Wrap(Anchor(Gdn_Format::Html($AddonName), $Url), 'span', array('class' => 'Tag Tag-Addon')).' ';
        }
    }

    /**
     * Write information about addons to the discussion if it is related to an addon.
     *
     * @param $Sender
     */
    public function DiscussionController_BeforeCommentBody_Handler($Sender) {
        $Discussion = val('Object', $Sender->EventArguments);
        $AddonID = val('AddonID', $Discussion);
        if (val('Type', $Sender->EventArguments) == 'Discussion' && is_numeric($AddonID) && $AddonID > 0) {
            $Data = Gdn::Database()->SQL()->Select('Name')->From('Addon')->Where('AddonID', $AddonID)->Get()->FirstRow();
            if ($Data) {
                echo RenderDiscussionAddonWarning($AddonID, $Data->Name, val('DiscussionID', $Discussion));
            }
        }
    }

    /**
     *
     *
     * @param DiscussionsController $Sender
     */
    public function DiscussionModel_AfterAddColumns_Handler($Sender, $Args) {
        AddonModel::JoinAddons($Args['Data'], 'AddonID', array('Name', 'Icon', 'AddonKey', 'AddonTypeID', 'Checked'));
    }

    /**
     *
     *
     * @param $Sender
     */
    public function Base_DiscussionOptions_Handler($Sender) {
         $Discussion = $Sender->EventArguments['Discussion'];
         $LabelString = T('Edit Addon Attachment...');
        if (is_null($Discussion->AddonID)) {
             $LabelString = T('Attach Addon...');
        }

         $Sender->EventArguments['DiscussionOptions'][] = array(
              'Label' => $LabelString,
              'Url' => 'addon/attachtodiscussion/' . $Discussion->DiscussionID,
              'Class' => 'AttachAddonDiscussion Popup');
    }

    /**
     *
     *
     * @param $Sender
     * @param $Args
     */
    public function DiscussionsController_BeforeDiscussionContent_Handler($Sender, $Args) {
        static $AddonModel = null;
        if (!$AddonModel) {
            $AddonModel = new AddonModel();
        }

        $Discussion = $Args['Discussion'];
        $Addon = val('Addon', $Discussion);
        if ($Addon) {
            $Slug = AddonModel::Slug($Addon, false);
            $Url = "/addon/$Slug";
//            if ($Addon['Icon']) {
//                echo Anchor(Img(Gdn_Upload::Url($Addon['Icon'])), $Url, array('class' => 'Addon-Icon Author'));
//            } else {
//                echo Wrap(Anchor('Addon', $Url), 'span', array('class' => 'Tag Tag-Addon'));
//            }
        }
    }

    /**
     * Pass the addonid to the form.
     *
     * @param $Sender
     */
    public function PostController_Render_Before($Sender) {
        $AddonID = GetIncomingValue('AddonID');
        if ($AddonID > 0 && is_object($Sender->Form)) {
            $Sender->Form->AddHidden('AddonID', $AddonID);
        }
    }

    /**
     * Make sure to use the AddonID when saving discussions if present in the url.
     *
     * @param $Sender
     */
    public function DiscussionModel_BeforeSaveDiscussion_Handler($Sender) {
        $AddonID = GetIncomingValue('AddonID');
        if (is_numeric($AddonID) && $AddonID > 0) {
            $FormPostValues = val('FormPostValues', $Sender->EventArguments);
            $FormPostValues['AddonID'] = $AddonID;
            $Sender->EventArguments['FormPostValues'] = $FormPostValues;
        }
    }

    /**
     *
     *
     * @param $Sender
     * @param $Args
     */
    public function DiscussionModel_BeforeNotification_Handler($Sender, $Args) {
        $Discussion = $Args['Discussion'];
        $Activity = $Args['Activity'];

        if (!val('AddonID', $Discussion)) {
            return;
        }

        $AddonModel = new AddonModel();
        $Addon = $AddonModel->GetID($Discussion['AddonID'], DATASET_TYPE_ARRAY);

        if (val('InsertUserID', $Addon) == Gdn::Session()->UserID) {
            return;
        }

        $ActivityModel = $Args['ActivityModel'];
        $Activity['NotifyUserID'] = $Addon['InsertUserID'];
        $Activity['HeadlineFormat'] = '{ActivityUserID,user} asked a <a href="{Url,html}">question</a> about the <a href="{Data.AddonUrl,exurl}">{Data.AddonName,html}</a> addon.';
        $Activity['Data']['AddonName'] = $Addon['Name'];
        $Activity['Data']['AddonUrl'] = '/addon/'.urlencode(AddonModel::Slug($Addon, false));

        $ActivityModel->Queue($Activity, 'AddonComment');
    }

    /**
     * Make sure that all translations are in the GDN_Translation table for the "source" language.
     *
     * @param $Sender
     */
    public function Gdn_Locale_BeforeTranslate_Handler($Sender) {
        $Code = ArrayValue('Code', $Sender->EventArguments, '');
        if ($Code != '' && !in_array($Code, $this->GetTranslations())) {
            $Session = Gdn::Session();
            // If the code wasn't in the source list, insert it
            $Database = Gdn::Database();
            $Database->SQL()->Replace('Translation', array(
                'Value' => $Code,
                'UserLanguageID' => 1,
                'Application' => $this->_EnabledApplication(),
                'InsertUserID' => $Session->UserID,
                'DateInserted' => Gdn_Format::ToDateTime(),
                'UpdateUserID' => $Session->UserID,
                'DateUpdated' => Gdn_Format::ToDateTime()
                ), array('Value' => $Code));
        }
    }

    /**
     *
     *
     * @param $Sender
     */
    public function Gdn_Dispatcher_AfterEnabledApplication_Handler($Sender) {
        $this->_EnabledApplication = ArrayValue('EnabledApplication', $Sender->EventArguments, 'Vanilla'); // Defaults to "Vanilla"
    }

    /**
     *
     *
     * @return mixed
     */
    private function _EnabledApplication() {
        return $this->_EnabledApplication;
    }

    /**
     *
     *
     * @return array
     */
    private function GetTranslations() {
        if (!is_array($this->_Translations)) {
            $TranslationModel = new Gdn_Model('Translation');
            $Translations = $TranslationModel->GetWhere(array('UserLanguageID' => 1));
            $this->_Translations = array();
            foreach ($Translations as $Translation) {
                $this->_Translations[] = $Translation->Value;
            }
        }
        return $this->_Translations;
    }

    /**
     * @param $Sender
     */
    public function ProfileController_AfterPreferencesDefined_Handler($Sender) {
        $Sender->Preferences['Notifications']['Popup.AddonComment'] = T('Notify me when people comment on my addons.');
        $Sender->Preferences['Notifications']['Email.AddonComment'] = T('Notify me when people comment on my addons.');
    }

    /**
     * Adds 'Addons' tab to profiles and adds CSS & JS files to their head.
     *
     * @since 2.0.0
     * @package Vanilla
     *
     * @param object $Sender ProfileController.
     */
    public function ProfileController_AddProfileTabs_Handler($Sender) {
        if (is_object($Sender->User) && $Sender->User->UserID > 0) {
            $Sender->AddProfileTab(T('Addons'), 'profile/addons/'.$Sender->User->UserID.'/'.urlencode($Sender->User->Name));
            // Add the discussion tab's CSS and Javascript
            $Sender->AddCssFile('profile.css', 'addons');
            $Sender->AddJsFile('addons.js');
        }
    }
    /**
     * Creates addons tab ProfileController.
     *
     * @since 2.0.0
     * @package Vanilla
     *
     * @param object $Sender ProfileController.
     */
    public function ProfileController_Addons_Create($Sender) {
        $UserReference = ArrayValue(0, $Sender->RequestArgs, '');
        $Username = ArrayValue(1, $Sender->RequestArgs, '');
        // $Offset = ArrayValue(2, $Sender->RequestArgs, 0);
        // Tell the ProfileController what tab to load
        $Sender->GetUserInfo($UserReference, $Username);
        $Sender->SetTabView('Addons', 'Profile', 'Addon', 'Addons');

        // Load the data for the requested tab.
        // if (!is_numeric($Offset) || $Offset < 0)
        //    $Offset = 0;

        $Offset = 0;
        $Limit = 100;
        $AddonModel = new AddonModel();
        $ResultSet = $AddonModel->GetWhere(array('UserID' => $Sender->User->UserID), 'DateUpdated', 'desc', $Limit, $Offset);
        $Sender->SetData('Addons', $ResultSet);
        $NumResults = $AddonModel->GetCount(array('InsertUserID' => $Sender->User->UserID));

        // Set the HandlerType back to normal on the profilecontroller so that it fetches it's own views
        $Sender->HandlerType = HANDLER_TYPE_NORMAL;

        // Render the ProfileController
        $Sender->Render();
    }

    /**
     *
     */
    public function Setup() {
        $Database = Gdn::Database();
        $Config = Gdn::Factory(Gdn::AliasConfig);
        $Drop = C('Addons.Version') === false ? true : false;
        $Explicit = true;
        $Validation = new Gdn_Validation(); // This is going to be needed by structure.php to validate permission names
        include(PATH_APPLICATIONS . DS . 'addons' . DS . 'settings' . DS . 'structure.php');

        $ApplicationInfo = array();
        include(CombinePaths(array(PATH_APPLICATIONS . DS . 'addons' . DS . 'settings' . DS . 'about.php')));
        $Version = ArrayValue('Version', ArrayValue('Addons', $ApplicationInfo, array()), 'Undefined');
        SaveToConfig('Addons.Version', $Version);
    }
}

if (!function_exists('RenderDiscussionAddonWarning')) {
    /**
     *
     *
     * @param $AddonID
     * @param $AddonName
     * @param $AttachID
     * @return string
     */
    function RenderDiscussionAddonWarning($AddonID, $AddonName, $AttachID) {
        $DeleteOption = '';
        if (Gdn::Session()->CheckPermission('Addons.Addon.Manage')) {
            $DeleteOption = Anchor(
                'x',
                'addon/detachfromdiscussion/' . $AttachID,
                array('class' => 'Dismiss')
            );
        }
        $String = Wrap(
            $DeleteOption .
            sprintf(
                T('This discussion is related to the %s addon.'),
                Anchor(
                    $AddonName,
                    'addon/' . $AddonID . '/' . Gdn_Format::Url($AddonName)
                )
            ),
            'div',
            array('class' => 'Warning AddonAttachment DismissMessage')
        );
        return $String;
    }
}
