<!DOCTYPE html>
<html>
<head>
  {asset name="Head"}
</head>
<body id="{$BodyID}" class="{$BodyClass}">
   <div id="Frame">
      <div class="Head">
         <div class="Row">
            <span class="OpenSource"></span>
            <a href="{link path="home"}" class="Home">{link path="Vanilla Forums: Community Forums Evolved"}</a>
            <div class="Menu">
               <a href="{link path="/addons"}" title="{t c="Themes, plugins and applications for Vanilla"}">{t c="Addons"}</a>
               <a href="{link path="/discussions"}" title="{t c="Vanilla Forums Developer Community"}">{t c="Community"}</a>
               <a href="{link path="http://docs.vanillaforums.com"}" title="{t c="User and developer documentation for Vanilla"}">{t c="Documentation"}</a>
               <a href="https://blog.vanillaforums.com" title="{t c="Latest news from the Vanilla Team"}">{t c="Blog"}</a>
               <a href="{link path="/download"}" class="Download" title="{t c="Download the latest stable version of Vanilla"}">{t c="Download"}</a>
               <a href="https://vanillaforums.com" class="Hosting" title="{t c="Use Vanilla Forums in the Cloud"}">{t c="Vanilla Cloud"}<span>{t c="Start Using Vanilla today"}</span></a>
            </div>
         </div>
      </div>
      <div id="Body">
         <div class="BreadcrumbsWrapper">
            <div class="Row">
               {breadcrumbs}
               <div class="MeModuleWrap">
                  {module name="MeModule" CssClass="Inline FlyoutRight"}
               </div>
            </div>
         </div>

         <div class="Row">
            <div class="Column PanelColumn" id="Panel">
               {asset name="Panel"}
            </div>
            <div class="Column ContentColumn" id="Content">
               {if InSection(array('DiscussionList', 'CategoryList'))}
                  {searchbox_advanced}
               {/if}
               {asset name="Content"}
            </div>
         </div>
      </div>
      <div id="Foot">
         <div class="Row">
            <a href="{vanillaurl}" class="PoweredByVanilla" title="{t c="Community Software by Vanilla Forums"}">{t c="Powered by Vanilla"}</a>
            {asset name="Foot"}
         </div>
      </div>
   </div>
   {event name="AfterBody"}
</body>
</html>
