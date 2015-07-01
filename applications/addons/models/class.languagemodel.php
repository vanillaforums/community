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

    public function Get($Where = FALSE, $Limit = FALSE, $Offset = FALSE) {
        $this->SQL
            ->Select('l.*')
            ->Select("l.Name, '(', l.Code, ')'", 'concat', 'Label')
            ->From('Language l')
            ->Where('LanguageID <> ', 1);

        if ($Where !== FALSE)
            $this->SQL->Where($Where);

        if ($Limit !== FALSE) {
            if ($Offset == FALSE || $Offset < 0)
                $Offset = 0;

            $this->SQL->Limit($Limit, $Offset);
        }

        return $this->SQL->Get();
    }
}