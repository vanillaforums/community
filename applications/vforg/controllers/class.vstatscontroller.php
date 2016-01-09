<?php
/**
 *
 *
 * @copyright 2009-2016 Vanilla Forums Inc.
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU GPL v2
 * @package VFOrg
 */

/**
 * Class VStatsController
 */
class VStatsController extends Gdn_Controller {

    /** @var array Objects to preload. */
    public $Uses = array('Database', 'Form');

    /**
     * Before all method calls.
     */
    public function initialize() {
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
     * List download stats.
     *
     * @param bool|false $Offset
     */
    public function index($Offset = false) {
        $this->permission('Garden.Settings.Manage');
        $this->addSideMenu('vstats');
        $this->addJsFile('jquery.gardenmorepager.js');
        $this->title('Vanilla Stats');

        $this->Form->Method = 'get';
        $Offset = is_numeric($Offset) ? $Offset : 0;
        $Limit = 19;

        $this->StatsData = array();
        $Offset--;
        $Year = date('Y');
        $Month = date('m');
        $BaseDate = Gdn_Format::toTimestamp($Year.'-'.str_pad($Month, 2, '0', STR_PAD_LEFT).'-01 00:00:00');
        for ($i = $Offset; $i <= $Limit; ++$i) {
            $String = "-$i month";
            $this->StatsData[] = $this->_getStats(date("Y-m-d 00:00:00", strtotime($String, $BaseDate)));
        }

        $TotalRecords = count($this->StatsData);

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
            'vstats/index/%1$s/'
        );

        // Deliver json data if necessary
        if ($this->_DeliveryType != DELIVERY_TYPE_ALL) {
            $this->setJson('LessRow', $this->Pager->toString('less'));
            $this->setJson('MoreRow', $this->Pager->toString('more'));
        }
        $this->render();
    }

    /**
     * Hark, a model kludged into a controller!
     *
     * @param string $mDay
     * @return array
     */
    private function _getStats($mDay = '') {
        $AssumeToday = $mDay == '';
        if ($AssumeToday) $mDay = date("Y-m-d 00:00:00");

        // Get Vanilla Download Count
        Gdn::sql()
            ->select('AddonID', 'count', 'Count')
            ->from('Download')
            ->where('AddonID', 465); // <---- Vanilla 1's AddonID

        if (!$AssumeToday) {
            Gdn::sql()->where('DateInserted <=', $mDay);
        }

        $OrgData = Gdn::sql()->get();
        $VanillaDownloads = $OrgData->numRows() > 0 ? $OrgData->firstRow()->Count : 0;
        $VanillaDownloads += 311528; // There were 311,528 Vanilla downloads before moving to this new database

        // There were 1,171,794 plugin downloads when we started recording plugin downloads in LUM_Download
        Gdn::sql()
            ->select('d.DownloadID', 'count', 'Count')
            ->from('Download d')
            ->join('Addon a', 'a.AddonID = d.AddonID')
            ->join('AddonType t', 'a.AddonTypeID = t.AddonTypeID')
            ->where('a.AddonID <>', 465); // Don't include Vanilla downloads in addon downloads.
            // ->Where('t.Label <>', 'Application');

        if (!$AssumeToday) {
            Gdn::sql()->where('d.DateInserted <=', $mDay);
        }

        $OrgData = Gdn::sql()->get();
        $AddonDownloads = $OrgData->numRows() > 0 ? $OrgData->firstRow()->Count : 0;
        $AddonDownloads += 1232885; // This was the count when we migrated to garden

        $mDay = substr($mDay, 0, 10).' 00:00:00';

        return array(
            'DateInserted' => $mDay,
            'VanillaDownloads' => $VanillaDownloads,
            'AddonDownloads' => $AddonDownloads
        );
    }
}