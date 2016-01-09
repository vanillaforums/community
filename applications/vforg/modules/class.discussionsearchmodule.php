<?php
/**
 *
 *
 * @copyright 2009-2016 Vanilla Forums Inc.
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU GPL v2
 * @package VFOrg
 */

/**
 * Class DiscussionSearchModule
 */
class DiscussionSearchModule extends Gdn_Module {

    /**
     * Put the search module at the top of the page.
     *
     * @return string
     */
    public function assetTarget() {
        $this->_ApplicationFolder = 'vforg';
        return 'Content';
    }

}