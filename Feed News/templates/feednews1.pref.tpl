<fieldset>
	<legend>{lng p="feednews_name"}</legend>
	
	<table>
		<tr>
			<td width="48"><img src="../plugins/templates/images/feednews_logo.png" width="48" height="48" border="0" alt="" /></td>
			<td width="10">&nbsp;</td>
			<td><b>{lng p="feednews_name"}</b><br />{lng p="feednews_text"}</td>
		</tr>
	</table>
</fieldset>


<fieldset>
	<legend>{lng p="stats"}</legend>
		<table width="100%">
    		<tr>
    			<td width="25%" class="td1">{lng p="feednews_name"}:</td>
    			<td class="td2">{$countall}</td>
    			
    			<td width="25%" class="td2">&nbsp;</td>
    			<td class="td2">&nbsp;</td>
    		</tr>
 		</tbody></table>
</fieldset>

<fieldset>
	<legend>{lng p="category"}</legend>
		<table width="100%">
    		<tr>
    			<td width="25%" class="td1">{lng p="used"} {lng p="category"}:</td>
    			<td>{foreach from=$cat item=cats}<a href="plugin.page.php?plugin=feednews&action=page2&cat={$cats.category}&sid={$sid}">{$cats.category} ({$cats.count})</a>, {/foreach}</td>
    		</tr>
    		<tr>
    			<td width="25%" class="td1">{lng p="feednews_unused"} {lng p="category"}:</td>
    			<td>{foreach from=$notuse item=notuses}<a href="plugin.page.php?plugin=feednews&action=page2&cat={$cats}&sid={$sid}">{$notuses}</a>, {/foreach}</td>
    		</tr>
		</tbody></table>
</fieldset>