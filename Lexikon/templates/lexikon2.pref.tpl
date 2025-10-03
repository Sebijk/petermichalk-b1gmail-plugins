<fieldset>
	<legend>{lng p="lexikon_name"}</legend>

	<table>
		<tr>
			<td width="48"><img src="../plugins/templates/images/lexikon_logo.png" width="48" height="48" border="0" alt="" /></td>
			<td width="10">&nbsp;</td>
			<td><b>{lng p="lexikon_name"}</b><br>{lng p="lexikon_text"}</td>
		</tr>
	</table>
</fieldset>

<fieldset>
	<legend>{lng p="lexikon_name"}</legend>
	
	{if $id==true}
		<form action="plugin.page.php?plugin=lexikon&action=page2&do=update&sid={$sid}&id={$lexikon.id}" method="post" onsubmit="editor.submit();spin(this);">
	{/if}
	{if $id==false}
		<form action="plugin.page.php?plugin=lexikon&action=page2&do=save&sid={$sid}" method="post" onsubmit="editor.submit();spin(this);">
	{/if}

		<table width="100%">
		<tr>
			<td class="td1" width="300">{lng p="title"}:</td>
			<td class="td2"><input type="text" style="width:85%;" name="title" value="{$lexikon.title}" /></td>
		</tr>
		<tr>
			<td class="td1" width="300">{lng p="category"}:</td>
			<td class="td2"><select style="width: 200px;" name="cat">
				<option value="0"{if $lexikon.cat=="0"} selected="selected"{/if}>0-9</option>
				<option value="a"{if $lexikon.cat=="a"} selected="selected"{/if}>A</option>
				<option value="b"{if $lexikon.cat=="b"} selected="selected"{/if}>B</option>
				<option value="c"{if $lexikon.cat=="c"} selected="selected"{/if}>C</option>
				<option value="d"{if $lexikon.cat=="d"} selected="selected"{/if}>D</option>
				<option value="e"{if $lexikon.cat=="e"} selected="selected"{/if}>E</option>
				<option value="f"{if $lexikon.cat=="f"} selected="selected"{/if}>F</option>
				<option value="g"{if $lexikon.cat=="g"} selected="selected"{/if}>G</option>
				<option value="h"{if $lexikon.cat=="h"} selected="selected"{/if}>H</option>
				<option value="i"{if $lexikon.cat=="i"} selected="selected"{/if}>I</option>
				<option value="j"{if $lexikon.cat=="j"} selected="selected"{/if}>J</option>
				<option value="k"{if $lexikon.cat=="k"} selected="selected"{/if}>K</option>
				<option value="l"{if $lexikon.cat=="l"} selected="selected"{/if}>L</option>
				<option value="m"{if $lexikon.cat=="m"} selected="selected"{/if}>M</option>
				<option value="n"{if $lexikon.cat=="n"} selected="selected"{/if}>N</option>
				<option value="o"{if $lexikon.cat=="o"} selected="selected"{/if}>O</option>
				<option value="p"{if $lexikon.cat=="p"} selected="selected"{/if}>P</option>
				<option value="q"{if $lexikon.cat=="q"} selected="selected"{/if}>Q</option>
				<option value="r"{if $lexikon.cat=="r"} selected="selected"{/if}>R</option>
				<option value="s"{if $lexikon.cat=="s"} selected="selected"{/if}>S</option>
				<option value="t"{if $lexikon.cat=="t"} selected="selected"{/if}>T</option>
				<option value="u"{if $lexikon.cat=="u"} selected="selected"{/if}>U</option>
				<option value="v"{if $lexikon.cat=="v"} selected="selected"{/if}>V</option>
				<option value="w"{if $lexikon.cat=="w"} selected="selected"{/if}>W</option>
				<option value="x"{if $lexikon.cat=="x"} selected="selected"{/if}>X</option>
				<option value="y"{if $lexikon.cat=="y"} selected="selected"{/if}>Y</option>
				<option value="z"{if $lexikon.cat=="z"} selected="selected"{/if}>Z</option>
				<option value="#"{if $lexikon.cat=="#"} selected="selected"{/if}>#</option>
				
				</select>
			</td>
		</tr>
		<tr>
			<td class="td1">{lng p="language"}:</td>
			<td class="td2"><select style="width: 200px;" name="lang">
				<option value=":all:"{if $eigeneseite.lang==':all:'} selected="selected"{/if}>{lng p="all"}</option>
				<optgroup label="{lng p="languages"}">
					{foreach from=$languages item=lang key=langID}
					<option value="{$langID}"{if $lexikon.lang==$langID} selected="selected"{/if}>{text value=$lang.title}</option>
					{/foreach}
				</optgroup>
				</select></td>
		</tr>
		<tr>
			<td colspan="2" style="border: 1px solid #DDDDDD;background-color:#FFFFFF;">
				<textarea name="text" id="text" class="plainTextArea" style="width:100%;height:220px;">{$lexikon.text}</textarea>
				<script language="javascript" src="../clientlib/wysiwyg.js"></script>
				<script language="javascript">
					<!--
					var editor = new htmlEditor('text', '{$usertpldir}/images/editor/');
					editor.init();
					registerLoadAction('editor.start()');
				//-->
				</script>
			</td>
		</tr>
		<tr>
			<td class="td1" width="300">{lng p="lexikon_publish"}?</td>
			<td class="td2"><input name="published" {if $lexikon.published==1}checked="checked"{/if} type="checkbox" /></td>
		</tr>
		</table>
		
		<p align="right">
			<input type="submit" value=" {lng p="save"} " class="button" />
		</p>	
	</form>
</fieldset>