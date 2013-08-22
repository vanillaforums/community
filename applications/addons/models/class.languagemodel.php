<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
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