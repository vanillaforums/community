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
        // Cut off after the first subheading.
        $Description = array_shift(explode('<h', $Description));
        echo Wrap(
            Wrap(Anchor($Title, $Link), 'h2')
                .Wrap(Gdn_Format::Date($PubDate), 'div', array('class' => 'Date'))
                .Wrap($Description, 'div', array('class' => 'FeedDescription')),
            'div', array('class' => 'FeedItem ExtendedFormat')
        );
    } else {
        echo Wrap(
            '<span class="Sprite SpriteRarr SpriteRarrDown">&rarr;</span>'
                .Anchor($Title, $Link)
                .Wrap(Gdn_Format::Date($PubDate), 'div', array('class' => 'Date')),
            'div', array('class' => 'FeedItem NormalFormat')
        );
    }
    $Loop++;
}
echo '</div>';