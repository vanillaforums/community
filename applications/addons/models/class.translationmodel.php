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
 * Class TranslationModel
 */
class TranslationModel extends Gdn_Model {

    /**
     * TranslationModel constructor.
     */
    public function __construct() {
        parent::__construct('Translation');
    }

    /**
     *
     *
     * @param bool|false $Where
     * @param bool|false $Limit
     * @param bool|false $Offset
     * @return Gdn_DataSet
     * @throws Exception
     */
    public function Get($Where = false, $Limit = false, $Offset = false) {
        $this->SQL
            ->Select('s.TranslationID', '', 'SourceTranslationID')
            ->Select('s.Value', '', 'SourceValue')
            ->Select('t.TranslationID, t.Value')
            ->From('Translation s')
            ->Join('Translation t', 't.SourceTranslationID = s.TranslationID', 'left');

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
