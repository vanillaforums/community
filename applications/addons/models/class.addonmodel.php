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
 * Class AddonModel
 */
class AddonModel extends Gdn_Model {

    /** @var array  */
    public static $Types = array(
        'plugin' => ADDON_TYPE_PLUGIN,
        'theme' => ADDON_TYPE_THEME,
        'locale' => ADDON_TYPE_LOCALE,
        'application' => ADDON_TYPE_APPLICATION,
        'core' => ADDON_TYPE_CORE
    );

    /** @var array  */
    public static $TypesPlural = array(
        'plugins' => ADDON_TYPE_PLUGIN,
        'themes' => ADDON_TYPE_THEME,
        'locales' => ADDON_TYPE_LOCALE,
        'applications' => ADDON_TYPE_APPLICATION,
        'core' => ADDON_TYPE_CORE
    );

    /** @var array  */
    protected $_AddonCache = array();

    /**
     * Let's sync with the Gdn_Addon table.
     */
    public function __construct() {
        parent::__construct('Addon');
    }

    /**
     * Generic addon retrieval query setup.
     *
     * @param bool|false $VersionSlug
     * @throws Exception
     */
    public function addonQuery($VersionSlug = false) {
        $this->SQL
            ->select('a.*')
            ->select('t.Label', '', 'Type')
            ->select('v.AddonVersionID, v.File, v.Version, v.DateReviewed, v.TestedWith, v.MD5, v.FileSize')
            ->select('v.DateInserted', '', 'DateUploaded')
            ->select('iu.Name', '', 'InsertName')
            ->from('Addon a')
            ->join('AddonType t', 'a.AddonTypeID = t.AddonTypeID')
            ->join('User iu', 'a.InsertUserID = iu.UserID')
            ->where('a.Visible', '1');

        if (!$VersionSlug) {
            // Join in the current addon version.
            $this->SQL->join('AddonVersion v', 'a.CurrentAddonVersionID = v.AddonVersionID', 'left');
        } else {
            // Join in the version based on the slug.
            if (is_int($VersionSlug)) {
                $On = $this->SQL->conditionExpr('v.AddonVersionID', $VersionSlug);
            } else {
                $On = 'v.Deleted = 0 and a.AddonID = v.AddonID and '.$this->SQL->conditionExpr('v.Version', $VersionSlug);
            }

            $this->SQL->join('AddonVersion v', $On, 'left');
        }
    }

    /**
     *
     *
     * @param $Data
     * @param string $Field
     * @param array $Columns
     * @throws
     * @throws Exception
     */
    public static function joinAddons(&$Data, $Field = 'AddonID', $Columns = array('Name')) {
        $Columns = array_merge(array('table' => 'Addon', 'column' => 'Addon'), $Columns);
        Gdn_DataSet::join($Data, $Columns, array('unique' => true));
    }

    /**
     *
     *
     * @param $Addon
     * @param bool|true $IncludeVersion
     * @return string
     */
    public static function slug($Addon, $IncludeVersion = true) {
        if (val('AddonKey', $Addon) && (val('Version', $Addon) || !$IncludeVersion)) {
            $Key = val('AddonKey', $Addon);
            $Type = val('Type', $Addon);
            if (!$Type) {
                $Type = val(val('AddonTypeID', $Addon), array_flip(self::$Types));
            }

            //$Slug = strtolower(val('AddonKey', $Data).'-'.val('Type', $Data).'-'.val('Version', $Data));
            $Slug = strtolower($Key).'-'.strtolower($Type);
            if ($IncludeVersion === true) {
                $Slug .= '-'.val('Version', $Addon, '');
            } elseif (is_string($IncludeVersion)) {
                $Slug .= '-'.$IncludeVersion;
            } elseif (is_array($IncludeVersion)) {
                $Slug .= '-'.$IncludeVersion['Version'];
            }
            return urlencode($Slug);
        } else {
            return val('AddonID', $Addon).'-'.Gdn_Format::url(val('Name', $Addon));
        }
    }

    /**
     * Delete an addon version.
     *
     * @param $VersionID
     */
    public function deleteVersion($VersionID) {
        $this->SQL->put('AddonVersion', array('Deleted' => 1), array('AddonVersionID' => $VersionID));
    }

