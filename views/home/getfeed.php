<?php if (!defined('APPLICATION')) exit();

$Loop = 1;
echo '<div class="Feed">';
foreach ($this->Feed->channel->item as $Item) {
   if ($Loop > $this->MaxLength)
      break;
   
   $Title = GetValue('title', $Item);
   $Link = GetValue('link', $Item);
   $PubDate = GetValue('pubDate', $Item);
   echo Wrap(
      '<i class="Sprite SpriteRarr SpriteRarrDown"><span>&rarr;</span></i>'
      .Anchor($Title, $Link),
      'div'
   );
   $Loop++;
}
echo '</div>';