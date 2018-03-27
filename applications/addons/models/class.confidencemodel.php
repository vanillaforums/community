<?php
/**
 *
 *
 * @copyright 2009-2016 Vanilla Forums Inc.
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU GPL v2
 * @package Addons
 * @since 2.2
 */

/**
 * Handles votes for addons working with a specific version of Vanilla.
 */
class ConfidenceModel extends Gdn_Model {

    private $coreVersion = null;
    
    /**
     * Use the Confidence table.
     */
    public function __construct() {
        parent::__construct('Confidence');
    }
    
    /**
     * Get a list of addon versions of Vanilla.
     * 
     * @param int $limit Number of core versions to return.
     * @return array
     */
    public function getCoreVersions($limit = 5) {
        return $this->SQL->select('a.AddonID, a.Name, av.AddonVersionID, av.Version')
                ->from('Addon a')
                ->where('a.AddonTypeID', ADDON_TYPE_CORE)
                ->where('a.AddonKey', 'vanilla')
                ->where('av.Deleted', 0)
                ->join('AddonVersion av', 'a.AddonID = av.AddonID')
                ->orderBy('av.DateInserted', 'desc')
                ->limit($limit)
                ->get()
                ->result();
    }

    /**
     * Get the latest addon version of Vanilla.
     * 
     * @return bool|stdClass False when empty, object otherwise
     */
    public function getCoreVersion() {
        if (is_null($this->coreVersion)) {
            $this->coreVersion = $this->SQL->select('a.Name, av.AddonVersionID, av.Version')
                ->from('Addon a')
                ->where('a.AddonTypeID', ADDON_TYPE_CORE)
                ->where('a.AddonKey', 'vanilla')
                ->where('av.Deleted', 0)
                ->join('AddonVersion av', 'a.AddonID = av.AddonID')
                ->orderBy('av.DateInserted', 'desc')
                ->limit(1)
                ->get()
                ->firstRow();
        }
        
        return $this->coreVersion;
    }
    
    /**
     * Validate a core version id.
     * 
     * Validates an ID and returns the addon version associated with the core
     * version. Defaults to the latest core version if the ID is invalid.
     * 
     * @param int $id The core version to check.
     * @return stdClass
     */
    public function checkCoreVersion($id) {
        $result = $this->SQL->select('a.Name, av.AddonVersionID, av.Version')
                ->from('Addon a')
                ->where('a.AddonTypeID', ADDON_TYPE_CORE)
                ->where('a.AddonKey', 'vanilla')
                ->where('av.AddonVersionID', $id)
                ->join('AddonVersion av', 'a.AddonID = av.AddonID')
                ->orderBy('av.DateInserted', 'desc')
                ->limit(1)
                ->get()
                ->firstRow();
        
        if (!$result) {
            $result = $this->getCoreVersion();
        }
        
        return $result;
    }
    
    /**
     * Loads the confidence summary of an addon version against a core version.
     * 
     * @param int $addonVersionID The addon id in question.
     * @param int $coreVersionID Defaults to latest core version if not specified.
     * @param string $dataType DATASET_TYPE_ARRAY or DATASET_TYPE_OBJECT.
     * @return array|stdClass matching $dataType
     */
    public function getID($addonVersionID, $coreVersionID = false, $dataType = DATASET_TYPE_ARRAY) {
        $coreVersion = $this->checkCoreVersion($coreVersionID);
        $confidence = $this->SQL
                ->select('COUNT(ConfidenceID) as TotalVotes, SUM(Weight) as TotalWeight, AVG(Weight) as AverageWeight')
                ->from('Confidence')
                ->where('AddonVersionID', $addonVersionID)
                ->where('CoreVersionID', $coreVersion->AddonVersionID)
                ->groupBy('AddonVersionID')
                ->get()
                ->firstRow($dataType);

        if (is_a($confidence) || is_object($confidence)) {
            setValue('AddonVersionID', $confidence, $addonVersionID);
            setValue('CoreVersionID', $confidence, $coreVersion->AddonVersionID);

            $addonCoreVersion = $this->SQL->select('Version')
                ->from('AddonVersion')
                ->where('AddonVersionID', $coreVersion->AddonVersionID)
                ->get()
                ->firstRow(DATASET_TYPE_ARRAY);
            $coreVersion = $addonCoreVersion ? $addonCoreVersion['Version'] : null;
            setValue('CoreVersion', $confidence, $coreVersion);
        }

        return $confidence;
    }
    
    /**
     * Get a users vote for an addon.
     * 
     * Get the vote of a specific user's confidence for and addon working on the
     * specified version of Vanilla.
     * 
     * @param int $userID The user in question.
     * @param int $addonID The addon in question.
     * @param int|bool $coreID If false, use the latest core version.
     * @return array
     */
    public function getConfidenceVote($userID, $addonID, $coreID = false) {
        $coreVersion = $this->checkCoreVersion($coreID);
        return $this->SQL
                ->select('c.*')
                ->from('Confidence c')
                ->where('c.AddonVersionID', $addonID)
                ->where('c.CoreVersionID', $coreVersion->AddonVersionID)
                ->where('c.UserID', $userID)
                ->get()
                ->firstRow();
    }
    
    /**
     * Automatically add the current core version if not specified in the fields.
     * 
     * @param array $fields The data you want to insert into the model.
     * @return bool
     */
    public function insert($fields) {
        if (!array_key_exists('CoreVersionID', $fields)) {
            $fields['CoreVersionID'] = $this->getCoreVersion()->AddonVersionID;
        }
        return parent::insert($fields);
    }
}