    /**
     * Fire the SQL get.
     *
     * @param string $Offset
     * @param string $Limit
     * @param string $Wheres
     * @return Gdn_DataSet
     * @throws Exception
     */
    public function get($Offset = '0', $Limit = '', $Wheres = '') {
        if ($Limit == '') {
            $Limit = c('Vanilla.Discussions.PerPage', 50);
        }

        $Offset = !is_numeric($Offset) || $Offset < 0 ? 0 : $Offset;

        $this->addonQuery();

        if (is_array($Wheres)) {
            $this->SQL->where($Wheres);
        }

        return $this->SQL
            ->limit($Limit, $Offset)
            ->get();
    }

    /**
     * Set SQL conditions.
     *
     * @param bool $Where
     * @param string $OrderFields
     * @param string $OrderDirection
     * @param bool $Limit
     * @param bool $Offset
     * @return Gdn_DataSet
     * @throws Exception
     */
    public function getWhere($Where = false, $OrderFields = '', $OrderDirection = 'asc', $Limit = false, $Offset = false) {
        $this->addonQuery();

        if ($Where !== false) {
            $this->SQL->where($Where);
        }

        if ($OrderFields != '') {
            $this->SQL->orderBy($OrderFields, $OrderDirection);
        }

        if ($Limit !== false) {
            if ($Offset == false || $Offset < 0) {
                $Offset = 0;
            }

            $this->SQL->limit($Limit, $Offset);
        }

        $Result = $this->SQL->get();
        $this->setCalculatedFields($Result);
        return $Result;
    }

    /**
     * Get the number of addons matching the criteria.
     *
     * @param string $Wheres
     * @return mixed
     */
    public function getCount($Wheres = '') {
        if (!is_array($Wheres)) {
            $Wheres = array();
        }

        $Wheres['a.Visible'] = '1';
        return $this->SQL
            ->select('a.AddonID', 'count', 'CountAddons')
            ->from('Addon a')
            ->join('AddonType t', 'a.AddonTypeID = t.AddonTypeID')
            ->where($Wheres)
            ->get()
            ->firstRow()
            ->CountAddons;
    }

    /**
     * Get an addon by ID or key.
     *
     * @param int|array $AddonID The addon ID which can be one of the following:
     *  - int: The AddonID.
     *  - array: An array where the first element is the addon's key and the second element is the addon type id.
     * @param bool $GetVersions Whether or not to get an array of all of the addon's versions.
     * @return object The addon.
     */
    public function getID($AddonID, $GetVersions = false) {
        // Look for the addon in the cache.
        foreach ($this->_AddonCache as $CachedAddon) {
            if (is_array($AddonID) && $CachedAddon['Key'] == $AddonID[0] && $CachedAddon['Type'] == $AddonID[1]) {
                $Addon = $CachedAddon;
                break;
            } elseif (is_numeric($AddonID) && $CachedAddon['AddonID'] == $AddonID) {
                $Addon = $CachedAddon;
                break;
            }
        }

        if (isset($Addon)) {
            $Result = $Addon;
        } else {
            $this->addonQuery(val(2, $AddonID, false));

            if (is_array($AddonID)) {
                $this->SQL->where(array('a.AddonKey' => $AddonID[0], 'a.AddonTypeID' => $AddonID[1]));
            } else {
                $this->SQL->where('a.AddonID', $AddonID);
            }

            $Result = $this->SQL->get()->firstRow(DATASET_TYPE_ARRAY);
            if (!$Result) {
                return false;
            }


            $this->setCalculatedFields($Result);
            $this->_AddonCache[] = $Result;
        }

        if ($GetVersions && !isset($Result['Versions'])) {
            $Versions = $this->SQL->getWhere('AddonVersion', array('AddonID' => val('AddonID', $Result), 'Deleted' => 0))->resultArray();
            usort($Versions, array($this, 'VersionCompare'));

            foreach ($Versions as $Index => &$Version) {
                $this->setCalculatedFields($Version);
            }

            $Result['Versions'] = $Versions;
        }

        return $Result;
    }

