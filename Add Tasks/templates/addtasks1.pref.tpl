<form action="plugin.page.php?plugin=addtasks&action=page1&sid={$sid}" method="post" onsubmit="spin(this)">

<fieldset>
	<legend>{lng p="addtasks_name"}</legend>
	
	<table>
		<tr>
			<td width="48"><img src="../plugins/templates/images/addtasks_logo.png" width="48" height="48" border="0" alt="" /></td>
			<td width="10">&nbsp;</td>
			<td><b>{lng p="addtasks_name"}</b><br>{lng p="addtasks_text"}</td>
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
				<td class="td2"><select style="width: 180px;" name="gruppe" {if $tpl_use>=1 AND $tpl_use!=3} disabled="disable"{/if}>
					<option value="-1" {if $selected_gruppe==-1} selected="selected"{/if}>{lng p="all"}</option>
				{foreach from=$gruppen item=gruppe}
					<option value="{$gruppe.id}"{if $selected_gruppe==$gruppe.id} selected="selected"{/if}>{text value=$gruppe.titel}</option>
				{/foreach}
				</select>
				<input type="hidden" value="{$selected_gruppe}" name="gruppe_hidden"> <a href="plugin.page.php?plugin=addtasks&action=page2&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="Bearbeiten" width="16" height="16" /></a></td>
			</tr>
		</table>

		{if $tpl_use==0 or $tpl_use==3}
		<p align="right">
			<input class="button" type="submit" value=" {lng p="next"} " />
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
				<input type="hidden" value="{$selected_user}" name="user_hidden"> <a href="plugin.page.php?plugin=addtasks&action=page2&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="Bearbeiten" width="16" height="16" /></a></td>
			</tr>
		</table>

		{if $tpl_use==1}
		<p align="right">
			<input class="button" type="submit" value=" {lng p="next"} " />
		</p>
		{/if}
</fieldset>
{/if}

{if $tpl_use>=2 and $tpl_use!=3}
<fieldset>
	<legend>{lng p="tasks"}</legend>
		<table>
		<tr>
			<td class="td1"><label for="titel">{lng p="title"}:</label></td>
			<td class="td2">
				<input type="text" name="titel" id="titel" value="" style="width:100%;" />
			</td>
		</tr>
		<tr>
			<td class="td1">{lng p="begin"}:</td>
			<td class="td2">
				{html_select_date prefix="beginn" time=$task.beginn end_year="+5" start_year="-5" field_order="DMY" field_separator="."}, 
				{html_select_time prefix="beginn" time=$task.beginn display_seconds=false}
			</td>
		</tr>
		<tr>
			<td class="td1">{lng p="due"}:</td>
			<td class="td2">
				{html_select_date prefix="faellig" time=$task.faellig end_year="+5" start_year="-5" field_order="DMY" field_separator="."}, 
				{html_select_time prefix="faellig" time=$task.faellig display_seconds=false}
			</td>
		</tr>
		<tr>
			<td class="td1"><label for="erledigt">{lng p="done"}:</label></td>
			<td class="td2">
				<input type="text" name="erledigt" id="erledigt" value="" size="5" /> %
			</td>
		</tr>
		<tr>
			<td class="td1"><label for="akt_status">{lng p="status"}:</label></td>
			<td class="td2">
				<select name="akt_status" id="akt_status">
					<option value="16">{lng p="taskst_16"}</option>
					<option value="32">{lng p="taskst_32"}</option>
					<option value="64">{lng p="taskst_64"}</option>
					<option value="128">{lng p="taskst_128"}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="td1"><label for="priority">{lng p="priority"}:</label></td>
			<td class="td2">
				<select name="priority" id="priority">
					<option value="1">{lng p="prio_1"}</option>
					<option value="0">{lng p="prio_0"}</option>
					<option value="-1">{lng p="prio_-1"}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="td1"><label for="comments">{lng p="comment"}:</label></td>
			<td class="td2">
				<textarea class="textInput" name="comments" id="comments" cols="100" rows="5"></textarea>
			</td>
		</tr>
		</table>

		<p align="right">
			<input class="button" type="submit" value=" {lng p="execute"} " />
		</p>
</fieldset>
{/if}