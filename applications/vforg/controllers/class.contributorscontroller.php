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
 * Class ContributorsController
 */
class ContributorsController extends VFOrgController {

    public $Uses = array('Form', 'Database');

    public function Initialize() {
        parent::Initialize();
    }

    public function Index() {
        $Session = Gdn::Session();
        if (!$Session->IsValid()) {
            $this->View = 'signin';
        } else {
            if ($this->Form->AuthenticatedPostBack() && $this->Form->GetFormValue('Agree', '') == '1') {
                $this->Database->Structure()->Table('User')
                    ->Column('DateContributorAgreement', 'datetime', TRUE)
                    ->Set(FALSE, FALSE);
                $this->Database->SQL()->Update('User')->Set('DateContributorAgreement', Gdn_Format::ToDateTime(), TRUE, FALSE)->Where('UserID', $Session->UserID)->Put();
                $this->View = 'done';
            }
        }
        $this->Render();
    }

    public function Signed() {
        $this->UserData = $this->Database->SQL()->Select()->From('User')->Where('DateContributorAgreement <>', '')->OrderBy('DateContributorAgreement', 'asc')->Get();
        $this->Render();
    }
}