    /**
     * Get a list of addons.
     *
     * @param $IDs
     * @return Gdn_DataSet
     */
    public function getIDs($IDs) {
        $AddonTypeIDs = array();
        $AddonIDs = array();

        // Loop through all of the IDs and parse them out.
        foreach ($IDs as $ID) {
            $Parts = explode('-', $ID, 3);

            if (is_numeric($Parts[0])) {
                $AddonIDs[] = $Parts[0];
            } else {
                $Key = $Parts[0];
                $Type = val(1, $Parts);
                if (isset(self::$Types[$Type])) {
                    $AddonTypeIDs[self::$Types[$Type]][] = $Key;
                }
            }
        }
        $Result = array();

        // Get all of the Addons by ID.
        if (count($AddonIDs) > 0) {
            $this->addonQuery();
            $Addons = $this->SQL->whereIn('a.AddonID', $AddonIDs)->get()->result();
            $Result = array_merge($Result, $Addons);
        }

        // Get all of the Addons by type.
        foreach ($AddonTypeIDs as $TypeID => $Keys) {
            $this->addonQuery();
            $Addons = $this->SQL
                ->where('a.AddonTypeID', $TypeID)
                ->whereIn('a.AddonKey', $Keys)
                ->get()->result();
            $Result = array_merge($Result, $Addons);
        }

        $this->setCalculatedFields($Result);
        $DataSet = new Gdn_DataSet($Result);
        return $DataSet;
    }

    /**
     * Get an addon based on its slug in the following form:
     *  - AddonID[-AddonName]
     *  - AddonType-AddonKey[-Version]
     *
     * @param string|int $Slug The slug to lookup
     * @param bool $GetVersions Whether or not to add an array of versions to the result.
     * @return array
     */
    public function getSlug($slug, $getVersions = false) {
        if (is_numeric($slug)) {
            $addon = $this->getID($slug, $getVersions);
        } else {
            // This is a string identifier for the addon.
            $parts = explode('-', $slug, 3);
            $key = val(0, $parts);

            if (is_numeric($key)) {
                $addon = $this->getID($key, $getVersions);
            } else {
                $type = strtolower(val(1, $parts));
                $typeID = val($type, self::$Types, 0);
                $version = val(2, $parts);

                $addon = $this->getID(array($key, $typeID, $version), $getVersions);
            }
        }

        if (!$addon) {
            return false;
        }

        $addon['Releases'] = [];
        $addon['Prereleases'] = [];

        if ($getVersions) {
            $maxVersion = valr('Versions.0', $addon);
            $foundMax = false;
            $viewingVersion = false;
            foreach ($addon['Versions'] as $version) {
                // Find the version we are looking at.
                $versionSlug = AddonModel::slug($addon, $version);
                if ($versionSlug == $slug && $viewingVersion === false) {
                    $viewingVersion = $version;
                }

                // Separate releases & prereleases.
                if (AddonModel::isReleaseVersion($version['Version'])) {
                    $addon['Releases'][] = $version;
                    // Find the latest stable version.
                    if (!$foundMax) {
                       $maxVersion = $version;
                       $foundMax = true;
                    }
                } elseif ($foundMax === false) {
                    // Only list prereleases new than the current stable.
                    $addon['Prereleases'][] = $version;
                }
            }

            if ($viewingVersion === false) {
                $viewingVersion = $maxVersion;
            }

            $addon['CurrentAddonVersionID'] = $maxVersion['AddonVersionID'];
            $addon = array_merge($addon, $viewingVersion);
            $addon['Slug'] = AddonModel::slug($addon, $viewingVersion);
        }

        return $addon;
    }

    /**
     * Figure out which version is newer.
     *
     * @param $A
     * @param $B
     * @return mixed
     */
    public function versionCompare($A, $B) {
        return -version_compare(val('Version', $A), val('Version', $B));
    }

    /**
     * Get a specified addon version.
     *
     * @param $VersionID
     * @return array|bool|stdClass
     */
    public function getVersion($VersionID) {
        return $this->SQL
            ->select('a.*')
            ->select('v.AddonVersionID, v.Version, v.File, v.MD5, v.FileSize, v.Checked')
            ->from('Addon a')
            ->join('AddonVersion v', 'a.AddonID = v.AddonID')
            ->where('v.AddonVersionID', $VersionID)
            ->get()->firstRow(DATASET_TYPE_ARRAY);
    }

