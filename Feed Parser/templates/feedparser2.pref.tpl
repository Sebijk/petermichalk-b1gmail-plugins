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


{if $id==true}
<form action="plugin.page.php?plugin=feedparser&action=page2&do=update&sid={$sid}&id={$rss.id}" method="post" onsubmit="spin(this)">
{/if}
{if $id==false}
<form action="plugin.page.php?plugin=feedparser&action=page2&do=save&sid={$sid}" method="post" onsubmit="spin(this)">
{/if}

	<fieldset>
		<legend>{lng p="feedparser_rss"}</legend>

		<table>
			<tr>
				<td class="td1" width="220">* {lng p="title"}:</td>
				<td class="td2"><input type="text" name="title" value="{$rss.title}" size="75" /></td>
			</tr>
			<tr>
				<td class="td1" width="220">* {lng p="feedparser_rss"}:</td>
				<td class="td2"><input type="text" name="rss" value="{$rss.rss}" size="75" /></td>
			</tr>
			<tr>
				<td class="td1" width="220">{lng p="category"}:</td>
				<td class="td2"><input type="text" name="category" value="{$rss.category}" size="75" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="td1" width="220">{lng p="interval"}:</td>
				<td class="td2"><input type="text" name="interval" value="{$rss.interval}" size="4" /></td>
			</tr>
			<tr>
				<td class="td1" width="220">{lng p="feedparser_bulk"}:</td>
				<td class="td2"><input type="text" name="bulk" value="{$rss.bulk}" size="4" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="td1" width="300">{lng p="feedparser_defaultcategory"}</td>
				<td class="td2"><input name="defaultcategory" {if $rss.defaultcategory==1}checked="checked"{/if} type="checkbox"/></td>
			</tr>
			<tr>
				<td class="td1" width="300">{lng p="feedparser_nopicture"}</td>
				<td class="td2"><input name="nopicture" {if $rss.nopicture==1}checked="checked"{/if} type="checkbox"/></td>
			</tr>
			<tr>
				<td class="td1" width="220">{lng p="expression"}:</td>
				<td class="td2"><input type="text" name="regex" value='{$rss.regex}' size="75" /></td>
			</tr>
		</table>

		<p align="right">
			<input type="submit" value=" {lng p="save"} " class="button"/>
		</p>
	</fieldset>
</form>