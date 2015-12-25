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
 * Class LanguageModel
 */
class LanguageModel extends Gdn_Model {
    public function __construct() {
        parent::__construct('Language');
    }

    public function Get($Where = false, $Limit = false, $Offset = false) {
        $this->SQL
            ->Select('l.*')
            ->Select("l.Name, '(', l.Code, ')'", 'concat', 'Label')
            ->From('Language l')
            ->Where('LanguageID <> ', 1);

        if ($Where !== false) {
            $this->SQL->Where($Where);
        }

        if ($Limit !== false) {
            if ($Offset == false || $Offset < 0) {
                $Offset = 0;
            }

            $this->SQL->Limit($Limit, $Offset);
        }

        return $this->SQL->Get();
    }
}
