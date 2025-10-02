{literal}
<script type="text/javascript">
function switchlay(Layer_Typ)
{
	if (Layer_Typ==0)
	{
		switchlayer('icon', 'none');
		switchlayer('icon_modern', 'none');
		switchlayer('icon_modern_active', 'none');
		switchlayer('sidebar', 'none');
		switchlayer('toolbar', 'none');
		switchlayer('groups', 'none');
		switchlayer('tab_order', 'none');
		switchlayer('quicklinks', 'none');
	}
	else if (Layer_Typ==1)
	{
		switchlayer('icon', 'table-row');
		switchlayer('icon_modern', 'table-row');
		switchlayer('icon_modern_active', 'table-row');
		switchlayer('sidebar', 'table-row');
		switchlayer('toolbar', 'table-row');
		switchlayer('groups', 'table-row');
		switchlayer('tab_order', 'table-row');
		switchlayer('quicklinks', 'table-row');
	}
	else if (Layer_Typ==2)
	{
		switchlayer('icon', 'table-row');
		switchlayer('icon_modern', 'table-row');
		switchlayer('icon_modern_active', 'table-row');
		switchlayer('sidebar', 'table-row');
		switchlayer('toolbar', 'table-row');
		switchlayer('groups', 'table-row');
		switchlayer('tab_order', 'table-row');
		switchlayer('quicklinks', 'table-row');
	}
}

function switchlayer(Layer_Name, Layer_Visible)
{
	var GECKO = document.getElementById? 1:0 ;
	var NS = document.layers? 1:0 ;
	var IE = document.all? 1:0 ;

	if (GECKO)
	{
		document.getElementById(Layer_Name).style.display=Layer_Visible;
	}
	else if (NS)
	{
		document.layers[Layer_Name].display=Layer_Visible;
	}
	else if (IE)
	{
		document.all[Layer_Name].style.display=Layer_Visible;
	}
}
</script>
{/literal}

