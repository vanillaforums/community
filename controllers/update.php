<?php if (!defined('APPLICATION')) exit();

/// <summary>
/// Lussumo Update Controller
/// </summary>
class UpdateController extends VanillaForumsComController {
   public $Uses = array('Database');
   
   /// <summary>
   /// Get the current version of all the requested addons (as referenced by ADDON_TYPE and NAME).
   /// </summary>
   public function Index() {
      // TODO: RECORD THE UPDATE CHECKER SOURCE
      $Check = GetIncomingValue('Check', FALSE);
      if ($Check !== FALSE) {
         $Check = Format::Unserialize($Check);
         if (!is_array($Check)) {
            $Check = FALSE;
         } else {
            $Database = Gdn::Database();
            foreach ($Check as $Type => $Names) {
               if (is_array($Names)) {
                  foreach ($Names as $Name => $Version) {
                     $Database->Select('Version, Name');
                     $Database->From('vw_AddOn');
                     $Database->Where(array('AddOnType' => $Type, 'Name' => $Name));
                     $Data = $Database->Get();
                     $Item = $Data->FirstRow();
                     if ($Item !== FALSE)
                        $Check[$Type][$Item->Name] = $Item->Version;
                  }
               }
            }
            $Check = Format::Serialize($Check);
         }
      }
      
      echo $Check === FALSE ? 'FALSE' : $Check;
   }
   
   public function Find($PluginName = '') {
      // Find the requested plugin and redirect to it...
   }
}