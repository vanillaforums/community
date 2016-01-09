<?php
/**
 *
 *
 * @copyright 2009-2016 Vanilla Forums Inc.
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU GPL v2
 * @package VFOrg
 */

/**
 * Class DownloadHelpModule
 */
class DownloadHelpModule extends Gdn_Module {

    /**
     * Add download help to the page.
     *
     * @return string
     */
    public function assetTarget() {
        return 'Panel';
    }
}