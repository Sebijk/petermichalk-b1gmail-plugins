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

<form action="plugin.page.php?plugin=checkpassword&action=page1&sid={$sid}" method="post" onsubmit="spin(this)" name="f1">
<input type="hidden" name="sortBy" id="sortBy" value="{$sortBy}" />
<input type="hidden" name="sortOrder" id="sortOrder" value="{$sortOrder}" />
<fieldset>
	<legend>{lng p="groups"}</legend>
	<table class="list">
		<tr>
			<th width="5%">&nbsp;</th>
			<th width="5%"><a href="javascript:updateSort('cg.id');">{lng p="id"} {if $sortBy=='cg.id'}<img src="{$tpldir}images/sort_{$sortOrder}.png" border="0" alt="" width="7" height="6" align="absmiddle" />{/if}</a></th>
			<th width="30%"><a href="javascript:updateSort('g.titel');">{lng p="title"} {if $sortBy=='g.titel'}<img src="{$tpldir}images/sort_{$sortOrder}.png" border="0" alt="" width="7" height="6" align="absmiddle" />{/if}</a></th>
			<th width="25%"><a href="javascript:updateSort('cg.time');">{lng p="timeframe"} {if $sortBy=='cg.time'}<img src="{$tpldir}images/sort_{$sortOrder}.png" border="0" alt="" width="7" height="6" align="absmiddle" />{/if}</a></th>
			<th width="10%"><a href="javascript:updateSort('cg.duty');">{lng p="oblig"} {if $sortBy=='cg.duty'}<img src="{$tpldir}images/sort_{$sortOrder}.png" border="0" alt="" width="7" height="6" align="absmiddle" />{/if}</a></th>
			<th width="15%"><a href="javascript:updateSort('cg.secure');">{lng p="checkpassword_login_secure_password"} {if $sortBy=='cg.secure'}<img src="{$tpldir}images/sort_{$sortOrder}.png" border="0" alt="" width="7" height="6" align="absmiddle" />{/if}</a></th>
			<th width="10%">&nbsp;</th>
		</tr>

		{foreach from=$check_groups item=check_group}
		{cycle name=class values="td1,td2" assign=class}
			<tr class="{$class}">
				<td><center><img src="./templates/images/ok.png" border="0" alt="{lng p="ok"}" width="16" height="16" /></center></td>
				<td align="center"><center>{$check_group.id}</center></td>
				<td align="center">{$check_group.title}</td>
				<td align="center">{$check_group.time}</td>
				<td>{if $check_group.duty==1}<center><img src="./templates/images/ok.png" border="0" alt="{lng p="ok"}" width="16" height="16" /></center>{/if}
					{if $check_group.duty==0}<center><img src="./templates/images/error.png" border="0" alt="{lng p="no"}" width="16" height="16" /></center>{/if}</td>
				<td>{if $check_group.secure==1}<center><img src="./templates/images/ok.png" border="0" alt="{lng p="ok"}" width="16" height="16" /></center>{/if}
					{if $check_group.secure==0}<center><img src="./templates/images/error.png" border="0" alt="{lng p="no"}" width="16" height="16" /></center>{/if}</td>
				<td><center>
					<a href="plugin.page.php?plugin=checkpassword&action=page1&do=delete&id={$check_group.id}&sid={$sid}" onclick="return confirm('{lng p="realdel"}');"><img src="./templates/images/delete.png" border="0" alt="{lng p="delete"}" width="16" height="16" /></a>
				</center></td>
			</tr>
		{/foreach}

	</table>
</fieldset>
</form>

<fieldset>
	<legend>{lng p="groups"} {lng p="add"}</legend>

	<form action="plugin.page.php?plugin=checkpassword&action=page1&do=save&sid={$sid}" method="post" onsubmit="editor.submit();spin(this);">

		<table width="100%">
			<tr>
			<td class="td1" width="150">{lng p="groups"}:</td>
			<td class="td2"><select style="width: 200px;" name="gruppe"{if $all_groups_count ==0}disabled="disabled" {/if}>
				{foreach from=$all_groups item=gruppe}
					<option value="{$gruppe.id}">{text value=$gruppe.titel}</option>
				{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td class="td1" width="300">{lng p="timeframe"}:</td>
			<td class="td2"><input type="text" style="width: 196px;" name="time" value="7776000" {if $all_groups_count ==0}disabled="disabled" {/if}/> Sekunden <a href="plugin.page.php?plugin=checkpassword&action=page3&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="{lng p="help"}" width="16" height="16" /></a></td>
		</tr>
		<tr>
			<td class="td1" width="300">{lng p="oblig"}?</td>
			<td class="td2"><input name="duty" type="checkbox" {if $all_groups_count==0}disabled="disabled" {/if} /></td>
		</tr>
		<tr>
			<td class="td1" width="300">{lng p="checkpassword_login_secure_password2"}?</td>
			<td class="td2"><input name="secure" type="checkbox" {if $all_groups_count==0}disabled="disabled" {/if} /></td>
		</tr>
		</table>
		<p align="right">
			<input type="submit" class="button" value=" {lng p="save"} " {if $all_groups_count==0}disabled="disabled" {/if}/>
		</p>	
	</form>
</fieldset>