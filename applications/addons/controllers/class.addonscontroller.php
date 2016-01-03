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

    /** @var int Because of our peculiar discussions list hijacking, this property is required. */
    public $CountCommentsPerPage = 30;

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

        $this->CountCommentsPerPage = c('Vanilla.Comments.PerPage', 30);
    }

    /**
     * Alias /addons to /addon.
     */
    public function index() {
        Gdn::dispatcher()->dispatch('/addon');
    }
}
