<?php if (!defined('APPLICATION')) exit(); ?>
<div class="Choose">
    <div>
        <div class="Head">
            <?php
            echo Anchor(
                Img('/applications/vforg/design/images/splash-logo.png', array('height'=>'90', 'width' => '202', 'alt' => 'Vanilla Forums')),
                '/vforg/home/choose'
            );
            ?>
            <h1>The <em>sweetest</em> forum on the web.</h1>
        </div>
        <h2>Over 500,000 sites have used Vanilla Forums to grow <br />community around their website, brand, or business. For free.</h2>
        <div class="Host">
            <?php
                echo Anchor(
                    '<strong>Simple</strong>'
                    ."<span>If you are a business owner, brand manager, or a non-technical person and you want your community running in just a few minutes, <em>click here</em>.</span>"
                    .Img('/applications/vforg/design/images/splash-person.png', array('alt' => 'Simple', 'height' => '108', 'width' => '100')),
                    'http://vanillaforums.com/?ref=vforg'
                );
            ?>
        </div>
        <div class="Download">
            <?php
                echo Anchor(
                    '<strong>Expert</strong>'
                    ."<span>If you are an IT manager, developer, or a do-it-yourselfer and want to install Vanilla Forums in your own environment, <em>click here</em>.</span>"
                    .Img('/applications/vforg/design/images/splash-tools.png', array('alt' => 'Expert', 'height' => '108', 'width' => '105')),
                    '/'
                );
            ?>
        </div>
        <div class="Help">Not sure which version is best for you? <a href="&#109;&#97;&#105;&#108;&#116;&#111;&#58;&#115;&#117;&#112;&#112;&#111;&#114;&#116;&#64;&#118;&#97;&#110;&#105;&#108;&#108;&#97;&#102;&#111;&#114;&#117;&#109;&#115;&#46;&#99;&#111;&#109;">Contact us</a>.
    </div>
</div>