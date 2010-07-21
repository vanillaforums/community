<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/

class HomeController extends VFOrgController {
   
   public function Index() {
      // If the user does not have an active session, or they have not yet viewed the "chooser", redirect them to it.
      /*
      $Session = Gdn::Session();
      $ViewedChooser = ArrayValue('VanillaViewedChooser', $_COOKIE);
      if (!$Session->IsValid() && !$ViewedChooser)
         Redirect('/choose');
      */
      
      $this->AddJsFile('jquery.js');
      $this->AddJsFile('home.js');
      $this->Head->AddScript('http://vanillaforums.com/applications/vfcom/js/cufon-yui.js');
      $this->Head->AddScript('http://vanillaforums.com/applications/vfcom/js/archer.font.js');
      $this->Head->AddScript('http://vanillaforums.com/applications/vfcom/js/gothamround.font.js');
      $this->Head->AddString("
<script type=\"text/javascript\">
   Cufon.replace('h2', {
      fontFamily: 'Archer',
      textShadow: '0px 1px 1px #ffffff;'
   });
   Cufon.replace('a.Get strong', {
      fontFamily: 'Archer',
      textShadow: '0px 1px 1px #00007e;'
   });
</script>");      
      
      $this->Render();
   }
   
   public function Hosting() {
      $this->Render();
   }
   
   public function Get() {
      $this->Render();
   }
   
   public function Download() {
      Redirect('download');
   }
   
   public function Splash() {
      /*
      $this->MasterView = 'splash';
      $this->ClearCssFiles();
      $this->AddCssFile('splash.css');
      */
      $this->Render();
   }

/*
   public function Choose() {
      $this->Title('Vanilla Forums - Free Forum Software');
      setcookie('VanillaViewedChooser', 'TRUE', time()+60*60*24*300, C('Garden.Cookie.Path'), C('Garden.Cookie.Domain')); // Expire in 300 days
      $this->MasterView = 'choose';
      $this->ClearCssFiles();
      $this->AddCssFile('choose.css');
      $this->Render();
   }
*/   
}