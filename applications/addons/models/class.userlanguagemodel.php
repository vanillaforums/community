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
 * Class UserLanguageModel
 */
class UserLanguageModel extends Gdn_Model {

    /**
     * UserLanguageModel constructor.
     */
    public function __construct() {
        parent::__construct('UserLanguage');
    }

    /**
     *
     */
    public function UserLanguageQuery() {
        $this->SQL
            ->Select('ul.*,l.Name,l.Code')
            ->Select('ul.UserID', '', 'InsertUserID')
            ->Select('iu.Name', '', 'InsertName')
            ->From('UserLanguage ul')
            ->Join('Language l', 'ul.LanguageID = l.LanguageID')
            ->Join('User iu', 'ul.UserID = iu.UserID')
            ->Where('ul.UserLanguageID <>', '1');
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
        $this->UserLanguageQuery();

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

    /**
     *
     *
     * @param string $Wheres
     * @return mixed
     */
    public function GetCount($Wheres = '') {
        if (!is_array($Wheres)) {
            $Wheres = array();
        }

        return $this->SQL
            ->Select('ul.UserLanguageID', 'count', 'CountUserLanguages')
            ->From('UserLanguage ul')
            ->Where($Wheres)
            ->Get()
            ->FirstRow()
            ->CountUserLanguages;
    }

    /**
     *
     *
     * @param mixed $UserLanguageID
     * @param string $Wheres
     * @return array|bool|stdClass
     */
    public function GetID($UserLanguageID, $Wheres = '') {
        $this->UserLanguageQuery();
        if (is_array($Wheres)) {
            $this->SQL->Where($Wheres);
        }

        return $this->SQL
            ->Where('ul.UserLanguageID', $UserLanguageID)
            ->Get()
            ->FirstRow();
    }

    /**
     *
     *
     * @param array $FormPostValues
     * @return bool|int|string
     */
    public function Save($FormPostValues) {
        $Session = Gdn::Session();
        $FormPostValues['UserID'] = $Session->UserID;

        // Define the primary key in this model's table.
        $this->DefineSchema();

        // Get the ID from the form so we know if we are inserting or updating.
        $UserLanguageID = ArrayValue('UserLanguageID', $FormPostValues, '');
        $Insert = $UserLanguageID == '' ? true : false;

        if ($Insert) {
            unset($FormPostValues['UserLanguageID']);
            $this->AddInsertFields($FormPostValues);
        } else {
            $this->AddUpdateFields($FormPostValues);
        }
        // Validate the form posted values
        if ($this->Validate($FormPostValues, $Insert)) {
            $Fields = $this->Validation->SchemaValidationFields(); // All fields on the form that relate to the schema
            $AddonID = intval(ArrayValue('UserLanguageID', $Fields, 0));
            $Fields = RemoveKeyFromArray($Fields, 'UserLanguageID'); // Remove the primary key from the fields for saving
            $UserLanguage = false;
            $Activity = 'EditUserLanguage';
            if ($UserLanguageID > 0) {
                $this->SQL->Put($this->Name, $Fields, array($this->PrimaryKey => $UserLanguageID));
            } else {
                $UserLanguageID = $this->SQL->Insert($this->Name, $Fields);
                $Activity = 'AddUserLanguage';
            }

            if ($UserLanguageID > 0) {
                $UserLanguage = $this->GetID($UserLanguageID);

                // Record Activity
                AddActivity(
                    $Session->UserID,
                    $Activity,
                    '',
                    '',
                    '/translation/'.$UserLanguageID.'/'
                );
            }
        }
        if (!is_numeric($UserLanguageID)) {
            $UserLanguageID = false;
        }

        return count($this->ValidationResults()) > 0 ? false : $UserLanguageID;
    }

    /**
     *
     *
     * @param int $UserLanguageID
     * @param string $Property
     * @param bool $Value
     * @throws Exception
     */
    public function SetProperty($UserLanguageID, $Property, $Value) {
        $Operator = false;
        if (strlen($Value) > 1) {
            $Operator = substr($Value, 0, 1);
            if (in_array($Operator, array('+', '-'))) {
                $Value = substr($Value, 1);
            } else {
                $Operator = false;
            }
        }
        $this->SQL->Update('Addon');
        if ($Operator !== false) {
            $this->SQL->Set($Property, $Property . ' ' . $Operator . ' ' . $Value);
        } else {
            $this->SQL->Set($Property, $Value);
        }

        $this->SQL->Where('UserLanguageID', $UserLanguageID)->Put();
    }
}
