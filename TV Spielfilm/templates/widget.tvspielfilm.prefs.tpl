<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{lng p="prefs"}</title>
	<!-- meta -->
	<meta http-equiv="content-type" content="text/html; charset={$charset}" />
	<!-- links -->
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<link href="{$tpldir}style/dialog.css" rel="stylesheet" type="text/css" />
	<!-- client scripts -->
	<script src="clientlang.php?sid={$sid}" type="text/javascript" language="javascript"></script>
	<script src="clientlib/overlay.js" type="text/javascript" language="javascript"></script>
	<script src="{$tpldir}js/common.js" type="text/javascript" language="javascript"></script>
	<script src="{$tpldir}js/loggedin.js" type="text/javascript" language="javascript"></script>
	<script src="{$tpldir}js/dialog.js" type="text/javascript" language="javascript"></script>
	<!--[if lt IE 7]>
	<script defer type="text/javascript" src="clientlib/pngfix.js"></script>
	<![endif]-->
</head>
<body>
	<form action="{$widgetPrefsURL}" method="post">
	<input type="hidden" name="save" value="true" />

	<fieldset>	
	<legend>{lng p="prefs"}</legend>
	<center>
		<select name="tvspielfilm" id="tvspielfilm">
		<option value="tvjetzt"{if $tvspielfilm=="tvjetzt"} selected="selected"{/if}>Jetzt im TV</option>
		<option value="tv2015"{if $tvspielfilm=="tv2015"} selected="selected"{/if}>Heute, 20:15 Uhr im TV</option>
		<option value="tv2200"{if $tvspielfilm=="tv2200"} selected="selected"{/if}>Heute, 22:00 Uhr im TV</option>
		<option value="tvtipps"{if $tvspielfilm=="tvtipps"} selected="selected"{/if}>TV-Tipps des Tages</option>
		</select>
	</center>
	</fieldset>	
	
	<fieldset>	
	<legend>{lng p="count"}</legend>
	<center>
		<select name="tvspielfilm_number" id="tvspielfilm_number">
		<option value="5"{if $tvspielfilm_number==5} selected="selected"{/if}>5</option>
		<option value="7"{if $tvspielfilm_number==7 OR $tvspielfilm_number==false} selected="selected"{/if}>7</option>
		<option value="10"{if $tvspielfilm_number==10} selected="selected"{/if}>10</option>
		<option value="12"{if $tvspielfilm_number==12} selected="selected"{/if}>12</option>
		<option value="15"{if $tvspielfilm_number==15} selected="selected"{/if}>15</option>
		</select>
	</center>
	</fieldset>

	<p align="right"><input type="button" onclick="parent.hideOverlay()" value="{lng p="cancel"}" /><input type="submit" value="{lng p="ok"}" /></p>
	</form>
</body>
</html>