<form action="plugin.page.php?plugin=adddate&action=page1&sid={$sid}" method="post" onsubmit="spin(this)">
	<fieldset>
		<legend>{lng p="adddate_name"}</legend>

		<table>
			<tr>
				<td width="48"><img src="../plugins/templates/images/adddate_logo.png" width="48" height="48" border="0" alt="" /></td>
				<td width="10">&nbsp;</td>
				<td><b>{lng p="adddate_name"}</b><br />{lng p="adddate_text"}</td>
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
				<a href="plugin.page.php?plugin=adddate&action=page2&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="{lng p="help"}" width="16" height="16" /></a>
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
				<a href="plugin.page.php?plugin=adddate&action=page2&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="{lng p="help"}" width="16" height="16" /></a>
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
		<legend>{lng p="date2"}</legend>

		<table>
		<tr>
			<td class="td1"><label for="title">{lng p="title"}:</label></td>
			<td class="td2"><input type="text" name="title" id="title" size="34" style="width:100%;" /></td>
		</tr>
		<tr>
			<td class="td1"><label for="location">{lng p="location"}:</label></td>
			<td class="td2"><input type="text" name="location" id="location" size="34" style="width:60%;" /></td>
		</tr>
		<tr>
			<td class="td1"><label for="text">{lng p="text"}:</label></td>
			<td class="td2"><textarea style="width:100%;height:100px;" name="text" id="text"></textarea></td>
		</tr>
		<tr>
			<td class="td1">{lng p="begin"}:</td>
			<td class="td2">
					{html_select_date prefix="startdate" time=$startDate field_order="DMY" start_year="-5" end_year="+5" field_separator="."},
					{html_select_time prefix="startdate" time=$startTime minute_interval=5 display_seconds=false}
			</td>
		</tr>
		<tr>
			<td class="td1">{lng p="duration"}:</td>
			<td class="td2">
				<table>
				<tr>
					<td><input type="radio" id="wholeDay_0" name="wholeDay" value="0"{if !$eDate || !($eDate.flags&1)} checked="checked"{/if} /></td>
					<td>
						<input type="text" onfocus="EBID('wholeDay_0').checked=true;" name="durationHours" id="durationHours" value="{$durationHours}" size="3" />
						{lng p="hours"},
						<input type="text" onfocus="EBID('wholeDay_0').checked=true;" name="durationMinutes" id="durationMinutes" value="{$durationMinutes}" size="3" />
						{lng p="minutes"}
					</td>
				</tr>
				<tr>
					<td><input type="radio" id="wholeDay_1" name="wholeDay" value="1"/></td>
					<td><label for="wholeDay_1">{lng p="wholeday"}</label></td>
				</tr>				
				</table>
			</td>
		</tr>
		<tr>
			<td class="td1">{lng p="reminder"}:</td>
			<td class="td2">
				<table>
				<tr>
					<td><input type="checkbox" name="reminder_email" id="reminderEMail"/> 
						<label for="reminderEMail">{lng p="byemail"}</label><br />
						<input type="checkbox" name="reminder_sms" id="reminderSMS"/>
						<label for="reminderSMS">{lng p="bysms"}</label>
					</td>
					<td width="20">&nbsp;</td>
					<td><fieldset>
						<legend>{lng p="timeframe"}</legend>
						<select name="reminder">
							<optgroup label="{lng p="minutes"}">
								<option value="5">5 {lng p="minutes"}</option>
								<option value="15">15 {lng p="minutes"}</option>
								<option value="15">30 {lng p="minutes"}</option>
								<option value="15">45 {lng p="minutes"}</option>
							</optgroup>
							<optgroup label="{lng p="hours"}">
								<option value="60">1 {lng p="hours"}</option>
								<option value="120">2 {lng p="hours"}</option>
								<option value="240">4 {lng p="hours"}</option>
								<option value="480">8 {lng p="hours"}</option>
								<option value="720">12 {lng p="hours"}</option>
							</optgroup>
							<optgroup label="{lng p="days"}">
								<option value="1440">1 {lng p="days"}</option>
								<option value="2880">2 {lng p="days"}</option>
								<option value="5760">4 {lng p="days"}</option>
								<option value="8640">6 {lng p="days"}</option>
							</optgroup>
							<optgroup label="{lng p="weeks"}">
								<option value="10080">1 {lng p="weeks"}</option>
								<option value="20160">2 {lng p="weeks"}</option>
								<option value="30240">3 {lng p="weeks"}</option>
								<option value="40320">4 {lng p="weeks"}</option>
							</optgroup>
						</select>
						<label for="reminder">{lng p="timebefore"}</label>
						</fieldset>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>

		<p align="right"><input class="button" type="submit" value=" {lng p="execute"} " /></p>
	</fieldset>
	{/if}
</form>