<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/

class FeaturesController extends VFOrgController {
   
   public function Index($FeaturePageName = '') {
      $ViewLocation = FALSE;
      try {
         $ViewLocation = $this->FetchViewLocation($FeaturePageName);
      } catch (Exception $e) {
         // nothing
      }
      if ($ViewLocation && $FeaturePageName != '')
         $this->View = $FeaturePageName;
      else
         Redirect('features/embed-vanilla/');


      $this->AddJsFile('jquery.js');
      $this->Head->AddScript('http://vanillaforums.com/applications/vfcom/js/cufon-yui.js');
      $this->Head->AddScript('http://vanillaforums.com/applications/vfcom/js/archer.font.js');
//      $this->Head->AddScript('http://vanillaforums.com/applications/vfcom/js/gothamround.font.js');
      $this->Head->AddString("
<script type=\"text/javascript\">
   Cufon.replace('.Features h1', {
      fontFamily: 'Archer',
      textShadow: '0px 1px 1px #ffffff;'
   });
</script>");
      
      $this->Render();
   }

}