<?php if (!defined('APPLICATION')) exit();

/// <summary>
/// Update Controller
/// </summary>
class UpdateController extends VanillaForumsOrgController {
   public $Uses = array('Database');
   
   /// <summary>
   /// Get the current version of all the requested addons (as referenced by ADDON_TYPE and NAME).
   /// </summary>
   public function Index() {
      $Source = GetIncomingValue('source', '');
      $CountUsers = GetIncomingValue('users', '');
      $CountConversations = GetIncomingValue('conversations', '');
      $CountConversationMessages = GetIncomingValue('messages', '');
      $CountDiscussions = GetIncomingValue('discussions', '');
      $CountComments = GetIncomingValue('comments', '');
      $UpdateChecks = Format::Unserialize(GetIncomingValue('updateChecks', ''));
      $UpdateCheckID = 0;
      
      // Get the UpdateCheckSourceID
      $SQL = $this->Database->SQL();
      $Data = $SQL->Select('SourceID')
         ->From('UpdateCheckSource')
         ->Where(array('Location' => $Source))
         ->Get()
         ->FirstRow();
      $UpdateCheckSourceID = $Data ? $Data->SourceID : 0;
      if ($UpdateCheckSourceID <= 0)
         $UpdateCheckSourceID = $SQL->Insert('UpdateCheckSource',
            array(
               'Location' => $Source,
               'DateInserted' => Format::ToDateTime(),
               'RemoteIp' => @$_SERVER['REMOTE_ADDR']
            )
         );
         
      // Assuming the source was saved successfully
      if ($UpdateCheckSourceID > 0) {
         // Record all of the count information
         $UpdateCheckID = $SQL->Insert('UpdateCheck',
            array(
               'SourceID' => $UpdateCheckSourceID,
               'CountUsers' => intval($CountUsers),
               'CountDiscussions' => intval($CountDiscussions),
               'CountComments' => intval($CountComments),
               'CountConversations' => intval($CountConversations),
               'CountConversationMessages' => intval($CountConversationMessages),
               'DateInserted' => Format::ToDateTime(),
               'RemoteIp' => @$_SERVER['REMOTE_ADDR']
            )
         );
      }
      
      // Define a RequiredUpdates array as a response
      $Response = array();

      // If the updatecheck instance was saved successfully && the updatechecks
      // argument was a serialize array, parse it to see if we have newer
      // versions
      if ($UpdateCheckID > 0 && is_array($UpdateChecks)) {
         foreach ($UpdateChecks as $Addon) {
            if (is_array($Addon)) {
               $Name = ArrayValue('Name', $Addon, '');
               $Type = ArrayValue('Type', $Addon, '');
               $Version = ArrayValue('Type', $Addon, '');
            } else {
               $Name = $Addon->Name;
               $Type = $Addon->Type;
               $Version = $Addon->Version;
            }
            $OurAddonID = 0;
            if ($Name != '' && $Type != '' && $Version != '') {
               // Look for a matching AddonID & get it's current Version
               $Data = $SQL
                  ->Select('a.AddonID, v.Version')
                  ->From('Addon a')
                  ->Join('AddonType t', 'a.AddonTypeID = t.AddonTypeID')
                  ->Join('AddonVersion v', 'a.CurrentAddonVersionID = v.AddonVersionID')
                  ->Where('a.Name', $Name)
                  ->Get()
                  ->FirstRow();
               
               $OurVersion = $Version;
               if ($Data) {
                  $OurAddonID = $Data->AddonID;
                  $OurVersion = $Data->Version;
               }

               // Compare versions, and add to the response if they don't match
               if ($OurAddonID > 0 && $OurVersion != $Version) {
                  $Response[] = array(
                     'Name' => $Name,
                     'Type' => $Type,
                     'Version' => $OurVersion
                  );
               }
            }
               
            // Insert the addon into the UpdateAddon table
            $UpdateAddonID = $SQL->Insert('UpdateAddon', array(
               'AddonID' => $OurAddonID,
               'Name' => $Name,
               'Type' => $Type,
               'Version' => $Version
            ));
            
            // Insert the relation of this addon to this updatecheck
            if ($UpdateAddonID > 0) {
               $SQL->Insert('UpdateCheckAddon', array(
                  'UpdateCheckID' => $UpdateCheckID,
                  'UpdateAddonID' => $UpdateAddonID
               ));
            }
         }
      }
      
      // Make sure the database connection is closed before exiting.
      $Database = Gdn::Database();
      $Database->CloseConnection();
      exit(json_encode(array(
         'messages' => Format::Serialize(array(
            array(
               'Content' => '<div class="Info">This is a honkin test!</div>',
               'AllowDismiss' => '1',
               'Enabled' => '1',
               'Application' => 'Garden',
               'Controller' => 'Settings',
               'Method' => 'Index',
               'AssetTarget' => 'Content'
            ),
            array(
               'Content' => '<div class="Info">Suck it, Trebek!</div>',
               'AllowDismiss' => '0',
               'Enabled' => '1',
               'Application' => 'Garden',
               'Controller' => 'Base',
               'Method' => '',
               'AssetTarget' => 'Content'
            )
         )), // <-- These messages must be an array of GDN_Message table rows in associative array format.
         'response' => Format::Serialize($Response)
      )));
      
      /*
       The Messages will be inserted into the remote databases GDN_Message table like this:
         'Content' => ArrayValue('Content', $Message, ''),
         'AllowDismiss' => ArrayValue('AllowDismiss', $Message, '1'),
         'Enabled' => ArrayValue('Enabled', $Message, '1'),
         'Application' => ArrayValue('Application', $Message, 'Garden'),
         'Controller' => ArrayValue('Controller', $Message, 'Settings'),
         'AssetTarget' => ArrayValue('AssetTarget', $Message, 'Content'),
      */
   }
   
   public function Find($PluginName = '') {
      // Find the requested plugin and redirect to it...
   }
}