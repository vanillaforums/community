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
 * Class AddonsController
 */
class AddonsController extends Gdn_Controller {

    /**
     * Do this before anything else.
     */
    public function initialize() {
        parent::initialize();
        if ($this->deliveryType() == DELIVERY_TYPE_ALL) {
            $this->Head = new HeadModule($this);
        }

        $this->addCssFile('style.css');
        $this->addCssFile('addons.css');
    }

    /**
     * Alias /addons to /addon.
     */
    public function index() {
        Gdn::dispatcher()->dispatch('/addon');
    }
}
