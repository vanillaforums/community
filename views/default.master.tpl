<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-ca">
{asset name='Head' tag='head'}
<body id="{$BodyIdentifier|escape}" class="{$CssClass|escape}">
<div class="Header">
	<div class="container_16 clearfix">
		<div class="grid_3">
			<h1><a href="{url dest='/'}"><span>Vanilla</span></a></h1>
		</div>
		<div class="grid_13">
			<ul>
				<li class="Home">{anchor text="Home" destination="/"}</li>
				<li class="Blog">{anchor text="Blog" destination="/blog"}</li>
				<li class="Addons">{anchor text="Addons" destination="/addons"}</li>
				<li>{anchor text="Community" destination="/discussions"}</li>
				<li>{anchor text="Documentation" destination="/docs"}</li>
				<li class="Hosting">{anchor text="Hosting" destination="/hosting"}</li>
				<li class="Download">{anchor text="Download" destination="/download"}</li>
			</ul>
		</div>
	</div>
</div>
{asset name='Splash'}
{asset name='Content'}
{asset name='Foot'}
</body>
</html>