    /**
     * Finish setting up data for the retrieved addon(s).
     *
     * @param $Data
     */
    public function setCalculatedFields(&$Data) {
        if (!$Data) {
            return;
        }

        if (is_a($Data, 'Gdn_DataSet')) {
            $this->setCalculatedFields($Data->result());
        } elseif (is_object($Data) || isset($Data['Icon'])) {
            $File = val('File', $Data);
            setValue('Url', $Data, Gdn_Upload::url($File));

            $Icon = val('Icon', $Data, null);
            if ($Icon !== null) {
                // Fix the icon path.
                if ($Icon && strpos($Icon, '/') == false) {
                    $Icon = 'ai'.$Icon;
                    setValue('Icon', $Data, $Icon);
                }

                if (empty($Icon)) {
                    setValue('IconUrl', $Data, 'foo');
                } else {
                    setValue('IconUrl', $Data, Gdn_Upload::url($Icon));
                }
            } else {
                // Set a default icon.
                setValue('Icon', $Data, url('/applications/dashboard/design/images/eyes.png', true));
            }

            if (val('AddonKey', $Data) && val('Checked', $Data)) {
                $Slug = strtolower(val('AddonKey', $Data).'-'.val('Type', $Data).'-'.val('Version', $Data));
                setValue('Slug', $Data, $Slug);
            }

            // Set the requirements.
            if (val('Checked', $Data)) {
                $Requirements = val('Requirements', $Data);
                try {
                    $Requirements = unserialize($Requirements);
                    if (is_array($Requirements)) {
                        setValue('Requirements', $Data, $Requirements);
                    }
                } catch (Exception $Ex) {
                }
            }
        } elseif (is_array($Data)) {
            foreach ($Data as &$Row) {
                $this->setCalculatedFields($Row);
            }
        }
    }

    /**
     * Test whether a versions string is a release version or not.
     *
     * This is not an exhaustive regex since people can pass whatever they want for a version string.
     * It assumes we are using PHP-standardized version number schemes.
     *
     * @see http://php.net/manual/en/function.version-compare.php
     * @param string $VersionString PHP-standardized version string to test.
     * @return bool Returns true if the version string is a release version or false otherwise.
     */
    public static function isReleaseVersion($VersionString) {
        return !preg_match('`(dev|a|b|rc)`i', $VersionString);
    }

