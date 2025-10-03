<div class="innerWidget" style="max-height 240px;overflow-y:auto;">
	<table cellspacing="0" width="100%" style="margin-bottom:2px;">
	{if $tvspielfilm_error}
		<tr>
			<td width="50"><img width="48" height="48" border="0" src="./plugins/templates/images/widget.tvspielfilm.nopic.png"></td>
			<td><b><center>Fehler beim Laden der TV-Programmdaten</center></b></td>
		</tr>
	{else}
		{foreach from=$tvspielfilm item=tvmovie}
			<tr>
				<td width="62" style="border-bottom: 1px solid rgb(221, 221, 221);"><img style="max-height: 60px; max-width: 60px;" border="0" src="{$tvmovie.pic}"></td>
				<td style="border-bottom: 1px solid rgb(221, 221, 221);"><b><a title="{$tvmovie.titel}" target="_blank" href="deref.php?{$tvmovie.link}">{$tvmovie.titel}</a></b>{if $showdes}<br/><small>{$tvmovie.description|truncate:120}</small>{/if}</td>
			</tr>
		{/foreach}
	{/if}
	</table>
</div>