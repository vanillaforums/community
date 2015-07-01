<?php 
    if (!defined('APPLICATION')) exit();
    $Access = ($this->Data('Slug') ? urlencode($this->Data('Slug')) : $AddonID);
?>
    <description><?php echo Gdn_Format::Text($this->Head->Title()); ?></description>
    <language><?php echo Gdn::Config('Garden.Locale', 'en-US'); ?></language>
    <atom:link href="<?php echo Url('/addon/'.$Access.'/follow.rss'); ?>" rel="self" type="application/rss+xml" />
<?php
$SlugBase = AddonModel::Slug($this->Data, FALSE);
foreach ($this->Data('Versions') as $Version) {
    $VersionSlug = urlencode($SlugBase.'-'.$Version['Version']);
    ?>
    <item>
        <title><?php echo Gdn_Format::Text($this->Data('Name').' '.$Version['Version']); ?></title>
        <link><?php echo Url('/addon/'.$VersionSlug, TRUE); ?></link>
        <pubDate><?php echo date(DATE_RSS, Gdn_Format::ToTimeStamp($Version['DateInserted'])); ?></pubDate>
        <dc:creator><?php echo Gdn_Format::Text($this->Data('InsertName')); ?></dc:creator>
        <guid isPermaLink="true"><?php echo Url('/addons/addon/'.$VersionSlug, TRUE); ?></guid>
        <description><![CDATA[<?php echo Gdn_Format::Html($this->Data('Description')); ?>]]></description>
    </item>
    <?php
}