<form action="index.php?action=changepassword&do=changePW&sid={$sid}" method="post">

<table class="nliTable">
	<!-- checkpassword message -->
	<tr>
		<td class="nliIconTD"><img src="./plugins/templates/images/checkpassword_page.png" border="0" alt="" /></td>
		<td class="nliTD">
			<h3>{$title}</h3>

			{$msg}<br /><br />			
{if $errorStep}
<div style="text-align:center; border: 1px solid #AAAAAA; background-color: #FFFFCC; padding: 5px; margin: 2px;">
	{$errorInfo}
</div>
<br />
{/if}
			<table>
			<tr>
				<td>
					<table>
					<tr>
						<td><b>{lng p="checkpassword_login_new_password1"}:</b></td>
					</tr>
					<tr>
						<td><input autocomplete="off" type="password" name="pass1" id="pass1" onkeyup="passwordSecurity(this.value, 'secureBar')" size="35" /></td>
					</tr>
					<tr>
						<td><b>{lng p="checkpassword_login_new_password2"}:</b></td>
					</tr>
					<tr>
						<td><input autocomplete="off" type="password" name="pass2" id="pass2" size="35" /></td>
					</tr>
					<tr>
						<td><b>{lng p="security"}:</b></td>
					</tr>
					<tr>
						<td>
						<div class="passwordSecurity">
							<div class="secureBar" id="secureBar" style="background: url({$tpldir}images/main/securebar.jpg); width:0%;">&nbsp;</div>
						</div>
						</td>
					</tr>
					</table>
				</td>
				<td>&nbsp;</td>
				<td>
					<b>{lng p="checkpassword_login_securepassword"}</b>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><input type="submit" value=" {lng p="changepw"} " /></td>
			</tr>
			</table>
		</td>
	</tr>
</table>
{if $duty==0}
<br /><br />
<div align="right"><a href="{$backlink}">{lng p="checkpassword_login_gotomailbox"}</a></div>
{/if}
</form>