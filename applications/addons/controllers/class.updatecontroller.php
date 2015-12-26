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
 * Class UpdateController
 */
class UpdateController extends AddonsController {

    /** @var array  */
    public $Uses = array('Database');

    /**
     *
     */
    public function index() {
        $Source = GetIncomingValue('source', '');
        $CountUsers = GetIncomingValue('users', '');
        $CountConversations = GetIncomingValue('conversations', '');
        $CountConversationMessages = GetIncomingValue('messages', '');
        $CountDiscussions = GetIncomingValue('discussions', '');
        $CountComments = GetIncomingValue('comments', '');

        $UpdateChecks = json_decode(GetIncomingValue('updateChecks'), true);
        $UpdateCheckID = 0;

        // Get the UpdateCheckSourceID
        $SQL = $this->Database->sql();
        $Data = $SQL->select('SourceID')
            ->from('UpdateCheckSource')
            ->where(array('Location' => $Source))
            ->get()->firstRow();
        $UpdateCheckSourceID = $Data ? $Data->SourceID : 0;
        if ($UpdateCheckSourceID <= 0) {
            $UpdateCheckSourceID = $SQL->insert(
                'UpdateCheckSource',
                array(
                    'Location' => $Source,
                    'DateInserted' => Gdn_Format::toDateTime(),
                    'RemoteIp' => @$_SERVER['REMOTE_ADDR']
                )
            );
        }

        // Assuming the source was saved successfully
        if ($UpdateCheckSourceID > 0) {
            // Record all of the count information
            $UpdateCheckID = $SQL->insert(
                'UpdateCheck',
                array(
                    'SourceID' => $UpdateCheckSourceID,
                    'CountUsers' => intval($CountUsers),
                    'CountDiscussions' => intval($CountDiscussions),
                    'CountComments' => intval($CountComments),
                    'CountConversations' => intval($CountConversations),
                    'CountConversationMessages' => intval($CountConversationMessages),
                    'DateInserted' => Gdn_Format::toDateTime(),
                    'RemoteIp' => Gdn::request()->ipAddress()
                )
            );
        }

        // Define a RequiredUpdates array as a response
        $Response = array();

        // If the the updatechecks argument was a serialized array, parse it to
        // see if we have newer versions
        if (is_array($UpdateChecks)) {
            foreach ($UpdateChecks as $Addon) {
                if (is_array($Addon)) {
                    $Name = val('Name', $Addon, '');
                    $Type = val('Type', $Addon, '');
                    $Version = val('Type', $Addon, '');
                } else {
                    $Name = $Addon->Name;
                    $Type = $Addon->Type;
                    $Version = $Addon->Version;
                }
                $OurAddonID = 0;
                if ($Name != '' && $Type != '' && $Version != '') {
                    // Look for a matching AddonID & get it's current Version
                    $Data = $SQL
                        ->select('a.AddonID, v.Version')
                        ->from('Addon a')
                        ->join('AddonVersion v', 'a.CurrentAddonVersionID = v.AddonVersionID')
                        ->join('AddonType t', 'a.AddonTypeID = t.AddonTypeID')
                        ->where('a.Name', $Name)
                        ->where('t.Label', $Type)
                        ->get()->firstRow();

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

                if ($UpdateCheckID > 0) {
                    // Insert the addon into the UpdateAddon table
                    $UpdateAddonID = $SQL->insert('UpdateAddon', array(
                        'AddonID' => $OurAddonID,
                        'Name' => $Name,
                        'Type' => $Type,
                        'Version' => $Version
                    ));

                    // Insert the relation of this addon to this updatecheck
                    if ($UpdateAddonID > 0) {
                        $SQL->insert('UpdateCheckAddon', array(
                            'UpdateCheckID' => $UpdateCheckID,
                            'UpdateAddonID' => $UpdateAddonID
                        ));
                    }
                }
            }
        }

        // Make sure the database connection is closed before exiting.
        $Database = Gdn::database();
        $Database->closeConnection();

        // Send messages back to the requesting application
        exit(json_encode(array(
            'messages' => '', // <-- These messages must be an array of GDN_Message table rows in associative array format.
            'response' => json_encode($Response)
        )));
    }

    /**
     * Find the requested plugin and redirect to it.
     *
     * @param string $PluginName
     */
    public function find($PluginName = '') {
        $Data = $this->Database->sql()
            ->select('AddonID, Name')
            ->from('Addon')
            ->where('Name', $PluginName)
            ->get()->firstRow();
        if ($Data) {
            safeRedirect('/addon/'.$Data->AddonID.'/'.Gdn_Format::url($Data->Name));
        } else {
            safeRedirect('/addon/notfound/');
        }
    }
}
