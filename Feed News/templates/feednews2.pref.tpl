<fieldset>
	<legend>{lng p="feednews_name"}</legend>
	
	<table>
		<tr>
			<td width="48"><img src="../plugins/templates/images/feednews_logo.png" width="48" height="48" border="0" alt="" /></td>
			<td width="10">&nbsp;</td>
			<td><b>{lng p="feednews_name"}</b><br />{lng p="feednews_text"}</td>
		</tr>
	</table>
</fieldset>

<fieldset>
	<legend>{lng p="feedparser_rss"}</legend>

	<table class="list">
		<tr>
			<th width="20">&nbsp;</th>
			<th width="40">{lng p="id"}</th>
			<th width="40">{lng p="id"}2</th>
			<th>{lng p="feedparser_rss"}</th>
			<th width="150">{lng p="category"}</th>
			<th width="150">{lng p="feednews_link"}</th>
			<th width="150">{lng p="date"}</th>
			<th width="100">&nbsp;</th>
		</tr>

		{foreach from=$rss item=rssitem}
		{cycle name=class values="td1,td2" assign=class}
			<tr class="{$class}">
				<td><img src="./templates/images/ok.png" border="0" alt="{lng p="ok"}" width="16" height="16" /></td>
				<td align="center"><center>{$rssitem.id}</center></td>
				<td align="center"><center><a href="plugin.page.php?plugin=feednews_cron&action=page2&id={$rssitem.rss}&sid={$sid}">{$rssitem.rss}</a></center></td>
				<td align="center">{$rssitem.title}</td>
				<td align="center"><a href="plugin.page.php?plugin=feednews&action=page2&cat={$rssitem.category}&sid={$sid}">{$rssitem.category}</a></td>
				<td align="center"><center><a href="{$rssitem.link}" target="_blank">{$rssitem.link|truncate:30}</a></center></td>
				<td align="center"><center><img src="./templates/images/ok.png" border="0" alt="{lng p="ok"}" width="16" height="16" align="absmiddle" /> ({$rssitem.datum|date_format:"%d.%m.%Y %H:%M:%S"})</center></td>
				<td><center>
					<a href="../index.php?action=news&id={$rssitem.id}" target="_blank" title="{lng p="execute"}"><img src="{$tpldir}images/go.png" border="0" alt="{lng p="execute"}" width="16" height="16" /></a>
					<a href="plugin.page.php?plugin=feednews&action=page3&id={$rssitem.id}&sid={$sid}"><img src="./templates/images/edit.png" border="0" alt="{lng p="edit"}" width="16" height="16" /></a>
					<a href="plugin.page.php?plugin=feednews&action=page2&do=delete&id={$rssitem.id}&sid={$sid}" onclick="return confirm('{lng p="realdel"}');"><img src="./templates/images/delete.png" border="0" alt="{lng p="delete"}" width="16" height="16" /></a></center>
				</td>
			</tr>
		{/foreach}

	</table>
</fieldset>