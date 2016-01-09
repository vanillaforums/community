<?php
/**
 *
 *
 * @copyright 2009-2015 Vanilla Forums Inc.
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU GPL v2
 * @package VFOrg
 * @since 2.0
 */

/**
 * Class VFOrgController
 */
class VFOrgController extends Gdn_Controller {

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
}