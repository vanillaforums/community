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
 * Class DiscussionSearchModule
 */
class DiscussionSearchModule extends Gdn_Module {

    public function AssetTarget() {
        $this->_ApplicationFolder = 'vforg';
        return 'Content';
    }

}