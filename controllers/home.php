<?php if (!defined('APPLICATION')) exit();

class HomeController extends VFOrgController {
   
   public function Index() {
      $this->AddCssFile('splash.css');
      $this->Render();
   }
   
   public function Hosting() {
      $this->Render();
   }
   
   public function Download() {
      $this->Render();
   }
   
}