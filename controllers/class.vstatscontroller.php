<?php if (!defined('APPLICATION')) exit();

class VStatsController extends Gdn_Controller {
	
   public $Uses = array('Database', 'Form');

   public function Initialize() {
      $this->Head = new HeadModule($this);
      $this->AddJsFile('jquery.js');
      $this->AddJsFile('jquery.livequery.js');
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
	
	public function Index($Offset = FALSE, $Keywords = '') {
		$this->Permission('Vanilla.Forums.Manage');
		$this->AddSideMenu('vstats');
		$this->AddJsFile('jquery.gardenmorepager.js');
		$this->Title('Vanilla Stats');
      $this->Form->Method = 'get';
      $Offset = is_numeric($Offset) ? $Offset : 0;
		$Limit = 9;
		
		$this->StatsData = array();
		$Offset--;
		$Year = date('Y');
		$Month = date('m');
		$BaseDate = Gdn_Format::ToTimestamp($Year.'-'.str_pad($Month, 2, '0', STR_PAD_LEFT).'-01 00:00:00');
		for ($i = $Offset; $i <= $Limit; ++$i) {
			$String = "-$i month";
			$this->StatsData[] = $this->_GetStats(date("Y-m-d 00:00:00", strtotime($String, $BaseDate)));
		}
		
		$TotalRecords = count($this->StatsData);

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
         'vstats/index/%1$s/'
      );
      
      // Deliver json data if necessary
      if ($this->_DeliveryType != DELIVERY_TYPE_ALL) {
         $this->SetJson('LessRow', $this->Pager->ToString('less'));
         $this->SetJson('MoreRow', $this->Pager->ToString('more'));
      }
		$this->Render();
	}
			
	private function _GetStats($mDay = '') {
		$AssumeToday = $mDay == '';
		if ($AssumeToday) $mDay = date("Y-m-d 00:00:00");
			
		// Get Vanilla Download Count
		$this->Database->SQL()->Select('AddonID', 'count', 'Count')->From('Download')->Where('AddonID', 465); // <---- Vanilla 1's AddonID
		if (!$AssumeToday) $this->Database->SQL()->Where('DateInserted <=', $mDay);
		$OrgData = $this->Database->SQL()->Get();
		$VanillaDownloads = $OrgData->NumRows() > 0 ? $OrgData->FirstRow()->Count : 0;
		$VanillaDownloads += 311528; // There were 311,528 Vanilla downloads before moving to this new database
		
		// There were 1,171,794 plugin downloads when we started recording plugin downloads in LUM_Download
		$this->Database->SQL()->Select('d.DownloadID', 'count', 'Count')
			->From('Download d')
			->Join('Addon a', 'a.AddonID = d.AddonID')
			->Join('AddonType t', 'a.AddonTypeID = t.AddonTypeID')
			->Where('t.Label <>', 'Application');
		if (!$AssumeToday) $this->Database->SQL()->Where('d.DateInserted <=', $mDay);
		$OrgData = $this->Database->SQL()->Get();
		$AddonDownloads = $OrgData->NumRows() > 0 ? $OrgData->FirstRow()->Count : 0;
		$AddonDownloads += 1232885; // This was the count when we migrated to garden
		
		$mDay = substr($mDay, 0, 10).' 00:00:00';
		return array(
			'DateInserted' => $mDay,
			'VanillaDownloads' => $VanillaDownloads,
			'AddonDownloads' => $AddonDownloads
		);

	}

}