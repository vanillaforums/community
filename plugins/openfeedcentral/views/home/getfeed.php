<?php if (!defined('APPLICATION')) exit();

$Loop = 1;
echo '<div class="Feed">';
foreach ($this->Feed->channel->item as $Item) {
    if ($Loop > $this->MaxLength) {
        break;
    }

    $Title = val('title', $Item);
    $Link = val('link', $Item);
    $PubDate = strtotime(val('pubDate', $Item));
    if ($this->FeedFormat == 'extended') {
        $Description = val('description', $Item);
        // Cut off after the first subheading.
        $Description = array_shift(explode('<h', $Description));
        echo wrap(
            wrap(anchor($Title, $Link), 'h2')
                .wrap(Gdn_Format::date($PubDate), 'div', ['class' => 'Date'])
                .wrap($Description, 'div', ['class' => 'FeedDescription']),
            'div',
            ['class' => 'FeedItem ExtendedFormat']
        );
    } else {
        echo wrap(
            '<span class="Sprite SpriteRarr SpriteRarrDown">&rarr;</span>'
                .anchor($Title, $Link)
                .wrap(Gdn_Format::date($PubDate), 'div',['class' => 'Date']),
            'div',
            ['class' => 'FeedItem NormalFormat']
        );
    }
    $Loop++;
}
echo '</div>';