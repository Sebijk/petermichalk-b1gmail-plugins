	<fieldset>
		<legend>{lng p="phpinfo_name"}</legend>

		<table>
			<tr>
				<td width="48"><img src="../plugins/templates/images/phpinfo_logo.png" width="48" height="48" border="0" alt="" /></td>
				<td width="10">&nbsp;</td>
				<td><b>{lng p="phpinfo_name"}</b><br />{lng p="phpinfo_text"}</td>
			</tr>
		</table>
	</fieldset>

	<fieldset>
		<legend>{lng p="phpinfo_name"}</legend>
		<table width="100%">
			<tr>
				<td class="td1" width="300">{lng p="phpinfo_name"}:</td>
				<td class="td2">
					<form action="plugin.page.php?plugin=phpinfo&action=page1&do=show&sid={$sid}" method="post" style="display: inline;">
						<input type="submit" class="button" value=" {lng p="phpinfo_show"} " style="margin-right: 10px;"/>
					</form>
				</td>
			</tr>
		</table>
	</fieldset>

	{if $show_phpinfo}
		<fieldset>
			<legend>{lng p="phpinfo_name"}</legend>
			<div style="max-height: 600px; overflow-y: auto; border: 1px solid #ddd; border-radius: 4px;">
				{$phpinfo_content}
			</div>
		</fieldset>
	{/if}