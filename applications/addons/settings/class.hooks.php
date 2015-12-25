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

    /**
     * Hook for discussion prefixes in /discussions.
     */
    public function base_beforeDiscussionMeta_handler($Sender, $Args) {
        if (Gdn::controller()->ControllerName == 'addoncontroller') {
            return;
        }
        $this->addonDiscussionPrefix($Args['Discussion']);
    }

    /**
     * Add prefix to the passed controller's discussion names when they are re: an addon.
     *
     * Ex: [AddonName] Discussion original name
     */
    public function addonDiscussionPrefix($Discussion) {
        $Addon = val('Addon', $Discussion);
        if ($Addon) {
            $Slug = AddonModel::slug($Addon, false);
            $Url = "/addon/$Slug";
            $AddonName = val('Name', $Addon);
            echo ' '.wrap(anchor(Gdn_Format::html($AddonName), $Url), 'span', array('class' => 'Tag Tag-Addon')).' ';
        }
    }

    /**
     * Write information about addons to the discussion if it is related to an addon.
     *
     * @param $Sender
     */
    public function discussionController_beforeCommentBody_handler($Sender) {
        $Discussion = val('Object', $Sender->EventArguments);
        $AddonID = val('AddonID', $Discussion);
        if (val('Type', $Sender->EventArguments) == 'Discussion' && is_numeric($AddonID) && $AddonID > 0) {
            $Data = Gdn::database()->sql()->select('Name')->from('Addon')->where('AddonID', $AddonID)->get()->firstRow();
            if ($Data) {
                echo renderDiscussionAddonWarning($AddonID, $Data->Name, val('DiscussionID', $Discussion));
            }
        }
    }

    /**
     *
     *
     * @param DiscussionsController $Sender
     */
    public function discussionModel_afterAddColumns_handler($Sender, $Args) {
        AddonModel::joinAddons($Args['Data'], 'AddonID', array('Name', 'Icon', 'AddonKey', 'AddonTypeID', 'Checked'));
    }

    /**
     *
     *
     * @param $Sender
     */
    public function base_discussionOptions_handler($Sender) {
        $Discussion = $Sender->EventArguments['Discussion'];
        $LabelString = t('Edit Addon Attachment...');
        if (is_null($Discussion->AddonID)) {
             $LabelString = t('Attach Addon...');
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
    /*public function discussionsController_beforeDiscussionContent_handler($Sender, $Args) {
        static $AddonModel = null;
        if (!$AddonModel) {
            $AddonModel = new AddonModel();
        }

        $Discussion = $Args['Discussion'];
        $Addon = val('Addon', $Discussion);
        if ($Addon) {
            $Slug = AddonModel::slug($Addon, false);
            $Url = "/addon/$Slug";
//            if ($Addon['Icon']) {
//                echo Anchor(Img(Gdn_Upload::Url($Addon['Icon'])), $Url, array('class' => 'Addon-Icon Author'));
//            } else {
//                echo Wrap(Anchor('Addon', $Url), 'span', array('class' => 'Tag Tag-Addon'));
//            }
        }
    }*/

    /**
     * Pass the addonid to the form.
     *
     * @param $Sender
     */
    public function postController_render_before($Sender) {
        $AddonID = GetIncomingValue('AddonID');
        if ($AddonID > 0 && is_object($Sender->Form)) {
            $Sender->Form->addHidden('AddonID', $AddonID);
        }
    }

    /**
     * Make sure to use the AddonID when saving discussions if present in the url.
     *
     * @param $Sender
     */
    public function discussionModel_beforeSaveDiscussion_handler($Sender) {
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
    public function discussionModel_beforeNotification_handler($Sender, $Args) {
        $Discussion = $Args['Discussion'];
        $Activity = $Args['Activity'];

        if (!val('AddonID', $Discussion)) {
            return;
        }

        $AddonModel = new AddonModel();
        $Addon = $AddonModel->getID($Discussion['AddonID'], DATASET_TYPE_ARRAY);

        if (val('InsertUserID', $Addon) == Gdn::session()->UserID) {
            return;
        }

        $ActivityModel = $Args['ActivityModel'];
        $Activity['NotifyUserID'] = $Addon['InsertUserID'];
        $Activity['HeadlineFormat'] = '{ActivityUserID,user} asked a <a href="{Url,html}">question</a> about the <a href="{Data.AddonUrl,exurl}">{Data.AddonName,html}</a> addon.';
        $Activity['Data']['AddonName'] = $Addon['Name'];
        $Activity['Data']['AddonUrl'] = '/addon/'.urlencode(AddonModel::slug($Addon, false));

        $ActivityModel->queue($Activity, 'AddonComment');
    }

    /**
     * @param $Sender
     */
    public function profileController_afterPreferencesDefined_handler($Sender) {
        $Sender->Preferences['Notifications']['Popup.AddonComment'] = t('Notify me when people comment on my addons.');
        $Sender->Preferences['Notifications']['Email.AddonComment'] = t('Notify me when people comment on my addons.');
    }

    /**
     * Adds 'Addons' tab to profiles and adds CSS & JS files to their head.
     *
     * @since 2.0.0
     * @package Vanilla
     *
     * @param object $Sender ProfileController.
     */
    public function profileController_addProfileTabs_handler($Sender) {
        if (is_object($Sender->User) && $Sender->User->UserID > 0) {
            $Sender->addProfileTab(t('Addons'), 'profile/addons/'.$Sender->User->UserID.'/'.urlencode($Sender->User->Name));
            // Add the discussion tab's CSS and Javascript
            $Sender->addCssFile('profile.css', 'addons');
            $Sender->addJsFile('addons.js');
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
    public function profileController_addons_create($Sender) {
        $UserReference = val(0, $Sender->RequestArgs, '');
        $Username = val(1, $Sender->RequestArgs, '');

        // Tell the ProfileController what tab to load
        $Sender->getUserInfo($UserReference, $Username);
        $Sender->setTabView('Addons', 'Profile', 'Addon', 'Addons');

        $Offset = 0;
        $Limit = 100;
        $AddonModel = new AddonModel();
        $ResultSet = $AddonModel->getWhere(array('UserID' => $Sender->User->UserID), 'DateUpdated', 'desc', $Limit, $Offset);
        $Sender->setData('Addons', $ResultSet);
        $NumResults = $AddonModel->getCount(array('InsertUserID' => $Sender->User->UserID));

        // Set the HandlerType back to normal on the profilecontroller so that it fetches it's own views
        $Sender->HandlerType = HANDLER_TYPE_NORMAL;

        // Render the ProfileController
        $Sender->render();
    }

    /**
     * Runs once on enable.
     */
    public function setup() {
        $this->structure();
    }

    /**
     * Runs on /utility/update.
     */
    public function structure() {
        include(PATH_APPLICATIONS . DS . 'addons' . DS . 'settings' . DS . 'structure.php');
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
    function renderDiscussionAddonWarning($AddonID, $AddonName, $AttachID) {
        $DeleteOption = '';
        if (Gdn::session()->checkPermission('Addons.Addon.Manage')) {
            $DeleteOption = anchor(
                'x',
                'addon/detachfromdiscussion/' . $AttachID,
                array('class' => 'Dismiss')
            );
        }
        $String = wrap(
            $DeleteOption .
            sprintf(
                t('This discussion is related to the %s addon.'),
                anchor(
                    $AddonName,
                    'addon/' . $AddonID . '/' . Gdn_Format::url($AddonName)
                )
            ),
            'div',
            array('class' => 'Warning AddonAttachment DismissMessage')
        );
        return $String;
    }
}
