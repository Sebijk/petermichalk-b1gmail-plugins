<fieldset>
	<legend>{lng p="lexikon_name"}</legend>

	<table>
		<tr>
			<td width="48"><img src="../plugins/templates/images/lexikon_logo.png" width="48" height="48" border="0" alt="" /></td>
			<td width="10">&nbsp;</td>
			<td><b>{lng p="lexikon_name"}</b><br>{lng p="lexikon_text"}</td>
		</tr>
	</table>
</fieldset>

<form action="plugin.page.php?plugin=lexikon&action=page1&sid={$sid}" method="post" onsubmit="spin(this)" name="f1">
<input type="hidden" name="sortBy" id="sortBy" value="{$sortBy}" />
<input type="hidden" name="sortOrder" id="sortOrder" value="{$sortOrder}" />
<fieldset>
	<legend>{lng p="lexikon_name"}</legend>

	<table class="list">
		<tr>
			<th width="20">&nbsp;</th>
			<th width="30"><a href="javascript:updateSort('id');">{lng p="id"} {if $sortBy=='id'}<img src="{$tpldir}images/sort_{$sortOrder}.png" border="0" alt="" width="7" height="6" align="absmiddle" />{/if}</a></th>
			<th width="120"><a href="javascript:updateSort('cat');">{lng p="category"} {if $sortBy=='cat'}<img src="{$tpldir}images/sort_{$sortOrder}.png" border="0" alt="" width="7" height="6" align="absmiddle" />{/if}</a></th>
			<th width="300"><a href="javascript:updateSort('title');">{lng p="title"} {if $sortBy=='title'}<img src="{$tpldir}images/sort_{$sortOrder}.png" border="0" alt="" width="7" height="6" align="absmiddle" />{/if}</a></th>
			<th width="80"><a href="javascript:updateSort('published');">{lng p="lexikon_published"} {if $sortBy=='published'}<img src="{$tpldir}images/sort_{$sortOrder}.png" border="0" alt="" width="7" height="6" align="absmiddle" />{/if}</a></th>
			<th width="80">&nbsp;</th>
		</tr>

		{foreach from=$lexikon item=lex}
		{cycle name=class values="td1,td2" assign=class}
			<tr class="{$class}">
				<td><center><img src="./templates/images/ok.png" border="0" alt="{lng p="ok"}" width="16" height="16" /></center></td>
				<td align="center"><center>{$lex.id}</center></td>
				<td><center>{$lex.cat}</td>
				<td align="center">{$lex.title}</td>
				<td>{if $lex.published==1}<center><img src="./templates/images/ok.png" border="0" alt="{lng p="ok"}" width="16" height="16" /></center>{/if}
					{if $lex.published==0}<center><img src="./templates/images/error.png" border="0" alt="{lng p="no"}" width="16" height="16" /></center>{/if}</td>
				<td><center>
					{if $lex.published==0}<a href="plugin.page.php?plugin=lexikon&action=page1&do=publish&id={$lex.id}&sid={$sid}"><img src="./templates/images/type_std.png" border="0" alt="{lng p="lexikon_publish"}" width="16" height="16" /></a>{/if}
					<a href="plugin.page.php?plugin=lexikon&action=page2&id={$lex.id}&sid={$sid}"><img src="./templates/images/edit.png" border="0" alt="{lng p="edit"}" width="16" height="16" /></a>
					<a href="plugin.page.php?plugin=lexikon&action=page1&do=delete&id={$lex.id}&sid={$sid}" onclick="return confirm('{lng p="realdel"}');"><img src="./templates/images/delete.png" border="0" alt="{lng p="delete"}" width="16" height="16" /></a>
				</center></td>
			</tr>
		{/foreach}

	</table>
</fieldset>
</form>