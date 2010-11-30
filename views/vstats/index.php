<?php if (!defined('APPLICATION')) exit();

function WriteDelta($Total, $Delta) {
   if ($Delta > 0) {
      $w = 'Plus';
      $o = '+';
   } elseif ($Delta < 0) {
      $w = 'Minus';
      $o = '';
   } else {
      $w = '';
      $o = '';
   }
   echo '<td>'.number_format($Total).'</td>
   <td class="Delta'.$w.'">'.$o.number_format($Delta)."</td>\r\n";
}
function ToTimeStamp($DateTime) {
   if (preg_match('/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/', $DateTime, $Matches)) {
      $Year = $Matches[1];
      $Month = $Matches[2];
      $Day = $Matches[3];
      $Hour = $Matches[4];
      $Minute = $Matches[5];
      $Second = $Matches[6];
      return mktime($Hour, $Minute, $Second, $Month, $Day, $Year);
   } else {
      return time();
   }
}

?>
<style type="text/css">
table {
   margin: 20px;
   border-bottom: 1px solid #ddd;
   border-right: 1px solid #ddd;
   border-collapse: collapse;
   width: auto;
}
table th {
   text-align: left;
}
table td {
   text-align: right;
}
table td,
table th {
   border-top: 1px solid #ddd;
   border-left: 1px solid #ddd;
   font-size: 13px;
   padding: 4px;
}
table thead td,
table thead th {
   background: #fafafa;
   font-weight: bold;
   text-align: left;
}
thead td.Delta {
   color: #555;
   text-align: right;
}
tbody td.DeltaPlus {
   color: green;
}
tbody td.DeltaMinus {
   color: red;
}
tbody td.Delta {
   color: #999;
}
li, p {
   font-size: small;
}
</style>
<h1>Vanilla Usage Stats</h1>
<div class="Info">Last 10 months. Current month will not be accurate as it is not yet complete.</div>
<table border="0">
   <thead>
   <tr>
      <td>Date</td>
      <td>Vanilla Downloads</td>
      <td class="Delta">+/-</td>
      <td>Addon Downloads</td>
      <td class="Delta">+/-</td>
      <td>Total Downloads</td>
      <td class="Delta">+/-</td>
   </tr>
   </thead>
   <tbody>
   <?php
      $VanillaDownloads = -1;
      $AddonDownloads = -1;

      // Dump the results for the last 30 days
      foreach ($this->StatsData as $Row) {
         if ($VanillaDownloads < 0) {
            $VanillaDownloads = $Row['VanillaDownloads'];
            $AddonDownloads = $Row['AddonDownloads'];
         } else {
            $DVanillaDownloads = $VanillaDownloads - $Row['VanillaDownloads'];
            $DAddonDownloads = $AddonDownloads - $Row['AddonDownloads'];
            ?>
            <tr>
               <td><?php echo date("M Y", ToTimeStamp($Row['DateInserted'])); ?></td>
               <?php
                  WriteDelta($VanillaDownloads, $DVanillaDownloads);
                  WriteDelta($AddonDownloads, $DAddonDownloads);
                  WriteDelta($VanillaDownloads + $AddonDownloads, $DVanillaDownloads + $DAddonDownloads);
               ?>
            </tr>
            <?php
            $VanillaDownloads = $Row['VanillaDownloads'];
            $AddonDownloads = $Row['AddonDownloads'];
         }
      }
   ?>
   </tbody>
</table>
