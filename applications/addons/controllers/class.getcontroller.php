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
 *
 */
class GetController extends AddonsController {

    /** @var array  */
    public $Uses = array('Form', 'Database', 'AddonModel');

    /**
     *
     *
     * @param string $ID
     * @param string $ServeFile
     */
    public function Index($ID = '', $ServeFile = '0') {
        $this->AddJsFile('jquery.js');

        // Define the item being downloaded
        if (strtolower($ID) == 'vanilla') {
            $ID = 'vanilla-core';
        }

        $UrlFilename = Gdn::Request()->Filename();
        $PathInfo = pathinfo($UrlFilename);

        $Ext = val('extension', $PathInfo);
        if ($Ext == 'zip') {
            $ServeFile = '1';
            $ID = $Ext = val('filename', $PathInfo);
        }

        // Find the requested addon
        $this->Addon = $this->AddonModel->GetSlug($ID, true);
        $this->SetData('Addon', $this->Addon);

        if (!is_array($this->Addon) || !val('File', $this->Addon)) {
            $this->Addon = array(
                'Name' => 'Not Found',
                'Version' => 'undefined',
                'File' => '');
        } else {
            $AddonID = $this->Addon['AddonID'];
            if ($ServeFile != '1') {
                $this->AddJsFile('get.js');
            }

            if ($ServeFile == '1') {
                // Record this download
                $this->Database->SQL()->Insert('Download', array(
                    'AddonID' => $AddonID,
                    'DateInserted' => Gdn_Format::ToDateTime(),
                    'RemoteIp' => @$_SERVER['REMOTE_ADDR']
                ));
                $this->AddonModel->SetProperty($AddonID, 'CountDownloads', $this->Addon['CountDownloads'] + 1);

                if (val('Slug', $this->Addon)) {
                    $Filename = $this->Addon['Slug'];
                } else {
                    $Filename = "{$this->Addon['Name']}-{$this->Addon['Version']}";
                }

                $Filename = Gdn_Format::Url($Filename).'.zip';


                $File = $this->Addon['File'];
                $Url = Gdn_Upload::Url($File);
                Gdn_FileSystem::ServeFile($Url, $Filename);
            }
        }

        $this->AddModule('AddonHelpModule');
        $this->Render();
    }
}
