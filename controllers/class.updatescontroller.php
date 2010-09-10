<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/

class UpdatesController extends Gdn_Controller {
	
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
		$this->AddSideMenu('updates');
		$this->AddJsFile('jquery.gardenmorepager.js');
		$this->Title('Remote Updates');
      $this->Form->Method = 'get';
		$Limit = 30;

      // Input Validation
      $Offset = is_numeric($Offset) ? $Offset : 0;
      if (!$Keywords) {
         $Keywords = $this->Form->GetFormValue('Keywords');
         if ($Keywords)
            $Offset = 0;

      }

      // Put the Keyword back in the form
      if ($Keywords)
         $this->Form->SetFormValue('Keywords', $Keywords);

		$this->UpdateData = $this->Database->SQL()->Query("
select s.Location, s.RemoteIp, s.DateInserted, c.CountUsers, c.CountDiscussions, c.CountComments
from GDN_UpdateCheckSource s
join (select SourceID, max(UpdateCheckID) as UpdateCheckID from GDN_UpdateCheck group by SourceID) mc
	on s.SourceID = mc.SourceID
join GDN_UpdateCheck c
	on mc.UpdateCheckID = c.UpdateCheckID
order by c.CountComments desc
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
         'updates/index/%1$s/'.urlencode($Keywords)
      );
      
      // Deliver json data if necessary
      if ($this->_DeliveryType != DELIVERY_TYPE_ALL) {
         $this->SetJson('LessRow', $this->Pager->ToString('less'));
         $this->SetJson('MoreRow', $this->Pager->ToString('more'));
      }

      $this->Render();
	}

}