<fieldset>
	<legend>{lng p="eigeneseiten_own"} {lng p="pages"} {lng p="add"}</legend>
	
	{if $id==true}
		<form action="plugin.page.php?plugin=eigeneseiten&action=page2&do=update&sid={$sid}&id={$eigeneseite.id}" method="post" onsubmit="editor.submit();spin(this);">
	{/if}
	{if $id==false}
		<form action="plugin.page.php?plugin=eigeneseiten&action=page2&do=save&sid={$sid}" method="post" onsubmit="editor.submit();spin(this);">
	{/if}

		<table width="100%">
		<tr>
			<td class="td1" width="300">{lng p="title"}:</td>
			<td class="td2"><input type="text" style="width:85%;" name="title" value="{$eigeneseite.title}" /> <a href="plugin.page.php?plugin=eigeneseiten&action=page3&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="{lng p="help"}" width="16" height="16" /></a></td>
		</tr>
		<tr>
			<td class="td1" width="300">{lng p="eigeneseiten_linktitle"}:</td>
			<td class="td2"><input type="text" style="width:85%;" name="link_title" value="{$eigeneseite.link_title}" /> <a href="plugin.page.php?plugin=eigeneseiten&action=page3&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="{lng p="help"}" width="16" height="16" /></a></td>
		</tr>
		<tr>
			<td class="td1" width="300">{lng p="type"}:</td>
			<td class="td2"><select style="width: 200px;" name="typ" id="typ" onChange="switchlay(this.selectedIndex)">
				<option value="0" {if $eigeneseite.typ==0} selected="selected"{/if}>{lng p="nli"}</option>
				<option value="1" {if $eigeneseite.typ==1} selected="selected"{/if}>{lng p="li"}</option>
				<option value="2" {if $eigeneseite.typ==2} selected="selected"{/if}>{lng p="both"}</option>
				</select> <a href="plugin.page.php?plugin=eigeneseiten&action=page3&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="{lng p="help"}" width="16" height="16" /></a>
			</td>
		</tr>
		<tr id="icon" {if $eigeneseite.typ==0} style="display: none;"{/if}>
			<td class="td1" width="300">{lng p="icon"}:</td>
			<td class="td2"><select style="width: 200px;" name="icon">
				{foreach from=$array_icon item=icon}
					<option value="{$icon.small_name}"{if $eigeneseite.icon==$icon.small_name} selected="selected"{/if}>{text value=$icon.full_name}</option>
				{/foreach}
				</select> <a href="plugin.page.php?plugin=eigeneseiten&action=page3&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="{lng p="help"}" width="16" height="16" /></a>
			</td>
		</tr>
		<tr id="icon_modern" {if $eigeneseite.typ==0} style="display: none;"{/if}>
			<td class="td1" width="300">{lng p="icon_modern"}:</td>
			<td class="td2"><select style="width: 200px;" name="icon_modern">
				{foreach from=$array_icon item=icon}
					<option value="{$icon.small_name}"{if $eigeneseite.icon_modern==$icon.small_name} selected="selected"{/if}>{text value=$icon.full_name}</option>
				{/foreach}
				</select> <a href="plugin.page.php?plugin=eigeneseiten&action=page3&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="{lng p="help"}" width="16" height="16" /></a>
			</td>
		</tr>
		<tr id="icon_modern_active" {if $eigeneseite.typ==0} style="display: none;"{/if}>
			<td class="td1" width="300">{lng p="icon_modern_active"}:</td>
			<td class="td2"><select style="width: 200px;" name="icon_modern_active">
				{foreach from=$array_icon item=icon}
					<option value="{$icon.small_name}"{if $eigeneseite.icon_modern_active==$icon.small_name} selected="selected"{/if}>{text value=$icon.full_name}</option>
				{/foreach}
				</select> <a href="plugin.page.php?plugin=eigeneseiten&action=page3&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="{lng p="help"}" width="16" height="16" /></a>
			</td>
		</tr>
		<tr id="sidebar" {if $eigeneseite.typ==0} style="display: none;"{/if}>
			<td class="td1" width="150">{lng p="eigeneseiten_sidebar"}:</td>
			<td class="td2"><select style="width: 200px;" name="li_sidebar">
				{foreach from=$array_sidebar item=sidebar}
					<option value="{$sidebar.small_name}"{if $eigeneseite.li_sidebar==$sidebar.small_name} selected="selected"{/if}>{text value=$sidebar.full_name}</option>
				{/foreach}
				</select> <a href="plugin.page.php?plugin=eigeneseiten&action=page3&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="{lng p="help"}" width="16" height="16" /></a>
			</td>
		</tr>
		<tr id="toolbar" {if $eigeneseite.typ==0} style="display: none;"{/if}>
			<td class="td1" width="150">{lng p="eigeneseiten_toolbar"}:</td>
			<td class="td2"><select style="width: 200px;" name="li_toolbar">
					<option value="0" {if $eigeneseite.li_toolbar==0} selected="selected"{/if}>{lng p="none"}</option>
				{foreach from=$array_toolbar item=toolbar}
					<option value="{$toolbar.small_name}"{if $eigeneseite.li_toolbar==$toolbar.small_name} selected="selected"{/if}>{text value=$toolbar.full_name}</option>
				{/foreach}
				</select> <a href="plugin.page.php?plugin=eigeneseiten&action=page3&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="{lng p="help"}" width="16" height="16" /></a>
			</td>
		</tr>
		<tr id="groups" {if $eigeneseite.typ==0} style="display: none;"{/if}>
			<td class="td1" width="150">{lng p="groups"}:</td>
			<td class="td2"><select style="width: 200px;" name="gruppe">
				<option value="0" {if $eigeneseite.gruppe==0} selected="selected"{/if}>{lng p="all"}</option>
				{foreach from=$gruppen item=gruppe}
					<option value="{$gruppe.id}"{if $eigeneseite.gruppe==$gruppe.id} selected="selected"{/if}>{text value=$gruppe.titel}</option>
				{/foreach}
				</select> <a href="plugin.page.php?plugin=eigeneseiten&action=page3&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="{lng p="help"}" width="16" height="16" /></a>
			</td>
		</tr>
		<tr>
			<td class="td1">{lng p="language"}:</td>
			<td class="td2"><select style="width: 200px;" name="lang">
				<option value=":all:"{if $eigeneseite.lang==':all:'} selected="selected"{/if}>{lng p="all"}</option>
				<optgroup label="{lng p="languages"}">
					{foreach from=$languages item=lang key=langID}
					<option value="{$langID}"{if $eigeneseite.lang==$langID} selected="selected"{/if}>{text value=$lang.title}</option>
					{/foreach}
				</optgroup>
				</select></td>
		</tr>
		<tr id="tab_order" {if $eigeneseite.typ==0} style="display: none;"{/if}>
			<td class="td1">{lng p="eigeneseiten_tab_order"}:</td>
			<td class="td2"><input type="text" style="width:197px;" name="order" value="{$eigeneseite.tab_order}" /> <a href="plugin.page.php?plugin=eigeneseiten&action=page3&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="{lng p="help"}" width="16" height="16" /></a></td>
		</tr>
		<tr>
			<td colspan="2" style="border: 1px solid #DDDDDD;background-color:#FFFFFF;">
				<textarea name="text" id="text" class="plainTextArea" style="width:100%;height:220px;">{$eigeneseite.seite}</textarea>
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
			<td class="td1" width="300">{lng p="eigeneseiten_published_link"}?</td>
			<td class="td2"><input name="published_link" {if $eigeneseite.published_link!=0}checked="checked"{/if} {if $id==false}checked="checked"{/if}type="checkbox" /> <a href="plugin.page.php?plugin=eigeneseiten&action=page3&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="{lng p="help"}" width="16" height="16" /></a></td>
		</tr>
		<tr id="quicklinks" {if $eigeneseite.typ==0} style="display: none;"{/if}>
			<td class="td1" width="300">{lng p="eigeneseiten_published_quicklinks"}?</td>
			<td class="td2"><input name="published_quicklinks" {if $eigeneseite.published_quicklinks==1}checked="checked"{/if} type="checkbox" /> <a href="plugin.page.php?plugin=eigeneseiten&action=page3&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="{lng p="help"}" width="16" height="16" /></a></td>
		</tr>
		<tr>
			<td class="td1" width="300">{lng p="eigeneseiten_smarty"}?</td>
			<td class="td2"><input name="smartyparsen" {if $eigeneseite.smarty==1}checked="checked"{/if} type="checkbox" /></td>
		</tr>
		<tr>
			<td class="td1" width="300">{lng p="eigeneseiten_publish"}?</td>
			<td class="td2"><input name="published" {if $eigeneseite.published==1}checked="checked"{/if} type="checkbox" /> <a href="plugin.page.php?plugin=eigeneseiten&action=page3&sid={$sid}"><img src="./templates/images/help.png" border="0" alt="{lng p="help"}" width="16" height="16" /></a></td>
		</tr>
		</table>
		
		<p align="right">
			<input class="button" type="submit" value=" {lng p="save"} " />
		</p>	
	</form>
</fieldset>