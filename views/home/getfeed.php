<?php if (!defined('APPLICATION')) exit();

$Loop = 1;
echo '<div class="Feed">';
foreach ($this->Feed->channel->item as $Item) {
   if ($Loop > $this->MaxLength)
      break;

   $Title = GetValue('title', $Item);
   $Link = GetValue('link', $Item);
   $PubDate = strtotime(GetValue('pubDate', $Item));
   if ($this->FeedFormat == 'extended') {
      $Description = GetValue('description', $Item);
      echo Wrap(
         Anchor($Title, $Link)
         .Wrap(Gdn_Format::Date($PubDate), 'div', array('class' => 'Date'))
         .Wrap($Description, 'em'),
         'div',
         array('class' => 'FeedItem')
      );
   } else {
      echo Wrap(
         '<i class="Sprite SpriteRarr SpriteRarrDown"><span>&rarr;</span></i>'
         .Anchor($Title, $Link),
         'div'
      );
   }
   $Loop++;
}
echo '</div>';