    /**
     * Save the addon data.
     *
     * @param array $Stub
     * @return bool|Gdn_DataSet|mixed|object|string
     */
    public function save($Stub) {
        trace('AddonModel->Save()');

        $Session = Gdn::session();

        $this->defineSchema();

        // Most of the values come from the file itself.
        if (isset($Stub['Path'])) {
            $Path = $Stub['Path'];
        } elseif (val('Checked', $Stub)) {
            $Addon = $Stub;
        } elseif (isset($Stub['File'])) {
            $Path = combinePaths(array(PATH_UPLOADS, $Stub['File']));
        } else {
            if (!$Session->checkPermission('Addons.Addon.Manage') && isset($Stub['Filename'])) {
                // Only admins can modify plugin attributes without the file.
                $this->Validation->addValidationResult('Filename', 'ValidateRequired');
                return false;
            }
        }

        // Analyze and fix the file.
        if (!isset($Addon)) {
            if (isset($Path)) {
                try {
                    $Addon = UpdateModel::analyzeAddon($Path, false);
                } catch (Exception $Ex) {
                    $Addon = false;
                    $this->Validation->addValidationResult('File', '@'.$Ex->getMessage());
                }
                if (!is_array($Addon)) {
                    $this->Validation->addValidationResult('File', 'Could not analyze the addon file.');
                    return false;
                }
                $Addon = array_merge($Stub, $Addon);
            } else {
                $Addon = $Stub;
                if (isset($Path)) {
                    $Addon['MD5'] = md5_file($Path);
                    $Addon['FileSize'] = filesize($Path);
                }
            }
        }

        // Get an existing addon.
        if (isset($Addon['AddonID'])) {
            $CurrentAddon = $this->getID($Addon['AddonID'], true);
        } elseif (isset($Addon['AddonKey']) && isset($Addon['AddonTypeID'])) {
            $CurrentAddon = $this->getID(array($Addon['AddonKey'], $Addon['AddonTypeID']), true);
        } else {
            $CurrentAddon = false;
        }

        trace($CurrentAddon, 'CurrentAddon');

        $Insert = !$CurrentAddon;
        if ($Insert) {
            $this->addInsertFields($Addon);
        }

        $this->addUpdateFields($Addon); // always add update fields

        if (!$this->validate($Addon, $Insert)) {
            trace('Addon did not validate');
            return false;
        }

        // Search for the current version.
        $MaxVersion = false;
        $CurrentVersion = false;
        if ($CurrentAddon && isset($Addon['Version'])) {
            // Search for a current version.
            foreach ($CurrentAddon['Versions'] as $Index => $Version) {
                if (isset($Addon['AddonVersionID'])) {
                    if ($Addon['AddonVersionID'] == $Version['AddonVersionID']) {
                        $CurrentVersion = $Version;
                    }
                } elseif (version_compare($Addon['Version'], $Version['Version']) == 0) {
                    $CurrentVersion = $Version;
                }

                // Only check for a current version if the version has been checked.
                if (!$Version['Checked']) {
                    continue;
                }

                if (!$MaxVersion || version_compare($MaxVersion['Version'], $Version['Version'], '<')) {
                    $MaxVersion = $Version;
                }
            }
        }

        // Save the addon.
        $Fields = $this->filterSchema($Addon);
        if ($Insert) {
            $AddonID = $this->SQL->insert($this->Name, $Fields);

            // Add the activity.
            $ActivityModel = new ActivityModel();
            $Activity = array(
                 'ActivityType' => 'Addon',
                 'ActivityUserID' => $Fields['InsertUserID'],
                 'NotifyUserID' => ActivityModel::NOTIFY_PUBLIC,
                 'HeadlineFormat' => '{ActivityUserID,user} added the <a href="{Url,html}">{Data.Name}</a> addon.',
                 'Story' => Gdn_Format::html($Fields['Description']),
                 'Route' => '/addon/'.rawurlencode(self::slug($Fields, false)),
                 'Data' => array('Name' => $Fields['Name'])
            );
            $ActivityModel->save($Activity);
        } else {
            $AddonID = val('AddonID', $CurrentAddon);

            // Only save the addon if it is the current version.
            if (!$MaxVersion || version_compare($Addon['Version'], $MaxVersion['Version'], '>=')) {
                Trace('Uploaded version is the most recent version.');
                $this->SQL->put($this->Name, $Fields, array('AddonID' => $AddonID));
            } else {
                $this->SQL->reset();
            }
        }

        // Save the version.
        if ($AddonID && isset($Path) || isset($Addon['File'])) {
            trace('Saving addon version');
            $Addon['AddonID'] = $AddonID;

            if (isset($Path)) {
                if (!stringBeginsWith($Path, PATH_UPLOADS.'/addons/')) {
                    // The addon must be copied into the uploads folder.
                    $NewPath = PATH_UPLOADS.'/addons/'.basename($Path);
                    //rename($Path, $NewPath);
                    $Path = $NewPath;
                    $this->_AddonCache = array();
                }
                $File = substr($Path, strlen(PATH_UPLOADS.'/'));
                $Addon['File'] = $File;
            }

            if ($CurrentVersion) {
                $Addon['AddonVersionID'] = val('AddonVersionID', $CurrentVersion);
            }

            // Insert or update the version.
            $VersionModel = new Gdn_Model('AddonVersion');
            $AddonVersionID = $VersionModel->save($Addon);
            $this->Validation->addValidationResult($VersionModel->validationResults());

            if (!$AddonVersionID) {
                return false;
            }

            // Update the current version in the addon.
            if (!$MaxVersion || version_compare($CurrentAddon['Version'], $Addon['Version'], '<')) {
                $this->SQL->put(
                    $this->Name,
                    array('CurrentAddonVersionID' => $AddonVersionID),
                    array('AddonID' => $AddonID)
                );
            }
        }
        $this->_AddonCache = array();

        return $AddonID;
    }

