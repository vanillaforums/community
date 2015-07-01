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
 * Class FeaturesController
 */
class FeaturesController extends VFOrgController {

    public function Index($FeaturePageName = '') {
        $ViewLocation = FALSE;

        $Redirects = array(
//             'embed-vanilla' => 'http://vanillaforums.com/features',
             'mobile' => 'http://vanillaforums.com/features/mobile',
             'social-connect' => 'http://vanillaforums.com/features/social-media',
             'themes' => 'http://vanillaforums.com/features/custom-theme',
             'banner' => 'http://vanillaforums.com/features/user-experience',
             'file-upload' => 'http://vanillaforums.com/features/user-experience',
             'import-tool' => 'http://vanillaforums.com/resources/migration',
             'vanilla-connect' => 'http://vanillaforums.com/features/single-sign-on'
        );

        if (isset($Redirects[$FeaturePageName]))
            Redirect($Redirects[$FeaturePageName]);
        else
            Redirect('http://vanillaforums.com/features');


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

        $this->Render();
    }

}