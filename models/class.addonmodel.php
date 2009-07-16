<?php if (!defined('APPLICATION')) exit();

class AddonModel extends Model {
   public function __construct() {
      parent::__construct('Addon');
   }
   
   public function AddonQuery() {
      $this->SQL
         ->Select('a.*')
         ->Select('t.Label', '', 'Type')
         ->Select('v.AddonVersionID, v.File, v.Version, v.DateReviewed, v.TestedWith')
         ->Select('v.DateInserted', '', 'DateUploaded')
         ->Select('iu.Name', '', 'InsertName')
         ->From('Addon a')
         ->Join('AddonVersion v', 'a.CurrentAddonVersionID = v.AddonVersionID')
         ->Join('AddonType t', 'a.AddonTypeID = t.AddonTypeID')
         ->Join('User iu', 'a.InsertUserID = iu.UserID')
         ->Where('a.Visible', '1');
   }
   
   public function Get($Offset = '0', $Limit = '', $Wheres = '') {
      if ($Limit == '') 
         $Limit = Gdn::Config('Vanilla.Discussions.PerPage', 50);

      $Offset = !is_numeric($Offset) || $Offset < 0 ? 0 : $Offset;
      
      $this->AddonQuery();
      
      if (is_array($Wheres))
         $this->SQL->Where($Wheres);

      return $this->SQL
         ->Limit($Limit, $Offset)
         ->Get();
   }
   
   public function GetWhere($Where = FALSE, $OrderFields = '', $OrderDirection = 'asc', $Limit = FALSE, $Offset = FALSE) {
      $this->AddonQuery();
      
      if ($Where !== FALSE)
         $this->SQL->Where($Where);

      if ($OrderFields != '')
         $this->SQL->OrderBy($OrderFields, $OrderDirection);

      if ($Limit !== FALSE) {
         if ($Offset == FALSE || $Offset < 0)
            $Offset = 0;

         $this->SQL->Limit($Limit, $Offset);
      }

      return $this->SQL->Get();
   }
   
   public function GetCount($Wheres = '') {
      if (!is_array($Wheres))
         $Wheres = array();
         
      $Wheres['a.Visible'] = '1';
      return $this->SQL
         ->Select('a.AddonID', 'count', 'CountAddons')
         ->From('Addon a')
         ->Where($Wheres)
         ->Get()
         ->FirstRow()
         ->CountDiscussions;
   }

   public function GetID($AddonID, $Wheres = '') {
      $this->AddonQuery();
      if (is_array($Wheres))
         $this->SQL->Where($Wheres);

      return $this->SQL
         ->Where('a.AddonID', $AddonID)
         ->Get()
         ->FirstRow();
   }
   
   public function Save($FormPostValues, $FileName = '') {
      $Session = Gdn::Session();
      
      // Define the primary key in this model's table.
      $this->DefineSchema();
      
      // Add & apply any extra validation rules:
      if (array_key_exists('Description', $FormPostValues))
         $this->Validation->ApplyRule('Description', 'Required');

      if (array_key_exists('Version', $FormPostValues))
         $this->Validation->ApplyRule('Version', 'Required');

      if (array_key_exists('TestedWith', $FormPostValues))
         $this->Validation->ApplyRule('TestedWith', 'Required');
      
      // Get the ID from the form so we know if we are inserting or updating.
      $AddonID = ArrayValue('AddonID', $FormPostValues, '');
      $Insert = $AddonID == '' ? TRUE : FALSE;
      
      if ($Insert) {
         unset($FormPostValues['AddonID']);
         $this->AddInsertFields($FormPostValues);
      }
      $this->AddUpdateFields($FormPostValues);
      // Validate the form posted values
      if ($this->Validate($FormPostValues, $Insert)) {
         $Fields = $this->Validation->SchemaValidationFields(); // All fields on the form that relate to the schema
         $AddonID = intval(ArrayValue('AddonID', $Fields, 0));
         $Fields = RemoveKeyFromArray($Fields, 'AddonID'); // Remove the primary key from the fields for saving
         $Addon = FALSE;
         $Activity = 'EditAddon';
         if ($AddonID > 0) {
            $this->SQL->Put($this->Name, $Fields, array($this->PrimaryKey => $AddonID));
         } else {
            $AddonID = $this->SQL->Insert($this->Name, $Fields);
            $Activity = 'AddAddon';
         }
         // Save the version
         if ($AddonID > 0 && $FileName != '') {
            // Save the addon file & version
            $AddonVersionModel = new Model('AddonVersion');
            $AddonVersionID = $AddonVersionModel->Save(array(
               'AddonID' => $AddonID,
               'File' => $FileName,
               'Version' => ArrayValue('Version', $FormPostValues, ''),
               'TestedWith' => ArrayValue('TestedWith', $FormPostValues, ''),
               'DateInserted' => Format::ToDateTime()
            ));
            // Mark the new addon file & version as the current version
            $this->SQL->Put($this->Name, array('CurrentAddonVersionID' => $AddonVersionID), array($this->PrimaryKey => $AddonID));
         }
         
         $Addon = $this->GetID($AddonID);
         // Record Activity
         AddActivity(
            $Session->UserID,
            $Activity,
            '',
            '',
            '/addon/'.$AddonID.'/'.Format::Url($Addon->Name)
         );
      }
      if (!is_numeric($AddonID))
         $AddonID = FALSE;
         
      return count($this->ValidationResults()) > 0 ? FALSE : $AddonID;
   }
   
   public function SetProperty($AddonID, $Property, $ForceValue = FALSE) {
      if ($ForceValue !== FALSE) {
         $Value = $ForceValue;
      } else {
         $Value = '1';
         $Addon = $this->GetID($AddonID);
         $Value = ($Addon->$Property == '1' ? '0' : '1');
      }
      $this->SQL
         ->Update('Addon')
         ->Set($Property, $Value)
         ->Where('AddonID', $AddonID)
         ->Put();
      return $Value;
   }
      
   public function Delete($AddonID) {
      $this->SetProperty($AddonID, 'Visible', '0');
   }
}