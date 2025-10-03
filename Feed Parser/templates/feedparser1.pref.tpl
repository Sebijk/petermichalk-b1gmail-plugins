<fieldset>
	<legend>{lng p="feedparser_name"}</legend>
	
	<table>
		<tr>
			<td width="48"><img src="../plugins/templates/images/feedparser_logo.png" width="48" height="48" border="0" alt="" /></td>
			<td width="10">&nbsp;</td>
			<td><b>{lng p="feedparser_name"}</b><br />{lng p="feedparser_text"}</td>
		</tr>
	</table>
</fieldset>

<fieldset>
	<legend>{lng p="feedparser_rss"}</legend>

	<table class="list">
		<tr>
			<th width="20">&nbsp;</th>
			<th width="40">{lng p="id"}</th>
			<th>{lng p="title"}</th>
			<th>{lng p="feedparser_rss"}</th>
			<th width="100">{lng p="category"}</th>
			<th width="24">&nbsp;</th>
			<th width="250">{lng p="lastfetch"}</th>
			<th width="100">{lng p="interval"}</th>
			<th width="100">&nbsp;</th>
		</tr>

		{foreach from=$rss item=rssitem}
		{cycle name=class values="td1,td2" assign=class}
			<tr class="{$class}">
				<td><img src="./templates/images/ok.png" border="0" alt="{lng p="ok"}" width="16" height="16" /></td>
				<td align="center"><center>{$rssitem.id}</center></td>
				<td align="center">{$rssitem.title}</td>
				<td align="center">{$rssitem.rss}</td>
				<td align="center">{$rssitem.category}</td>
				<td align="center"><center>{if $rssitem.nopicture}<img src="./templates/images/error.png" border="0" alt="{lng p="error"}" width="16" height="16" align="absmiddle" />{else}<img src="./templates/images/ok.png" border="0" alt="{lng p="ok"}" width="16" height="16" align="absmiddle" />{/if}</center></td>
				<td align="center"><center>{if $rssitem.lastfetch!=0}<img src="./templates/images/ok.png" border="0" alt="{lng p="ok"}" width="16" height="16" align="absmiddle" /> ({$rssitem.lastfetch|date_format:"%d.%m.%Y %H:%M:%S"}){else}<img src="./templates/images/error.png" border="0" alt="{lng p="error"}" width="16" height="16" align="absmiddle" />{/if}</center></td>
				<td align="center"><center>{$rssitem.interval}</center></td>
				<td><center>
					<a href="{$rssitem.rss}" target="_blank" title="{lng p="execute"}"><img src="{$tpldir}images/go.png" border="0" alt="{lng p="execute"}" width="16" height="16" /></a>
					<a href="plugin.page.php?plugin=feedparser&action=page2&id={$rssitem.id}&sid={$sid}"><img src="./templates/images/edit.png" border="0" alt="{lng p="edit"}" width="16" height="16" /></a>
					<a href="plugin.page.php?plugin=feedparser&action=page1&do=delete&id={$rssitem.id}&sid={$sid}" onclick="return confirm('{lng p="realdel"}');"><img src="./templates/images/delete.png" border="0" alt="{lng p="delete"}" width="16" height="16" /></a></center>
				</td>
			</tr>
		{/foreach}

	</table>
</fieldset>