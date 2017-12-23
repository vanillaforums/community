<?php
/**
 *
 *
 * @copyright 2009-2016 Vanilla Forums Inc.
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU GPL v2
 * @package VFOrg
 */

/**
 * Class DownloadController
 */
class DownloadController extends Gdn_Controller {

    /**
     * Before every controller method call.
     */
    public function initialize() {
        if ($this->deliveryType() == DELIVERY_TYPE_ALL) {
            $this->Head = new HeadModule($this);
        }
        $this->addCssFile('style.css');
        parent::initialize();
    }

    /**
     * DIY vs Cloud page.
     */
    public function index() {
        $this->render();
    }
}