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

{if $id==true}
<form action="plugin.page.php?plugin=feednews&action=page3&do=update&sid={$sid}&id={$rss.id}" method="post" onsubmit="spin(this)">
{/if}
{if $id==false}
<form action="plugin.page.php?plugin=feednews&action=page3&do=save&sid={$sid}" method="post" onsubmit="spin(this)">
{/if}

	<fieldset>
		<legend>{lng p="feednews_rss"}</legend>

		<table>
			<tr>
				<td class="td1" width="220">* {lng p="feednews_rss"}:</td>
				<td class="td2"><input type="text" name="rss" value="{$rss.rss}" size="4" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="td1" width="220">* {lng p="title"}:</td>
				<td class="td2"><input type="text" name="title" value="{$rss.title}" size="75" /></td>
			</tr>
			<tr>
				<td class="td1" width="220">* {lng p="text"}:</td>
				<td class="td2"><textarea name="text" cols="73" rows="15">{$rss.text}</textarea></td>
			</tr>
			<tr>
				<td class="td1" width="220">* {lng p="date"}:</td>
				<td class="td2"><input type="text" name="datum" value="{$rss.datum}" size="75" /></td>
			</tr>
			<tr>
				<td class="td1" width="220">* {lng p="category"}:</td>
				<td class="td2"><input type="text" name="category" value="{$rss.category}" size="75" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="td1" width="220">* {lng p="feednews_link"}:</td>
				<td class="td2"><input type="text" name="link" value="{$rss.link}" size="75" /></td>
			</tr>
			<tr>
				<td class="td1" width="220">* {lng p="feednews_picture"}:</td>
				<td class="td2"><input type="text" name="picture" value="{$rss.picture}" size="75" /></td>
			</tr>
		</table>

		<p align="right">
			<input type="submit" value=" {lng p="save"} " class="button"/>
		</p>
	</fieldset>
</form>