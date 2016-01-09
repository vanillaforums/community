<?php
/**
 *
 *
 * @copyright 2009-2016 Vanilla Forums Inc.
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU GPL v2
 * @package VFOrg
 */

/**
 * Class ContributorsController
 */
class ContributorsController extends VFOrgController {

    /** @var array  */
    public $Uses = array('Form');

    /**
     * Show the contributor agreement form & workflow for signing.
     */
    public function index() {
        if (!Gdn::session()->isValid()) {
            $this->View = 'signin';
        } else {
            if ($this->Form->authenticatedPostBack() && $this->Form->getFormValue('Agree', '') == '1') {
                Gdn::sql()->update('User')
                    ->set('DateContributorAgreement', Gdn_Format::toDateTime(), true, false)
                    ->where('UserID', Gdn::session()->UserID)
                    ->put();
                $this->View = 'done';
            }
        }
        $this->render();
    }

    /**
     * Get list of signed contributors.
     */
    public function signed() {
        $this->UserData = Gdn::sql()
            ->select()
            ->from('User')
            ->where('DateContributorAgreement <>', '')
            ->orderBy('DateContributorAgreement', 'asc')
            ->get();

        $this->render();
    }
}