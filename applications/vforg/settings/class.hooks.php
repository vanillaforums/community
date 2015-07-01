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
 * Class VFOrgHooks
 */
class VFOrgHooks implements Gdn_IPlugin {
    public function UserModel_SessionQuery_Handler($Sender) {
        // Make sure that the VotingPlugin is not active (it messes up sorts)
        if (IsMobile())
            Gdn::PluginManager()->UnRegisterPlugin('VotingPlugin');
    }

    public function Base_Render_Before($Sender) {
        // If on user-facing pages
        if ($Sender->MasterView == 'default' || $Sender->MasterView == '') {
            // and in a mobile browser
            if (IsMobile()) {
                // and not on forum, inbox, profile pages
                if (!in_array(strtolower($Sender->Application), array('vanilla', 'conversations', 'dashboard'))) {
                    // use the main theme instead of mobile
                    $Sender->Theme = C('Garden.Theme');
                    Gdn::PluginManager()->UnRegisterPlugin('MobileThemeHooks');
                }
            }
        }
    }


    public function Base_ConfigError_Handler($Sender, $Args) {
        $Data = $Args['Data'];
        $Backtrace = $Args['Backtrace'];

        // Generate a file to show the error.
        $PathBase = PATH_CONF.'/config_error_'.Gdn_Format::ToDate();
        $Path = $PathBase;
        for($i = 0  ; file_exists($Path.'.php'); $Path = $PathBase.'_'.$i) {
            $i++;
        }
        $Path .= '.php';

        $Lines = array("<?php if (!defined('APPLICATION')) exit(); ?>");
        $Lines[] = '<pre>';
        $Lines[] = 'Error saving config.php on '.Gdn_Format::ToDateTime();

        $Lines[] = '';
        $Lines[] = 'Backtrace:';
        foreach ($Backtrace as $Trace) {
            $Line = '';
            if ($Trace['class'])
                $Line .= "{$Trace['class']}{$Trace['type']}";
            $Line .= "{$Trace['function']}()";
            if ($Trace['file'])
                $Line .= ", {$Trace['file']}, line {$Trace['line']}";
            $Lines[] = $Line;
        }

        $Lines[] = '';
        $Lines[] = 'Data:';
        $Lines[] = print_r($Data, TRUE);

        $Lines[] = '</pre>';

        file_put_contents($Path, implode(PHP_EOL, $Lines));

    }


    public function Base_GetAppSettingsMenuItems_Handler($Sender) {
        $Menu = $Sender->EventArguments['SideMenu'];
        $Menu->AddLink('Site Settings', 'Update Checkers', 'updates/', 'Garden.Settings.Manage');
        $Menu->AddLink('Site Settings', 'Download Summary', 'vstats', 'Garden.Settings.Manage');
    }

    public function Setup() {
        $Database = Gdn::Database();
        $Config = Gdn::Factory(Gdn::AliasConfig);
        $Drop = FALSE;  // Gdn::Config('VFOrg.Version') === FALSE ? TRUE : FALSE;
        $Explicit = TRUE;
        $Validation = new Gdn_Validation(); // This is going to be needed by structure.php to validate permission names
        include(PATH_APPLICATIONS . DS . 'vforg' . DS . 'settings' . DS . 'structure.php');

        $ApplicationInfo = array();
        include(CombinePaths(array(PATH_APPLICATIONS . DS . 'vforg' . DS . 'settings' . DS . 'about.php')));
        $Version = ArrayValue('Version', ArrayValue('VFOrg', $ApplicationInfo, array()), 'Undefined');
        SaveToConfig('VFOrg.Version', $Version);
    }
}
