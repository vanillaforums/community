<?php
/**
 *
 *
 * @copyright 2009-2016 Vanilla Forums Inc.
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU GPL v2
 * @package Addons
 * @since 2.0
 */

/**
 * Renders the "You should register or sign in" panel box.
 */
class AddonHelpModule extends Gdn_Module {

    /**
     *
     *
     * @return string
     */
    public function assetTarget() {
        return 'Panel';
    }
}
