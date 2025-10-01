<form action="plugin.page.php?plugin=addnotes&action=page1&sid={$sid}" method="post" onsubmit="spin(this)">

	<fieldset>
		<legend>{lng p="addnotes_name"}</legend>

		<table>
			<tr>
				<td width="48"><img src="../plugins/templates/images/addnotes_logo.png" width="48" height="48" border="0" alt="" /></td>
				<td width="10">&nbsp;</td>
				<td><b>{lng p="addnotes_name"}</b><br>{lng p="addnotes_text"}</td>
			</tr>
		</table>
	</fieldset>

	{if $tpl_use==3}
	<fieldset>
		<legend>{lng p="add"}</legend>
			<center><img src="./templates/images/ok.png" border="0" alt="{lng p="ok"}" width="16" height="16" /> {lng p="success"}!</center>
	</fieldset>
	{/if}

	<fieldset>
		<legend>{lng p="groups"}</legend>

		<table width="100%">
		<tr>
			<td class="td1" width="150">{lng p="groups"}:</td>
			<td class="td2">
				<select style="width: 180px;" name="gruppe" {if $tpl_use>=1 AND $tpl_use!=3} disabled="disable"{/if}>
					<option value="-1" {if $selected_gruppe==-1} selected="selected"{/if}>{lng p="all"}</option>
					{foreach from=$gruppen item=gruppe}
					<option value="{$gruppe.id}"{if $selected_gruppe==$gruppe.id} selected="selected"{/if}>{text value=$gruppe.titel}</option>
					{/foreach}
				</select>
				<input type="hidden" value="{$selected_gruppe}" name="gruppe_hidden">
				<a href="plugin.page.php?plugin=addnotes&action=page2&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="{lng p="help"}" width="16" height="16" /></a>
			</td>
		</tr>
		</table>

		{if $tpl_use==0 or $tpl_use==3}
			<p align="right"><input class="button" type="submit" value=" {lng p="next"} " /></p>
		{/if}
	</fieldset>

	{if $tpl_use>=1 and $tpl_use!=3}
	<fieldset>
		<legend>{lng p="users"}</legend>

		<table>
		<tr>
			<td class="td1" width="150">{lng p="users"}:</td>
			<td class="td2">
				<select style="width: 180px;" name="user" {if $tpl_use>=2} disabled="disable"{/if}>
					<option value="-1" {if $selected_user==-1} selected="selected"{/if}>{lng p="all"}</option>
					{foreach from=$users item=user}
					<option value="{$user.id}"{if $selected_user==$user.id} selected="selected"{/if}>{text value=$user.email}</option>
					{/foreach}
				</select>
				<input type="hidden" value="{$selected_user}" name="user_hidden">
				<a href="plugin.page.php?plugin=addnotes&action=page2&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="{lng p="help"}" width="16" height="16" /></a>
			</td>
		</tr>
		</table>

		{if $tpl_use==1}
			<p align="right"><input class="button" type="submit" value=" {lng p="next"} " /></p>
		{/if}
	</fieldset>
	{/if}

	{if $tpl_use>=2 and $tpl_use!=3}
	<fieldset>
		<legend>{lng p="notes"}</legend>

		<table>
		<tr>
			<td class="td1">{lng p="priority"}:</td>
			<td class="td2">
				<select name="priority" style="width: 180px;" id="priority">
					<option value="1">{lng p="prio_1"}</option>
					<option value="0">{lng p="prio_0"}</option>
					<option value="-1">{lng p="prio_-1"}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="td1">{lng p="text"}:</td>
			<td class="td2"><textarea class="textInput" name="text" id="text" cols="100" rows="5"></textarea></td>
		</tr>
		</table>

		<p align="right"><input class="button" type="submit" value=" {lng p="execute"} " /></p>
	</fieldset>
	{/if}
</form>