<fieldset>
	<legend>{lng p="checkpassword_name"}</legend>
	<table>
		<tr>
			<td width="48"><img src="../plugins/templates/images/checkpassword_logo.png" width="48" height="48" border="0" alt="" /></td>
			<td width="10">&nbsp;</td>
			<td><b>{lng p="checkpassword_name"}</b><br />{lng p="checkpassword_text"}</td>
		</tr>
	</table>
</fieldset>

<form action="plugin.page.php?plugin=checkpassword&action=page2&sid={$sid}" method="post" onsubmit="spin(this)">
{if $tpl_use==3}
<fieldset>
	<legend>{lng p="add"}</legend>
	{if $tpl_email_locked==false}
		<center><img src="./templates/images/ok.png" border="0" alt="{lng p="ok"}" width="16" height="16" /> {lng p="success"}!</center>
	{else}
		<center><img src="./templates/images/error.png" border="0" alt="{lng p="error"}" width="16" height="16" /> {lng p="error"}!<br />{lng p="addresstaken"}</center>
	{/if}
</fieldset>
{/if}

<fieldset>
	<legend>{lng p="groups"}</legend>
		<table width="100%">
			<tr>
				<td class="td1" width="150">{lng p="groups"}:</td>
				<td class="td2"><select style="width: 180px;" name="gruppe" {if $tpl_use>=1 AND $tpl_use!=3} disabled="disable"{/if}>
					<option value="-1" {if $selected_gruppe==-1} selected="selected"{/if}>{lng p="all"}</option>
				{foreach from=$gruppen item=gruppe}
					<option value="{$gruppe.id}"{if $selected_gruppe==$gruppe.id} selected="selected"{/if}>{text value=$gruppe.titel}</option>
				{/foreach}
				</select>
				<input type="hidden" value="{$selected_gruppe}" name="gruppe_hidden"> <a href="plugin.page.php?plugin=checkpassword&action=page3&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="Bearbeiten" width="16" height="16" /></a></td>
			</tr>
		</table>

		{if $tpl_use==0 or $tpl_use==3}
		<p align="right">
			<input type="submit" class="button" value=" {lng p="next"} " />
		</p>
		{/if}
</fieldset>

{if $tpl_use>=1 and $tpl_use!=3}
<fieldset>
	<legend>{lng p="users"}</legend>

		<table>
			<tr>
				<td class="td1" width="150">{lng p="users"}:</td>
				<td class="td2"><select style="width: 180px;" name="user" {if $tpl_use>=2} disabled="disable"{/if}>
					<option value="-1" {if $selected_user==-1} selected="selected"{/if}>{lng p="all"}</option>
				{foreach from=$users item=user}
					<option value="{$user.id}"{if $selected_user==$user.id} selected="selected"{/if}>{text value=$user.email}</option>
				{/foreach}
				</select>
				<input type="hidden" value="{$selected_user}" name="user_hidden"> <a href="plugin.page.php?plugin=checkpassword&action=page3&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="Bearbeiten" width="16" height="16" /></a></td>
			</tr>
		</table>

		{if $tpl_use==1}
		<p align="right">
			<input type="submit" class="button" value=" {lng p="next"} " />
		</p>
		{/if}
</fieldset>
{/if}

{if $tpl_use>=2 and $tpl_use!=3}
<fieldset>
	<legend>{lng p="type"}</legend>
		<table>
		<tr>
			<td class="td1">{lng p="checkpassword_password_expire"}:</td>
			<td class="td2"><input type="radio" name="expire" value="2"></td>
		</tr>
		<tr>
			<td class="td1">{lng p="checkpassword_password_never_expire"}:</td>
			<td class="td2"><input type="radio" name="expire" value="-1"></td>
		</tr>
		</table>

		<p align="right">
			<input type="submit" class="button" value=" {lng p="execute"} " />
		</p>
</fieldset>
{/if}