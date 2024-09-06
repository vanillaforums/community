<!DOCTYPE html>
<html lang="{$CurrentLocale.Key}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {asset name="Head"}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i" rel="stylesheet">
</head>

{assign var="SectionGroups" value=(isset($Groups) || isset($Group))}

<body id="{$BodyID}" class="
    {$BodyClass}

    {if $User.SignedIn}
        UserLoggedIn
    {else}
        UserLoggedOut
    {/if}

    {if inSection('Discussion') and $Page gt 1}
        isNotFirstPage
    {/if}

    {if inSection('Group') && !isset($Group.Icon)}
        noGroupIcon
    {/if}

    locale-{$CurrentLocale.Lang}
">


    <!--[if lt IE 9]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <div class="Frame">
        <div class="Frame-top">
            {include file="partials/header.tpl"}
            <div class="Frame-body">
                <div class="Frame-content">
                    <div class="Container">
                        <div class="Frame-contentWrap">
                            <div class="Frame-details">
                                <!-- Hide the breadcrumbs when we are on the discussions home page -->
                                {if !$isHomepage && !(inSection('Discussions') && $Category === false)}
                                    <div class="Frame-row">
                                        <nav class="BreadcrumbsBox">
                                            {breadcrumbs}
                                        </nav>
                                    </div>
                                {/if}
                                <div class="Frame-row SearchBoxMobile">
                                    {if !$SectionGroups && !inSection(["SearchResults"])}
                                        <div class="SearchBox js-sphinxAutoComplete" role="search">
                                            {module name="AdvancedSearchModule"}
                                        </div>
                                    {/if}
                                </div>
                                <div class="Frame-row">
                                    <main class="Content MainContent">
                                        {if inSection("Profile")}
                                            <div class="Profile-header">
                                                <div class="Profile-photo">
                                                    <div class="PhotoLarge">
                                                        {module name="UserPhotoModule"}
                                                    </div>
                                                </div>
                                                <div class="Profile-name">
                                                    <h1 class="Profile-username">
                                                        {$Profile.Name|escape:'html'}
                                                    </h1>
                                                    {if isset($Rank)}
                                                        <span class="Profile-rank">{$Rank.Label|escape:'html'}</span>
                                                    {/if}
                                                </div>
                                            </div>
                                        {/if}
                                        {asset name="Content"}
                                        {event name="AfterBody"}
                                    </main>
                                    <aside class="Panel Panel-main">
                                        {if !$SectionGroups}
                                            <div class="SearchBox js-sphinxAutoComplete" role="search">
                                                {searchbox}
                                            </div>
                                        {/if}
                                        {asset name="Panel"}
                                        <div class="Box PanelCTA">
                                            <div class="PanelCTA-logo">
                                                {include file="partials/logo.html"}
                                            </div>
                                            <p class="PanelCTA-text">Tired of hosting yourself? Upgrade to the Cloud!</p>
                                            <div class="BoxButtons"><a class="Button Primary" href="https://www.higherlogic.com/vanilla-demo-request/">Learn More</a></div>
                                        </div>
                                    </aside>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="Frame-footer">
            {asset name="Foot"}
            {include file="partials/footer.tpl"}
        </div>
    </div>
</body>

</html>
