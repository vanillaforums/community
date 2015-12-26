<?php if (!defined('APPLICATION')) exit();
    $Access = ($this->data('Slug') ? urlencode($this->data('Slug')) : $AddonID);
?>
    <description><?php echo Gdn_Format::text($this->Head->title()); ?></description>
    <language><?php echo c('Garden.Locale', 'en-US'); ?></language>
    <atom:link href="<?php echo url('/addon/'.$Access.'/follow.rss'); ?>" rel="self" type="application/rss+xml" />
<?php
$SlugBase = AddonModel::slug($this->Data, FALSE);
foreach ($this->data('Versions') as $Version) {
    $VersionSlug = urlencode($SlugBase.'-'.$Version['Version']);
    ?>
    <item>
        <title><?php echo Gdn_Format::text($this->data('Name').' '.$Version['Version']); ?></title>
        <link><?php echo url('/addon/'.$VersionSlug, TRUE); ?></link>
        <pubDate><?php echo date(DATE_RSS, Gdn_Format::toTimeStamp($Version['DateInserted'])); ?></pubDate>
        <dc:creator><?php echo Gdn_Format::text($this->data('InsertName')); ?></dc:creator>
        <guid isPermaLink="true"><?php echo Url('/addons/addon/'.$VersionSlug, TRUE); ?></guid>
        <description><![CDATA[<?php echo Gdn_Format::html($this->data('Description')); ?>]]></description>
    </item>
    <?php
}