<table class="nliTable">
	<tbody><tr>
		<td class="nliIconTD"><img width="32" height="32" border="0" alt="" src="./plugins/templates/images/lexikon_page.png"/></td>
		<td class="nliTD"><h3 style="margin-bottom:0;">{$pageTitle}</h3>
<div align="right" style="float: right; width: 330px;">
<a href="index.php?action={$lexikonvar}">Alle</a> | <a href="javascript:void(0);" onmouseup="showSearchPopup()" onclick="return false;">Suche</a> | <a href="index.php?action={$lexikonvar}&amp;cat=0">0-9</a> | <a href="index.php?action={$lexikonvar}&amp;cat=a">A</a> | <a href="index.php?action={$lexikonvar}&amp;cat=b">B</a> | <a href="index.php?action={$lexikonvar}&amp;cat=c">C</a> | <a href="index.php?action={$lexikonvar}&amp;cat=d">D</a> | <a href="index.php?action={$lexikonvar}&amp;cat=e">E</a> | <a href="index.php?action={$lexikonvar}&amp;cat=f">F</a> | <a href="index.php?action={$lexikonvar}&amp;cat=g">G</a> | <a href="index.php?action={$lexikonvar}&amp;cat=h">H</a> | <a href="index.php?action={$lexikonvar}&amp;cat=i">I</a> | <a href="index.php?action={$lexikonvar}&amp;cat=j">J</a> | <a href="index.php?action={$lexikonvar}&amp;cat=k">K</a> | <a href="index.php?action={$lexikonvar}&amp;cat=l">L</a> | <a href="index.php?action={$lexikonvar}&amp;cat=m">M</a> | <a href="index.php?action={$lexikonvar}&amp;cat=n">N</a> | <a href="index.php?action={$lexikonvar}&amp;cat=o">O</a> | <a href="index.php?action={$lexikonvar}&amp;cat=p">P</a> | <a href="index.php?action={$lexikonvar}&amp;cat=q">Q</a> | <a href="index.php?action={$lexikonvar}&amp;cat=r">R</a> | <a href="index.php?action={$lexikonvar}&amp;cat=s">S</a> | <a href="index.php?action={$lexikonvar}&amp;cat=t">T</a> | <a href="index.php?action={$lexikonvar}&amp;cat=u">U</a> | <a href="index.php?action={$lexikonvar}&amp;cat=v">V</a> | <a href="index.php?action={$lexikonvar}&amp;cat=w">W</a> | <a href="index.php?action={$lexikonvar}&amp;cat=x">X</a> | <a href="index.php?action={$lexikonvar}&amp;cat=y">Y</a> | <a href="index.php?action={$lexikonvar}&amp;cat=z">Z</a> | <a href="index.php?action={$lexikonvar}&amp;cat=.">#</a>
</div>

<br/><br/><br/>
<div id="lex_content">
{if $empty}
	{if $suche}
	<br/><center><strong>{lng p="lexikon_nosearch"}</strong></center><br/>
	{else}
	<br/><center><strong>{lng p="lexikon_empty"}</strong></center><br/>
	{/if}
{else}
<table>
<tbody>
		{foreach from=$lexikon item=lex}
		<tr>
			<td class="nliIconTD" style="width:0;"><img width="22" height="22" border="0" alt="" src="./plugins/templates/images/lexicon_bulb.png"/></td>
			<td>

		<h2 style="margin:0 0 0.5em;"><a href="index.php?action={$lexikonvar}&amp;id={$lex.id}" title="{$lex.title}">{$lex.title}</a></h2>
		{if !$nosmall}<font size="2" style="font-family: Arial;">{/if}{$lex.text}{if !$nosmall}<br/><br/></font>{/if}
			</td>
		</tr>
		{/foreach}
</tbody></table>
		{if $pageCount!=1}
		<div style="float:right;padding-top:3px;">
			{pageNav page=$pageNo pages=$pageCount on=" <span class=\"pageNav\"><b>[.t]</b></span> " off=" <span class=\"pageNav\"><a href=\"index.php?action=`$lexikonvar`&amp;cat=`$cat2`&amp;perPage=`$perPage`&amp;page=.s\">.t</a></span> "}&nbsp;
		</div>
		{/if}
{/if}
</div>
		</td>
	</tr>
</tbody></table>

<table width="100%" cellspacing="0" cellpadding="0" onmouseout="disableHide=false;" onmouseover="disableHide=true;" style="height: 26px; display: none;" id="searchPopup">
	<tbody><tr>
		<td>
			<table width="100%" cellspacing="0" cellpadding="0">
				<tbody><tr>
					<td width="70" align="right">Suche: &nbsp;</td>
					<td align="center">
					<form action="index.php?action={$lexikonvar}" method="post">
						<input style="width:90%" name="s" id="s"/>
					</form>
					</td>
				</tr>
			</tbody></table>
		</td>
	</tr>
</tbody></table>

{literal}
<script type="text/javascript">
function EBID(k){return(document.getElementById(k));}
function showSearchPopup(){document.onmousedown = hideSearchPopup;EBID('searchPopup').style.display='';EBID('searchPopup').style.height='26px';EBID('s').focus();}
function hideSearchPopup(really){if(!disableHide || really==true)EBID('searchPopup').style.display = 'none';}
{/literal}{if $showsearch}{literal}registerLoadAction(showSearchPopup);{/literal}{/if}{literal}
</script>
{/literal}
{literal}
<style type="text/css">
<!--
#searchPopup{position:absolute;top:183px;width:320px;border:1px solid #999;background-color:#FFF;margin-left:52px;}
-->
</style>
{/literal}