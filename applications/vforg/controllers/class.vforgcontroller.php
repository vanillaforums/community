<?php
/**
 *
 *
 * @copyright 2009-2015 Vanilla Forums Inc.
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU GPL v2
 * @package Addons
 * @since 2.0
 */

class VFOrgController extends Gdn_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function Initialize() {
        if ($this->DeliveryType() == DELIVERY_TYPE_ALL) {
            $this->Head = new HeadModule($this);
        }

        $this->AddCssFile('style.css');
        // $this->AddCssFile('vforg.css');
        parent::Initialize();
    }
}