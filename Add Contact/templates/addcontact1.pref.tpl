<form action="plugin.page.php?plugin=addcontact&action=page1&sid={$sid}" method="post" onsubmit="spin(this)">
	<fieldset>
		<legend>{lng p="addcontact_name"}</legend>

		<table>
			<tr>
				<td width="48"><img src="../plugins/templates/images/addcontact_logo.png" width="48" height="48" border="0" alt="" /></td>
				<td width="10">&nbsp;</td>
				<td><b>{lng p="addcontact_name"}</b><br />{lng p="addcontact_text"}</td>
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
				<a href="plugin.page.php?plugin=addcontact&action=page2&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="{lng p="help"}" width="16" height="16" /></a>
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
				<a href="plugin.page.php?plugin=addcontact&action=page2&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="{lng p="help"}" width="16" height="16" /></a>
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
		<legend>{lng p="addcontact"}</legend>

		<table class="listTable">
		<tr>
			<td class="listTableLeftDesc"><img src="../templates/{$templ}/images/li/addr_common.png" width="16" height="16" border="0" alt="" /></td>
			<td class="listTableRightDesc">{lng p="common"}</td>
		</tr>
		<tr>
			<td class="listTableLeft"><label for="anrede">{lng p="salutation"}:</label></td>
			<td class="listTableRight">
				<select name="anrede" id="anrede">
					<option value="" selected="selected">&nbsp;</option>
					<option value="frau">{lng p="mrs"}</option>
					<option value="herr">{lng p="mr"}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="listTableLeft"><label for="vorname">{lng p="firstname"}</label> / <label for="nachname">{lng p="surname"}:</label></td>
			<td class="listTableRight">
				<input type="text" name="vorname" id="vorname" value="" size="20" />
				<input type="text" name="nachname" id="nachname" value="" size="20" />
			</td>
		</tr>
		
		<tr>
			<td class="listTableLeftDesc"><img src="../templates/{$templ}/images/li/addr_priv.png" width="16" height="16" border="0" alt="" /></td>
			<td class="listTableRightDesc">
				<table width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td>{lng p="priv"}</td>
						<td align="right">
							<label for="default_priv">{lng p="default"}</label>
							<input type="radio" name="default" id="default_priv" value="priv" checked="checked" />
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="listTableLeft"><label for="strassenr">{lng p="streetnr"}</label>:</td>
			<td class="listTableRight">
				<input type="text" name="strassenr" id="strassenr" value="" size="30" />
			</td>
		</tr>
		<tr>
			<td class="listTableLeft"><label for="plz">{lng p="zipcity"}:</label></td>
			<td class="listTableRight">
				<input type="text" name="plz" id="plz" value="" size="6" />
				<input type="text" name="ort" id="ort" value="" size="20" />
			</td>
		</tr>
		<tr>
			<td class="listTableLeft"><label for="land">{lng p="country"}:</label></td>
			<td class="listTableRight">	
				<input type="text" name="land" id="land" value="" size="30" />
			</td>
		</tr>
		<tr>
			<td class="listTableLeft"><label for="email">{lng p="email"}:</label></td>
			<td class="listTableRight">	
				<input type="text" name="email" id="email" value="" size="30" />
			</td>
		</tr>
		<tr>
			<td class="listTableLeft"><label for="tel">{lng p="phone"}:</label></td>
			<td class="listTableRight">	
				<input type="text" name="tel" id="tel" value="" size="30" />
			</td>
		</tr>
		<tr>
			<td class="listTableLeft"><label for="fax">{lng p="fax"}:</label></td>
			<td class="listTableRight">	
				<input type="text" name="fax" id="fax" value="" size="30" />
			</td>
		</tr>
		<tr>
			<td class="listTableLeft"><label for="handy">{lng p="mobile"}:</label></td>
			<td class="listTableRight">	
				<input type="text" name="handy" id="handy" value="" size="30" />
			</td>
		</tr>
		
		<tr>
			<td class="listTableLeftDesc"><img src="../templates/{$templ}/images/li/addr_work.png" width="16" height="16" border="0" alt="" /></td>
			<td class="listTableRightDesc">
				<table width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td>{lng p="work"}</td>
						<td align="right">
							<label for="default_work">{lng p="default"}</label>
							<input type="radio" name="default" id="default_work" value="work" />
						</td>
					</tr>
				</table></td>
		</tr>
		<tr>
			<td class="listTableLeft"><label for="work_strassenr">{lng p="streetnr"}</label>:</td>
			<td class="listTableRight">
				<input type="text" name="work_strassenr" id="work_strassenr" value="" size="30" />
			</td>
		</tr>
		<tr>
			<td class="listTableLeft"><label for="work_plz">{lng p="zipcity"}:</label></td>
			<td class="listTableRight">
				<input type="text" name="work_plz" id="work_plz" value="" size="6" />
				<input type="text" name="work_ort" id="work_ort" value="" size="20" />
			</td>
		</tr>
		<tr>
			<td class="listTableLeft"><label for="work_land">{lng p="country"}:</label></td>
			<td class="listTableRight">	
				<input type="text" name="work_land" id="work_land" value="" size="30" />
			</td>
		</tr>
		<tr>
			<td class="listTableLeft"><label for="work_email">{lng p="email"}:</label></td>
			<td class="listTableRight">	
				<input type="text" name="work_email" id="work_email" value="" size="30" />
			</td>
		</tr>
		<tr>
			<td class="listTableLeft"><label for="tel">{lng p="phone"}:</label></td>
			<td class="listTableRight">	
				<input type="text" name="work_tel" id="work_tel" value="" size="30" />
			</td>
		</tr>
		<tr>
			<td class="listTableLeft"><label for="fax">{lng p="fax"}:</label></td>
			<td class="listTableRight">	
				<input type="text" name="work_fax" id="work_fax" value="" size="30" />
			</td>
		</tr>
		<tr>
			<td class="listTableLeft"><label for="work_handy">{lng p="mobile"}:</label></td>
			<td class="listTableRight">	
				<input type="text" name="work_handy" id="work_handy" value="" size="30" />
			</td>
		</tr>
		
		<tr>
			<td class="listTableLeftDesc"><img src="../templates/{$templ}/images/li/addr_misc.png" width="16" height="16" border="0" alt="" /></td>
			<td class="listTableRightDesc">{lng p="misc"}</td>
		</tr>
		<tr>
			<td class="listTableLeft"><label for="firma">{lng p="company"}:</label></td>
			<td class="listTableRight"><input type="text" name="firma" id="firma" value="" size="30" /></td>
		</tr>
		<tr>
			<td class="listTableLeft"><label for="position">{lng p="position"}</label>:</td>
			<td class="listTableRight">
				<input type="text" name="position" id="position" value="" size="30" />
			</td>
		</tr>
		<tr>
			<td class="listTableLeft"><label for="web">{lng p="web"}:</label></td>
			<td class="listTableRight">	
				<input type="text" name="web" id="web" value="" size="30" />
			</td>
		</tr>
		<tr>
			<td class="listTableLeft">{lng p="birthday"}:</td>
			<td class="listTableRight">	
				{if $contact.geburtsdatum}
				{html_select_date time=$contact.geburtsdatum year_empty="---" day_empty="---" month_empty="---" start_year="-120" end_year="+0" prefix="geburtsdatum_" field_order="DMY"}
				{else}
				{html_select_date time="---" year_empty="---" day_empty="---" month_empty="---" start_year="-120" end_year="+0" prefix="geburtsdatum_" field_order="DMY"}
				{/if}
			</td>
		</tr>
		<tr>
			<td class="listTableLeft"><label for="kommentar">{lng p="comment"}:</label></td>
			<td class="listTableRight">	
				<textarea class="textInput" name="kommentar" id="kommentar"></textarea>
			</td>
		</tr>
	</table>

	<p align="right"><input class="button" type="submit" value=" {lng p="execute"} " /></p>
	</fieldset>
	{/if}
</form>