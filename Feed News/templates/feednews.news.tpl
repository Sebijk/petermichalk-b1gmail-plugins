<table class="nliTable">
	<tbody><tr>
		<td class="nliTD">
<div class="news_{$cat} newsdiv_{$cat}">
	<h2 class="newsh2">
		<a href="">{$pageTitle}</a>
	</h2>
</div>
<div>
<div style="float:left">
	{if $cat!="nachrichten"}
		<a title="Zur&uuml;ck" href="news">← Zur&uuml;ck</a>
	{/if}
</div>
<div style="float:right">
	<a class="a_schlagzeilen" href="index.php?action=news&amp;cat=schlagzeilen">Schlagzeilen</a> | <a class="a_lifestyle" href="index.php?action=news&amp;cat=lifestyle">Lifestyle</a> | <a  class="a_politik" href="index.php?action=news&amp;cat=politik">Politik</a> | <a class="a_wirtschaft" href="index.php?action=news&amp;cat=wirtschaft">Wirtschaft</a> | <a class="a_technik" href="index.php?action=news&amp;cat=technik">Technik</a> | <a class="a_unterhaltung" href="index.php?action=news&amp;cat=unterhaltung">Unterhaltung</a> | <a class="a_kino" href="cinema">Kino</a> | <a class="a_sport" href="index.php?action=news&amp;cat=sport">Sport</a> | <a class="a_panorama" href="index.php?action=news&amp;cat=panorama">Panorama</a> | <a onclick="return false;" onmouseup="showSearchPopup()" href="javascript:void(0);">Suche</a> | <a href="index.php?action=news&amp;showrss=1">RSS</a>
</div>
</div>
<br/><br/>
{if $empty}
	{if $suche}
	<br/><center><strong>{lng p="feednews_nosearch"}</strong></center><br/>
	{else}
	<br/><center><strong>{lng p="feednews_empty"}</strong></center><br/>
	{/if}
{else}
<article>
{foreach from=$news item=rssitem}
{cycle name=class values=",newsdiv_`$cat`,"" assign=class}
	{if $rssitem.picture=="" OR $rssitem.nopicture}
	<div class="newsdiv {$class}">
	<div class="newsspan2">
		<table style="width: 100%;">
		<tr>
			<td><h2 style="margin: 0;">
			{if $rssitem.nolayer}
				<a class="title" rel="nofollow" href="index.php?action=news&amp;link={$rssitem.link}" title="{$rssitem.title}"><img width="12px" height="11px" src="./plugins/templates/images/feednews_extlink.png" border="0" alt="Externer Link"/> {$rssitem.title} <img width="12px" height="11px" src="./plugins/templates/images/feednews_extlink.png" alt="Externer Link" border="0"/></a>
			{else}
				<a class="title" rel="nofollow" onclick="return false;" onmouseup="openOverlay('{$rssitem.link}', '', 800, 600, false);" href="javascript:void(0);" title="{$rssitem.title}">{$rssitem.title}</a>
			{/if}
			</h2></td>
		</tr>
		<tr>
			<td><small><a rel="nofollow" href="index.php?action=news&amp;link={$rssitem.link}" title="{$rssitem.rsstitle}">{$rssitem.rsstitle} <img width="12px" height="11px" src="./plugins/templates/images/feednews_extlink.png" alt="Externer Link" border="0"/></a> - Vor {$rssitem.datum}</small></td>
		</tr>
		{if $rssitem.text!="" }
		<tr>
			<td>{$rssitem.text}</td>
		</tr>
		{/if}
		</table>
	</div>
	</div>
	{else}
	<div class="newsdiv {$class}">
	<div class="newsspan1">
		<a rel="nofollow" href="index.php?action=news&amp;link={$rssitem.link}" title="{$rssitem.title}">
			<img width="80px" height="60px" src="index.php?action=news&do=getpic&l={$rssitem.picture}" alt="{$rssitem.rsstitle}" border="0"/>
		</a><br/><small><a rel="nofollow" href="index.php?action=news&amp;link={$rssitem.link}" title="{$rssitem.rsstitle}">{$rssitem.rsstitle} <img width="12px" height="11px" src="./plugins/templates/images/feednews_extlink.png" border="0"  alt="Externer Link"/></a></small>
	</div>
	<div class="newsspan2">
		<table style="width:100%;">
		<tr>
			<td><h2 style="margin: 0;">
			{if $rssitem.nolayer}
				<a class="title" rel="nofollow" href="index.php?action=news&amp;link={$rssitem.link}" title="{$rssitem.title}"><img width="12px" height="11px" src="./plugins/templates/images/feednews_extlink.png" border="0" alt="Externer Link"/> {$rssitem.title} <img width="12px" height="11px" src="./plugins/templates/images/feednews_extlink.png" alt="Externer Link" border="0"/></a>
			{else}
				<a class="title" rel="nofollow" onclick="return false;" onmouseup="openOverlay('{$rssitem.link}', '', 800, 600, false);" href="javascript:void(0);" title="{$rssitem.title}">{$rssitem.title}</a>
			{/if}
			</h2></td>
		</tr>
		<tr>
			<td><small><a rel="nofollow" href="index.php?action=news&amp;link={$rssitem.link}" title="{$rssitem.rsstitle}">{$rssitem.rsstitle} <img width="12px" height="11px" src="./plugins/templates/images/feednews_extlink.png" alt="Externer Link" border="0"/></a> - Vor {$rssitem.datum}</small></td>
		</tr>
		<tr>
			<td>{$rssitem.text}</td>
		</tr>
		</table>
	</div>
	</div>
	{/if}
{/foreach}
</article>
<div style="float:right;padding-top:3px;">
{pageNav page=$pageNo pages=$pageCount on=" <span class=\"pageNav\"><b>[.t]</b></span> " off=" <span class=\"pageNav\"><a href=\"index.php?action=news&amp;cat=`$cat2`&amp;c=`$c`&amp;page=.s\">.t</a></span> "}&nbsp;
</div>
{/if}
		</td>
	</tr>
</tbody></table>

<table width="100%" cellspacing="0" cellpadding="0" onmouseout="disableHide=false;" onmouseover="disableHide=true;" style="height: 26px; display: none;" id="searchPopup">
<tbody><tr>
	<td>
		<table width="100%" cellspacing="0" cellpadding="0">
		<tbody><tr>
			<td width="70" align="right">Suche: &nbsp;</td>
			<td align="center">
				<form action="index.php?action=news" method="post">
					<input style="width:90%" name="s" id="s"/>
				</form>
			</td>
		</tr>
		</tbody></table>
	</td>
</tr>
</tbody>
</table>