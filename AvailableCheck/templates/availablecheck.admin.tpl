	<fieldset>
		<legend>{lng p="availablecheck_name"}</legend>

		<table>
			<tr>
				<td width="48"><img src="../plugins/templates/images/availablecheck_logo.png" width="48" height="48" border="0" alt="" /></td>
				<td width="10">&nbsp;</td>
				<td><b>{lng p="availablecheck_name"}</b><br />{lng p="availablecheck_text"}</td>
			</tr>
		</table>
	</fieldset>

	<fieldset>
	<legend>{lng p="availablecheck_name"}</legend>
	<form action="plugin.page.php?plugin=availablecheck&action=page1&do=check&sid={$sid}" method="post">
		<table width="100%">
		<tr>
			<td class="td1" width="300">{lng p="user"}:</td>
			<td class="td2"><input type="text" value="{$user}" name="user" style="width: 85%;"/></td>
		</tr>
		<tr>
			<td class="td1" width="300">{lng p="password"}:</td>
			<td class="td2"><input type="password" value="{$pass}" name="pass" style="width: 85%;"/></td>
		</tr>
		</table>
		<p align="right">
			<input type="submit" class="button" value=" {lng p="next"} "/>
		</p>	
	</form>
</fieldset>

{if $check}
	<fieldset>
		<legend>{lng p="pop3"}</legend>
		<table width="100%">
		<tr>
			<td class="td1" width="300">{lng p="availablecheck_connect"}:</td>
			<td class="td2">{if $pop3status1}<center><img src="./templates/images/ok.png" border="0" alt="{lng p="ok"}" width="16" height="16" /> {lng p="success"}!</center>{else}<center><img src="./templates/images/error.png" border="0" alt="{lng p="error"}" width="16" height="16" /> {lng p="error"}!</center>{/if}</td>
		</tr>
		<tr>
			<td class="td1" width="300">{lng p="login"}:</td>
			<td class="td2">{if $pop3status2}<center><img src="./templates/images/ok.png" border="0" alt="{lng p="ok"}" width="16" height="16" /> {lng p="success"}!</center>{else}<center><img src="./templates/images/error.png" border="0" alt="{lng p="error"}" width="16" height="16" /> {lng p="error"}!</center>{/if}</td>
		</tr>
		</table>
	</fieldset>
	
	<fieldset>
		<legend>{lng p="smtp"}</legend>
		<table width="100%">
		<tr>
			<td class="td1" width="300">{lng p="availablecheck_connect"}:</td>
			<td class="td2">{if $smtpstatus1}<center><img src="./templates/images/ok.png" border="0" alt="{lng p="ok"}" width="16" height="16" /> {lng p="success"}!</center>{else}<center><img src="./templates/images/error.png" border="0" alt="{lng p="error"}" width="16" height="16" /> {lng p="error"}!</center>{/if}</td>
		</tr>
		<tr>
			<td class="td1" width="300">{lng p="login"}:</td>
			<td class="td2">{if $smtpstatus2}<center><img src="./templates/images/ok.png" border="0" alt="{lng p="ok"}" width="16" height="16" /> {lng p="success"}!</center>{else}<center><img src="./templates/images/error.png" border="0" alt="{lng p="error"}" width="16" height="16" /> {lng p="error"}!</center>{/if}</td>
		</tr>
		</table>
	</fieldset>
{/if}