    /**
     * Set a single property of an addon.
     *
     * @param int $AddonID
     * @param string $Property
     * @param bool $ForceValue
     * @return bool|string
     * @throws Exception
     */
    public function setProperty($AddonID, $Property, $ForceValue = false) {
        if ($ForceValue !== false) {
            $Value = $ForceValue;
        } else {
            $Addon = $this->getID($AddonID);
            $Value = ($Addon[$Property] == '1' ? '0' : '1');
        }

        $this->SQL
            ->update('Addon')
            ->set($Property, $Value)
            ->where('AddonID', $AddonID)
            ->put();

        return $Value;
    }

    /**
     * Do validation on addon data.
     *
     * @param array $Post
     * @param bool $Insert
     * @return bool
     */
    public function validate($Post, $Insert) {
        $this->Validation->addRule('AddonKey', 'function:ValidateAddonKey');

        if (val('Checked', $Post) && ($Insert || isset($Post['AddonKey']))) {
            $this->Validation->applyRule('AddonKey', 'Required');
            $this->Validation->applyRule('AddonKey', 'AddonKey');
        }

        if ($Insert || isset($Post['Version'])) {
            $this->Validation->applyRule('Version', 'Required');
            $this->Validation->applyRule('Version', 'Version');
        }

        parent::validate($Post, $Insert);

        // Validate against an existing addon.
        if ($AddonID = val('AddonID', $Post)) {
            $CurrentAddon = $this->getID($AddonID, true);
            if ($CurrentAddon) {
                if (val('AddonKey', $CurrentAddon) && isset($Post['AddonKey']) && val('AddonKey', $Post) != val('AddonKey', $CurrentAddon)) {
                    $this->Validation->addValidationResult('AddonKey', '@'.sprintf(t('The addon\'s key cannot be changed. The uploaded file has a key of <b>%s</b>, but it must be <b>%s</b>.'), val('AddonKey', $Post), val('AddonKey', $CurrentAddon)));
                } else {
                    // Make sure this version doesn't match.
                    foreach ($CurrentAddon['Versions'] as $Version) {
                        if ($Version['Deleted']) {
                            continue;
                        }

                        if (version_compare(val('Version', $Version), val('Version', $Post)) == 0) {
                            // This version matches a previous version.
                            if (val('Checked', $Version) && val('MD5', $Version) != val('MD5', $Post)) {
                                $this->Validation->addValidationResult('Version', '@'.sprintf(t('Version %s of this addon already exists.'), val('Version', $Version)));
                            }
                        }
                    }
                }
            }
        }

        // Make sure there isn't another addon with the same key as this one.
        if (validateRequired(val('AddonKey', $Post))) {
            $CountSame = $this->getCount(array('AddonKey' => $Post['AddonKey'], 'AddonID <>' => val('AddonID', $Post), 'a.AddonTypeID' => val('AddonTypeID', $Post)));
            if ($CountSame > 0) {
                $this->Validation->addValidationResult('AddonKey', '@'.sprintf(t('The addon key %s is already taken.'), $Post['AddonKey']));
            }
        }

        return count($this->Validation->results()) == 0;
    }

    /**
     * "Delete" an addon (make it invisible).
     *
     * @param string|unknown_type $AddonID
     */
    public function delete($AddonID) {
        $this->setProperty($AddonID, 'Visible', '0');
    }

    /**
     * Update the latest version of the addon.
     *
     * @param $AddonID
     */
    public function updateCurrentVersion($AddonID) {
        $Addon = $this->getID($AddonID, true);

        $MaxVersion = false;
        foreach ($Addon['Versions'] as $Version) {
            if (!$Version['Checked'] || $Version['Deleted']) {
                continue;
            }
            if (!$MaxVersion || version_compare($Version['Version'], $MaxVersion['Version'], '>')) {
                $MaxVersion = $Version;
            }
        }
        if ($MaxVersion) {
            $this->SQL->history()->put('Addon', array('CurrentAddonVersionID' => $MaxVersion->Version), array('AddonID' => $AddonID));
        }
    }
}

/**
 * Do we have a valid addon key?
 *
 * @param $Value
 * @return bool
 */
function validateAddonKey($Value) {
    if (is_numeric($Value)) {
        return false;
    } elseif (preg_match('`[-,;:/]`', $Value) || strpos($Value, '\\') !== false) {
        return false;
    }
    return true;
}
