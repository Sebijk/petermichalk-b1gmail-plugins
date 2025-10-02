<fieldset>
	<legend>{lng p="eigeneseiten_name"}</legend>

	<table>
		<tr>
			<td width="48"><img src="../plugins/templates/images/eigeneseiten_logo.png" width="48" height="48" border="0" alt="" /></td>
			<td width="10">&nbsp;</td>
			<td><b>{lng p="eigeneseiten_name"}</b><br>{lng p="eigeneseiten_text"}</td>
		</tr>
	</table>
</fieldset>

<fieldset>
	<legend>{lng p="eigeneseiten_name"} {lng p="cache"}</legend>
    <p><a href="plugin.page.php?plugin=eigeneseiten&amp;action=cache&amp;sid={$sid}">Cache schreiben, bzw. vervollst&auml;ndigen.</a></p>
</fieldset>

<fieldset>
	<legend>{lng p="eigeneseiten_own"} {lng p="pages"}</legend>

	<table class="list">
		<tr>
			<th width="20">&nbsp;</th>
			<th width="30">{lng p="value"}</th>
			<th width="120">{lng p="type"}</th>
			<th width="300">{lng p="title"}</th>
			<th width="300">{lng p="eigeneseiten_linktitle"}</th>
			<th width="50">{lng p="views"}</th>
			<th width="80">{lng p="eigeneseiten_published_link_s1"}</th>
			<th width="80">{lng p="quicklinks"}</th>
			<th width="80">{lng p="eigeneseiten_published"}</th>
			<th width="80">&nbsp;</th>
		</tr>

		{foreach from=$eigeneseiten item=eigeneseite}
		{cycle name=class values="td1,td2" assign=class}
			<tr class="{$class}">
				<td><center><img src="./templates/images/ok.png" border="0" alt="{lng p="ok"}" width="16" height="16" /></center></td>
				<td align="center"><center>{$eigeneseite.id}</center></td>
				<td><center>{if $eigeneseite.typ==0}{lng p="nli"}{/if}
					{if $eigeneseite.typ==1}{lng p="li"}{/if}
					{if $eigeneseite.typ==2}{lng p="both"}{/if}</center></td>
				<td align="center">{$eigeneseite.title}</td>
				<td align="center">{$eigeneseite.link_title}</td>
				<td align="center">{$eigeneseite.views}</td>
				<td>{if $eigeneseite.published_link==1}<center><img src="./templates/images/ok.png" border="0" alt="{lng p="ok"}" width="16" height="16" /></center>{/if}
					{if $eigeneseite.published_link==0}<center><img src="./templates/images/error.png" border="0" alt="{lng p="no"}" width="16" height="16" /></center>{/if}</td>
				<td>{if $eigeneseite.typ!=0}{if $eigeneseite.published_quicklinks==1}<center><img src="./templates/images/ok.png" border="0" alt="{lng p="ok"}" width="16" height="16" /></center>{/if}
					{if $eigeneseite.published_quicklinks==0}<center><img src="./templates/images/error.png" border="0" alt="{lng p="no"}" width="16" height="16" /></center>{/if}{/if}</td>
				<td>{if $eigeneseite.published==1}<center><img src="./templates/images/ok.png" border="0" alt="{lng p="ok"}" width="16" height="16" /></center>{/if}
					{if $eigeneseite.published==0}<center><img src="./templates/images/error.png" border="0" alt="{lng p="no"}" width="16" height="16" /></center>{/if}</td>
				<td><center>
					{if $eigeneseite.published==0}<a href="plugin.page.php?plugin=eigeneseiten&action=page1&do=publish&id={$eigeneseite.id}&sid={$sid}"><img src="./templates/images/type_std.png" border="0" alt="{lng p="eigeneseiten_publish"}" width="16" height="16" /></a>{/if}
					<a href="plugin.page.php?plugin=eigeneseiten&action=page2&id={$eigeneseite.id}&sid={$sid}"><img src="./templates/images/edit.png" border="0" alt="{lng p="edit"}" width="16" height="16" /></a>
					<a href="plugin.page.php?plugin=eigeneseiten&action=page1&do=delete&id={$eigeneseite.id}&sid={$sid}" onclick="return confirm('{lng p="realdel"}');"><img src="./templates/images/delete.png" border="0" alt="{lng p="delete"}" width="16" height="16" /></a>
				</center></td>
			</tr>
		{/foreach}

	</table>
</fieldset>