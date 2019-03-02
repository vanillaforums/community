{assign
    "linkFormat"
    "<div class='Navigation-linkContainer'>
        <a href='%url' class='Navigation-link %class'>
            %text
        </a>
    </div>"
}

<header class="Header Header-branding">
    <div class="Container">
        <a href="{home_link format="%url"}" class="Header-logo">
            {logo}
        </a>
        <div class="Header-spacer"></div>
        <a class="Header-brandLink" href="https://blog.vanillaforums.com">Blog</a>
        <a class="Header-brandLink" href="https://docs.vanillaforums.com">Documentation</a>
        <div class="Button-Controls Header-buttons">
            <a class="Button Primary" href="http://pages.vanillaforums.com/demo-request-vanilla-forums?utm_source=vanilladocs&utm_medium=cta&utm_campaign=demo-request">Try Vanilla Cloud</a>
        </div>
    </div>
</header>
<header id="MainHeader" class="Header Header-vanilla">
    <div class="Container">
        <div class="row">
            <div class="Hamburger">
                {include file="partials/hamburger.html"}
            </div>
            <nav class="Header-desktopNav">
                {categories_link format=$linkFormat}
                {discussions_link format=$linkFormat}
                {activity_link format=$linkFormat}
                {custom_menu format=$linkFormat}
            </nav>
            <a href="{home_link format="%url"}" class="Header-logo mobile">
                {mobile_logo}
            </a>
            <div class="Header-right">
                <div class="MeBox-header">
                    {module name="MeModule" CssClass="FlyoutRight"}
                </div>
                {if $User.SignedIn}
                    <button class="mobileMeBox-button">
                        {module name="UserPhotoModule"}
                    </button>
                {/if}
            </div>
        </div>
    </div>
    <nav class="Navigation needsInitialization js-nav">
        <div class="Container">
            {if $User.SignedIn}
                <div class="Navigation-row NewDiscussion">
                    <div class="NewDiscussion mobile">
                        {module name="NewDiscussionModule"}
                    </div>
                </div>
            {else}
                <div class="Navigation-row">
                    <div class="SignIn mobile">
                        {module name="MeModule"}
                    </div>
                </div>
            {/if}
            {categories_link format=$linkFormat}
            {discussions_link format=$linkFormat}
            {activity_link format=$linkFormat}
            {custom_menu format=$linkFormat}
        </div>
    </nav>
    <nav class="mobileMebox js-mobileMebox needsInitialization">
        <div class="Container">
            {module name="MeModule"}
            <button class="mobileMebox-buttonClose Close">
                <span>×</span>
            </button>
        </div>
    </nav>
</header>
