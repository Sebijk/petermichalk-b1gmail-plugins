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

<form action="plugin.page.php?plugin=feedparser&action=page3&do=save&sid={$sid}" method="post" onsubmit="spin(this)">

	<fieldset>
		<legend>{lng p="prefs"}</legend>

		<table>
			<tr>
				<td class="td1" width="300">{lng p="feedparser_oncron"}</td>
				<td class="td2"><input name="oncron" {if $oncron==1}checked="checked"{/if} type="checkbox" /></td>
			</tr>
			<tr>
				<td class="td1" width="300">{lng p="feedparser_onfilehandler"}</td>
				<td class="td2"><input name="filehandler" {if $filehandler==1}checked="checked"{/if} type="checkbox" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="td1" width="220">* {lng p="feedparser_maxdate"}:</td>
				<td class="td2"><input type="text" name="maxdate" value="{$maxdate}" size="3" /></td>
			</tr>
		</table>

		<p align="right">
			<input type="submit" value=" {lng p="save"} " class="button"/>
		</p>
	</fieldset>
</form>