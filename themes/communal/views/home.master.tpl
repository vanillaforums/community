<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {asset name="Head"}
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body id="{$BodyID}" class="{$BodyClass}">
    <!--[if lt IE 8]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <div class="github-fork-ribbon-wrapper right">
        <div class="github-fork-ribbon" style="background-color:#000;box-shadow:none;">
            <a href="https://github.com/vanillaforums/vanilla">Fork me on GitHub</a>
        </div>
    </div>

    <nav class="navbar navbar-static-top" role="navigation">
      <div class="container">

        <a class="navbar-brand" href="{link path="home"}">{logo}</a>

        <ul class="nav">

        </ul>

      </div>
    </nav>

    <section class="container">
      {asset name="Content"}
    </section>

    {asset name="Foot"}
    {event name="AfterBody"}
  </body>
</html>
