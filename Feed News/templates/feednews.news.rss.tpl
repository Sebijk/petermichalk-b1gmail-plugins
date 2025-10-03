<?xml version="1.0" encoding="utf-8"?><rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<atom:link href="http://my.wreckedmail.net/news/{$rsscat}rss" rel="self" type="application/rss+xml" />
	<title>wreckedmail.net - Nachrichten</title>
	<link>{$http_prefix}://my.wreckedmail.net</link>
	<description>Einfach mehr drin - Viele Funktionen rund um die Kommunikation machen Wreckedmail zu mehr als einer reinen E-Mail-Adresse.</description>
	<language>de-de</language>
	<copyright>wreckedmail</copyright>
	<pubDate>Fri, 11 Mar 2011 11:50:44 +0200</pubDate>
	<image>
		<title>wreckedmail.net</title>
		<link>{$http_prefix}://my.wreckedmail.net</link>
		<url>{$http_prefix}://img.wreckedmail.net/logos/wreckedmail.gif</url>
	</image>

{foreach from=$news item=rssitem}
 	<item>
		<title>{$rssitem.title}</title>
{if $rssitem.picture!="" AND !$rssitem.nopicture}
		<description>&lt;img width="80px" height="60px" border="0" alt="{$rssitem.rsstitle}" src="{$rssitem.picture}"/&gt;{$rssitem.text}&lt;br/&gt;&lt;br/&gt;Quelle: {$rssitem.rsstitle}</description>
{else}
		<description>{$rssitem.text}{if $rssitem.text!="" }&lt;br/&gt;&lt;br/&gt;{/if}Quelle: {$rssitem.rsstitle}</description>
{/if}
		<link>{$rssitem.link}</link>
		<guid>{$http_prefix}://my.wreckedmail.net/news/{$rssitem.id}</guid>
		<category>{$rssitem.category}</category>
{if $rssitem.picture!="" AND !$rssitem.nopicture}
		<enclosure type="image/jpeg" url="{$rssitem.picture}" length="25600"/>
{/if}
		<pubDate>{$rssitem.rssdatum}</pubDate>
		<source url="{$rssitem.link}">{$rssitem.rsstitle}</source>
	</item>
{/foreach}
</channel>
</rss>