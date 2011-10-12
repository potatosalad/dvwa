<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/dvwaPage.inc.php';
require_once DVWA_WEB_PAGE_TO_ROOT."external/recaptcha/recaptchalib.php";

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ] .= $page[ 'title_separator' ].'Vulnerability: Insecure CAPTCHA';
$page[ 'page_id' ] = 'captcha';

// ReCAPTCHA Key configuration
// Global Keys provided by 

dvwaDatabaseConnect();

$vulnerabilityFile = '';
switch( $_COOKIE[ 'security' ] ) {
	case 'low':
		$vulnerabilityFile = 'low.php';
		break;

	case 'medium':
		$vulnerabilityFile = 'medium.php';
		break;

	case 'high':
	default:
		$vulnerabilityFile = 'high.php';
		break;
}

require_once DVWA_WEB_PAGE_TO_ROOT."vulnerabilities/captcha/source/{$vulnerabilityFile}";

$page[ 'help_button' ] = 'captcha';
$page[ 'source_button' ] = 'captcha';

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: Insecure CAPTCHA</h1>

	<div class=\"vulnerable_code_area\">

  	<h3>Change your password:</h3>
    	<br>
    	<form action=\"#\" method=\"POST\"";
if ($hide_form) $page[ 'body' ] .= "style=\"display:none;\"";


$page[ 'body' ] .= ">
		<input type=\"hidden\" name=\"step\" value=\"1\" />";

        if (dvwaSecurityLevelGet() == 'high'){
                $page[ 'body' ] .= "Current password:<br>
                <input type=\"password\" AUTOCOMPLETE=\"off\" name=\"password_current\"><br>";
        }

	$page[ 'body' ] .= "    New password:<br>
		<input type=\"password\" AUTOCOMPLETE=\"off\" name=\"password_new\"><br>
		Confirm new password: <br>
	        <input type=\"password\" AUTOCOMPLETE=\"off\" name=\"password_conf\">
			" . recaptcha_get_html($_DVWA['recaptcha_public_key']) . "
			<br />
			<input type=\"submit\" value=\"Change\" name=\"Change\">
	</form>

	{$html}

</div>

	<h2>More info</h2>
	<ul>
		<li>".dvwaExternalLinkUrlGet( 'http://www.captcha.net/')."</li>
		<li>".dvwaExternalLinkUrlGet( 'http://www.google.com/recaptcha/')."</li>
	</ul>
</div>
";

dvwaHtmlEcho( $page );

?>
