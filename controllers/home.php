<?php if (!defined('APPLICATION')) exit();

class HomeController extends VFOrgController {
   
   public function Index() {
      $this->AddJsFile('js/library/jquery.js');
      $this->AddJsFile('home.js');
      $this->Render();
   }
   
   public function Hosting() {
      $this->Render();
   }
   
   public function Download() {
      $this->Render();
   }
   
}