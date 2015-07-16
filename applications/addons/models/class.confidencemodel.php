<?php
class ConfidenceModel extends Gdn_Model {

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
        return $this->SQL->Select('av.AddonVersionID')
                ->From('Addon a')
                ->Where('a.AddonTypeID', ADDON_TYPE_CORE)
                ->Where('a.AddonKey', 'vanilla')
                ->Join('AddonVersion av', 'a.AddonID = av.AddonID')
                ->OrderBy('av.DateInserted', 'desc')
                ->Limit(1)
                ->Get()
                ->FirstRow()
                ->AddonVersionID;
    }
    
    public function getID($AddonVersionID, $DataType = DATASET_TYPE_ARRAY) {
        $CoreVersion = $this->getCoreVersion();
        $Confidence = $this->SQL
                ->Select('c.AddonVersionID, c.CoreVersionID, av.Version as CoreVersion, COUNT(c.ConfidenceID) as TotalVotes, SUM(c.Weight) as TotalWeight, AVG(c.Weight) as AverageWeight')
                ->From('Confidence c')
                ->Where('c.AddonVersionID', $AddonVersionID)
                ->Where('c.CoreVersionID', $CoreVersion)
                ->Join('AddonVersion av', 'c.CoreVersionID = av.AddonVersionID')
                ->GroupBy('c.AddonVersionID')
                ->Get()
                ->FirstRow($DataType);
        
        return $Confidence;
    }
    
    public function getCurrentConfidence($userID, $addonID) {
        $CoreVersion = $this->getCoreVersion();
        return $this->SQL
                ->Select('c.*')
                ->From('Confidence c')
                ->Where('c.AddonVersionID', $addonID)
                ->Where('c.CoreVersionID', $CoreVersion)
                ->Where('c.UserID', $userID)
                ->Get()
                ->FirstRow();
    }

}