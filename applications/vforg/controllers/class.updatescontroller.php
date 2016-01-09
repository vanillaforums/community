<?php
/**
 *
 *
 * @copyright 2009-2016 Vanilla Forums Inc.
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU GPL v2
 * @package VFOrg
 */

/**
 * Class UpdatesController
 */
class UpdatesController extends Gdn_Controller {

    /** @var array Objects to preload. */
    public $Uses = array('Database', 'Form');

    /**
     * Before all method calls.
     */
    public function Initialize() {
        $this->Head = new HeadModule($this);
        $this->addJsFile('jquery.js');
        $this->addJsFile('jquery.form.js');
        $this->addJsFile('jquery.popup.js');
        $this->addJsFile('jquery.gardenhandleajaxform.js');
        $this->addJsFile('global.js');
        $this->addCssFile('admin.css');
        $this->MasterView = 'admin';
        parent::initialize();
    }

    /**
     * Setup the Dashboard menu.
     *
     * @param $CurrentUrl
     * @throws Exception
     */
    public function addSideMenu($CurrentUrl) {
        // Only add to the assets if this is not a view-only request
        if ($this->_DeliveryType == DELIVERY_TYPE_ALL) {
            $SideMenu = new SideMenuModule($this);
            $SideMenu->HtmlId = '';
            $SideMenu->highlightRoute($CurrentUrl);
            $this->EventArguments['SideMenu'] = &$SideMenu;
            $this->fireEvent('GetAppSettingsMenuItems');
            $this->addModule($SideMenu, 'Panel');
        }
    }

    /**
     * List all update checks.
     *
     * @param bool|false $Offset
     * @param string $SortField
     */
    public function index($Offset = false, $SortField = '') {
        $this->permission('Garden.Settings.Manage');
        $this->addSideMenu('updates');
        $this->addJsFile('jquery.gardenmorepager.js');
        $this->title('Remote Updates');

        $this->Form->Method = 'get';
        $Limit = 30;
        $SortField = $SortField == 'CountComments' ? 'c.CountComments' : 'c.DateInserted';

        // Input Validation
        $Offset = is_numeric($Offset) ? $Offset : 0;

        // What the actual model in my controller, guy?
        $this->UpdateData = Gdn::sql()->query("
            select s.Location, s.RemoteIp, c.DateInserted, c.CountUsers, c.CountDiscussions, c.CountComments
            from GDN_UpdateCheckSource s
            join (select SourceID, max(UpdateCheckID) as UpdateCheckID from GDN_UpdateCheck group by SourceID) mc
                on s.SourceID = mc.SourceID
            join GDN_UpdateCheck c
                on mc.UpdateCheckID = c.UpdateCheckID
            order by $SortField desc
            limit $Offset, $Limit"
        );

        $TotalRecords = Gdn::sql()
            ->select('SourceID', 'count', 'CountSources')
            ->from('UpdateCheckSource')
            ->get()->firstRow()->CountSources;

        // Build a pager
        $PagerFactory = new Gdn_PagerFactory();
        $this->Pager = $PagerFactory->getPager('MorePager', $this);
        $this->Pager->MoreCode = 'More';
        $this->Pager->LessCode = 'Previous';
        $this->Pager->ClientID = 'Pager';
        $this->Pager->Wrapper = '<tr %1$s><td colspan="6">%2$s</td></tr>';
        $this->Pager->configure(
            $Offset,
            $Limit,
            $TotalRecords,
            'updates/index/%1$s/'.urlencode($SortField)
        );

        // Deliver json data if necessary
        if ($this->_DeliveryType != DELIVERY_TYPE_ALL) {
            $this->setJson('LessRow', $this->Pager->toString('less'));
            $this->setJson('MoreRow', $this->Pager->toString('more'));
        }

        $this->render();
    }
}