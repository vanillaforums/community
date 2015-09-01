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
 * Class UpdatesController
 */
class UpdatesController extends Gdn_Controller {

    public $Uses = array('Database', 'Form');

    public function Initialize() {
        $this->Head = new HeadModule($this);
        $this->AddJsFile('jquery.js');
        $this->AddJsFile('jquery.form.js');
        $this->AddJsFile('jquery.popup.js');
        $this->AddJsFile('jquery.gardenhandleajaxform.js');
        $this->AddJsFile('global.js');
        $this->AddCssFile('admin.css');
        $this->MasterView = 'admin';
        parent::Initialize();
    }

    public function AddSideMenu($CurrentUrl) {
        // Only add to the assets if this is not a view-only request
        if ($this->_DeliveryType == DELIVERY_TYPE_ALL) {
            $SideMenu = new SideMenuModule($this);
            $SideMenu->HtmlId = '';
            $SideMenu->HighlightRoute($CurrentUrl);
            $this->EventArguments['SideMenu'] = &$SideMenu;
            $this->FireEvent('GetAppSettingsMenuItems');
            $this->AddModule($SideMenu, 'Panel');
        }
    }

    public function Index($Offset = FALSE, $SortField = '') {
        $this->Permission('Garden.Settings.Manage');
        $this->AddSideMenu('updates');
        $this->AddJsFile('jquery.gardenmorepager.js');
        $this->Title('Remote Updates');
        $this->Form->Method = 'get';
        $Limit = 30;
        $SortField = $SortField == 'CountComments' ? 'c.CountComments' : 'c.DateInserted';

        // Input Validation
        $Offset = is_numeric($Offset) ? $Offset : 0;

        $this->UpdateData = $this->Database->SQL()->Query("
select s.Location, s.RemoteIp, c.DateInserted, c.CountUsers, c.CountDiscussions, c.CountComments
from GDN_UpdateCheckSource s
join (select SourceID, max(UpdateCheckID) as UpdateCheckID from GDN_UpdateCheck group by SourceID) mc
    on s.SourceID = mc.SourceID
join GDN_UpdateCheck c
    on mc.UpdateCheckID = c.UpdateCheckID
order by $SortField desc
limit $Offset, $Limit");

        $TotalRecords = $this->Database->SQL()->Select('SourceID', 'count', 'CountSources')->From('UpdateCheckSource')->Get()->FirstRow()->CountSources;

        // Build a pager
        $PagerFactory = new Gdn_PagerFactory();
        $this->Pager = $PagerFactory->GetPager('MorePager', $this);
        $this->Pager->MoreCode = 'More';
        $this->Pager->LessCode = 'Previous';
        $this->Pager->ClientID = 'Pager';
        $this->Pager->Wrapper = '<tr %1$s><td colspan="6">%2$s</td></tr>';
        $this->Pager->Configure(
            $Offset,
            $Limit,
            $TotalRecords,
            'updates/index/%1$s/'.urlencode($SortField)
        );

        // Deliver json data if necessary
        if ($this->_DeliveryType != DELIVERY_TYPE_ALL) {
            $this->SetJson('LessRow', $this->Pager->ToString('less'));
            $this->SetJson('MoreRow', $this->Pager->ToString('more'));
        }

        $this->Render();
    }

}