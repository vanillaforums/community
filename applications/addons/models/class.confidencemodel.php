<?php
class ConfidenceModel extends Gdn_Model {

    private $coreVersion = null;
    
    public function __construct($Name = '') {
        parent::__construct('Confidence');
    }
    
    /**
     * Get a list of addon versions of Vanilla
     * 
     * @param int $Limit
     * @return array
     */
    public function getCoreVersions($Limit = 5) {
        return $this->SQL->Select('a.AddonID, a.Name, av.AddonVersionID, av.Version')
                ->From('Addon a')
                ->Where('a.AddonTypeID', ADDON_TYPE_CORE)
                ->Where('a.AddonKey', 'vanilla')
                ->Where('av.Deleted', 0)
                ->Join('AddonVersion av', 'a.AddonID = av.AddonID')
                ->OrderBy('av.DateInserted', 'desc')
                ->Limit($Limit)
                ->Get()
                ->Result();
    }

    /**
     * Get the latest addon version of Vanilla
     * 
     * @return bool|stdClass False when empty, object otherwise
     */
    public function getCoreVersion() {
        if (is_null($this->coreVersion)) {
            $this->coreVersion = $this->SQL->Select('a.Name, av.AddonVersionID, av.Version')
                ->From('Addon a')
                ->Where('a.AddonTypeID', ADDON_TYPE_CORE)
                ->Where('a.AddonKey', 'vanilla')
                ->Where('av.Deleted', 0)
                ->Join('AddonVersion av', 'a.AddonID = av.AddonID')
                ->OrderBy('av.DateInserted', 'desc')
                ->Limit(1)
                ->Get()
                ->FirstRow();
        }
        
        return $this->coreVersion;
    }
    
    /**
     * Validates an ID and returns the addon version associated with the core
     * version. Defaults to the latest core version if the ID is invalid
     * 
     * @param int $ID
     * @return stdClass
     */
    public function checkCoreVersion($ID) {
        $result = $this->SQL->Select('a.Name, av.AddonVersionID, av.Version')
                ->From('Addon a')
                ->Where('a.AddonTypeID', ADDON_TYPE_CORE)
                ->Where('a.AddonKey', 'vanilla')
                ->Where('av.AddonVersionID', $ID)
                ->Join('AddonVersion av', 'a.AddonID = av.AddonID')
                ->OrderBy('av.DateInserted', 'desc')
                ->Limit(1)
                ->Get()
                ->FirstRow();
        
        if (!$result) {
            $result = $this->getCoreVersion();
        }
        
        return $result;
    }
    
    /**
     * Loads the confidence summary of the specified addon version against the 
     * specified core version.
     * 
     * @param int $AddonVersionID
     * @param string $DataType DATASET_TYPE_ARRAY or DATASET_TYPE_OBJECT
     * @return array|stdClass matching $DataType
     */
    public function getID($AddonVersionID, $coreVersionID = false, $DataType = DATASET_TYPE_ARRAY) {
        $coreVersion = $this->checkCoreVersion($coreVersionID);
        $Confidence = $this->SQL
                ->Select('c.AddonVersionID, c.CoreVersionID, av.Version as CoreVersion, COUNT(c.ConfidenceID) as TotalVotes, SUM(c.Weight) as TotalWeight, AVG(c.Weight) as AverageWeight')
                ->From('Confidence c')
                ->Where('c.AddonVersionID', $AddonVersionID)
                ->Where('c.CoreVersionID', $coreVersion->AddonVersionID)
                ->Join('AddonVersion av', 'c.CoreVersionID = av.AddonVersionID')
                ->GroupBy('c.AddonVersionID')
                ->Get()
                ->FirstRow($DataType);
        
        return $Confidence;
    }
    
    /**
     * Get the vote of a specific user's confidence for and addon working on the
     * specified version of Vanilla
     * 
     * @param int $userID
     * @param int $addonID
     * @param int|bool $coreID If false, use the latest core version
     * @return array
     */
    public function getConfidenceVote($userID, $addonID, $coreID = false) {
        $coreVersion = $this->checkCoreVersion($coreID);
        return $this->SQL
                ->Select('c.*')
                ->From('Confidence c')
                ->Where('c.AddonVersionID', $addonID)
                ->Where('c.CoreVersionID', $coreVersion->AddonVersionID)
                ->Where('c.UserID', $userID)
                ->Get()
                ->FirstRow();
    }
    
    /**
     * Automatically add the current core version if not specified in the fields
     * 
     * @param array $fields
     * @return bool
     */
    public function insert($fields) {
        if (!array_key_exists('CoreVersionID', $fields)) {
            $fields['CoreVersionID'] = $this->getCoreVersion()->AddonVersionID;
        }
        return parent::insert($fields);
    }

}