<?php
class ConfidenceModel extends Gdn_Model {

    private $coreVersion = null;
    
    public function __construct($Name = '') {
        parent::__construct('Confidence');
    }
    
    public function getCoreVersions($Limit = 5) {
        return $this->SQL->Select('a.AddonID, a.Name, av.AddonVersionID, av.Version')
                ->From('Addon a')
                ->Where('a.AddonTypeID', ADDON_TYPE_CORE)
                ->Where('a.AddonKey', 'vanilla')
                ->Join('AddonVersion av', 'a.AddonID = av.AddonID')
                ->OrderBy('av.DateInserted', 'desc')
                ->Limit($Limit)
                ->Get()
                ->Result();
    }

    public function getCoreVersion() {
        if(is_null($this->coreVersion)) {
            $this->coreVersion = $this->SQL->Select('a.Name, av.AddonVersionID, av.Version')
                ->From('Addon a')
                ->Where('a.AddonTypeID', ADDON_TYPE_CORE)
                ->Where('a.AddonKey', 'vanilla')
                ->Join('AddonVersion av', 'a.AddonID = av.AddonID')
                ->OrderBy('av.DateInserted', 'desc')
                ->Limit(1)
                ->Get()
                ->FirstRow();
        }
        
        return $this->coreVersion;
    }
    
    public function getID($AddonVersionID, $DataType = DATASET_TYPE_ARRAY) {
        $version = $this->getCoreVersion();
        $Confidence = $this->SQL
                ->Select('c.AddonVersionID, c.CoreVersionID, av.Version as CoreVersion, COUNT(c.ConfidenceID) as TotalVotes, SUM(c.Weight) as TotalWeight, AVG(c.Weight) as AverageWeight')
                ->From('Confidence c')
                ->Where('c.AddonVersionID', $AddonVersionID)
                ->Where('c.CoreVersionID', $version->AddonVersionID)
                ->Join('AddonVersion av', 'c.CoreVersionID = av.AddonVersionID')
                ->GroupBy('c.AddonVersionID')
                ->Get()
                ->FirstRow($DataType);
        
        return $Confidence;
    }
    
    public function getCurrentConfidence($userID, $addonID) {
        $version = $this->getCoreVersion();
        return $this->SQL
                ->Select('c.*')
                ->From('Confidence c')
                ->Where('c.AddonVersionID', $addonID)
                ->Where('c.CoreVersionID', $version->AddonVersionID)
                ->Where('c.UserID', $userID)
                ->Get()
                ->FirstRow();
    }

}