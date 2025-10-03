{literal}
<script type="text/javascript">
function switchlayer(Layer_Name)
{
	var GECKO = document.getElementById? 1:0 ;
	var NS = document.layers? 1:0 ;
	var IE = document.all? 1:0 ;

	if (GECKO)
	{
		document.getElementById(Layer_Name).style.display= (document.getElementById(Layer_Name).style.display=='block') ? 'none' : 'block';
		document.getElementById('button1_'+Layer_Name).style.display= (document.getElementById(Layer_Name).style.display=='block') ? 'none' : 'block';
		document.getElementById('button2_'+Layer_Name).style.display= (document.getElementById(Layer_Name).style.display=='block') ? 'block' : 'none';
	}
	else if (NS)
	{
		document.layers[Layer_Name].display=(document.layers[Layer_Name].display== 'block') ? 'none' : 'block';
		document.layers['button1_'+Layer_Name].display=(document.layers['button1_'+Layer_Name].display== 'block') ? 'none' : 'block';
		document.layers['button2_'+Layer_Name].display=(document.layers['button2_'+Layer_Name].display== 'block') ? 'block' : 'none';
	}
	else if (IE)
	{
		document.all[Layer_Name].style.display=(document.all[Layer_Name].style.display== 'block') ? 'none' : 'block';
		document.all['button1_'+Layer_Name].style.display=(document.all['button1_'+Layer_Name].style.display== 'block') ? 'none' : 'block';
		document.all['button2_'+Layer_Name].style.display=(document.all['button2_'+Layer_Name].style.display== 'block') ? 'block' : 'none';
	}
}
</script>
{/literal}

<fieldset>
	<legend>{lng p="showshares_name"}</legend>

	<table>
		<tr>
			<td width="48"><img src="../plugins/templates/images/showshares_logo.png" width="48" height="48" border="0" alt="" /></td>
			<td width="10">&nbsp;</td>
			<td><b>{lng p="showshares_name"}</b><br />{lng p="showshares_text"}</td>
		</tr>
	</table>
</fieldset>

<form action="plugin.page.php?plugin=showshares&action=page1&sid={$sid}" method="post" onsubmit="spin(this)" name="f1">
<input type="hidden" name="sortBy" id="sortBy" value="{$sortBy}" />
<input type="hidden" name="sortOrder" id="sortOrder" value="{$sortOrder}" />
<fieldset>
	<legend>{lng p="showshares_name"}</legend>
	<table class="list">
		<tr>
			<th width="5%">&nbsp;</th>
			<th width="5%"><a href="javascript:updateSort('folder.id');">{lng p="id"} 1 {if $sortBy=='folder.id'}<img src="{$tpldir}images/sort_{$sortOrder}.png" border="0" alt="" width="7" height="6" align="absmiddle" />{/if}</a></th>
			<th width="5%"><a href="javascript:updateSort('folder.user');">{lng p="id"} 2 {if $sortBy=='folder.user'}<img src="{$tpldir}images/sort_{$sortOrder}.png" border="0" alt="" width="7" height="6" align="absmiddle" />{/if}</a></th>
			<th width="40%" colspan="3"><a href="javascript:updateSort('user.email');">{lng p="username"} {if $sortBy=='user.email'}<img src="{$tpldir}images/sort_{$sortOrder}.png" border="0" alt="" width="7" height="6" align="absmiddle" />{/if}</a> / {lng p="filetypes"}</th>
			<th width="30%"><a href="javascript:updateSort('folder.titel');">{lng p="folders"} {if $sortBy=='folder.titel'}<img src="{$tpldir}images/sort_{$sortOrder}.png" border="0" alt="" width="7" height="6" align="absmiddle" />{/if}</a>  / {lng p="filename"}</th>
			<th width="10%">{lng p="count"}</th>
			<th width="5%"></th>
		</tr>
	</table>

	{foreach from=$folders item=folder}
	{cycle name=class values="td1,td2" assign=class}
	<table class="list">
		<tr class="{$class}">
			<td width="5%"><center><a href="#" onclick="javascript:switchlayer('{$folder.id}'); return false;">
			<div id="button1_{$folder.id}" style="display:block;"><img src="./templates/images/expand.gif" border="0"/></div>
			<div id="button2_{$folder.id}" style="display:none;"><img src="./templates/images/contract.gif" border="0"/></div>
			</a></center></td>
			<td width="5%"><center>{$folder.id}</center></td>
			<td width="5%"><center>{$folder.user}</center></td>
			<td width="24%"><a href="users.php?do=edit&id={$folder.user}&sid={$sid}">{$folder.email}</a></td>
			<td width="8%"><center>{progressBar value=$folder.diskspace_used max=$folder.diskspace_max width=75}<center></td>
			<td width="8%"><center>{progressBar value=$folder.traffic max=$folder.traffic_max width=75}</center></td>
			<td width="30%">{$folder.titel}</td>
			<td width="10%">{$folder.count}</td>
			<td width="5%"><center><font size="3px">{if $webdiskgalerie && $folder.share=="yes" && $folder.galerie=="yes"}<a href="../index.php?action=galerie&gal={$folder.id}" target="_blank" title="Galerie"><img width="16" height="16" border="0" alt="Galerie" src="../plugins/templates/images/showshares_gallery.png" align="absmiddle"></a>{/if}
			{if $folder.share=="yes"}<a href="../share/?user={$folder.email}" target="_blank" title="Freigabe"><img width="16" height="16" border="0" alt="Freigabe" src="../share/favicon.ico" align="absmiddle"></a>{/if}&nbsp;</font></center></td>
		</tr>
	</table>

	<div id="{$folder.id}" style="display:none;">
		<table class="list">
		{foreach from=$files item=file}
			{if $folder.id == $file.ordner}	
			<tr class="{$class}">
				<td width="5%"></td>
				<td width="5%"><center>{$file.ordner}</center></td>
				<td width="5%"><center>{$file.id}</center></td>
				<td width="40%"><center>{$file.contenttype}</center></td>
				<td width="30%"><center>{$file.dateiname}</center></td>
				<td width="10%">{size bytes=$file.size}</td>
				<td width="5%"></td>
			</tr>
			{/if}
		{/foreach}
		</table>
	</div>

	{/foreach}
</fieldset>
</form>

<fieldset>
	<legend>{lng p="showshares_name"}</legend>
	<form action="plugin.page.php?plugin=showshares&action=page1&do=save&sid={$sid}" method="post">
	<br/>
	<table>
		<tr>
			<td width="80"><center><input name="showall" {if $showall}checked="checked"{/if} type="checkbox" /></center></td>
			<td width="10">&nbsp;</td>
			<td>{lng p="showshares_showall"}</td>
		</tr>
	</table>
	<div style="float:right">
		<input type="submit" value=" {lng p="save"} " class="button">
	</div>
	</form>
</fieldset>