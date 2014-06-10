<?php
/*********************************************************************************
 * TimeTrex is a Payroll and Time Management program developed by
 * TimeTrex Software Inc. Copyright (C) 2003 - 2013 TimeTrex Software Inc.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by
 * the Free Software Foundation with the addition of the following permission
 * added to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED
 * WORK IN WHICH THE COPYRIGHT IS OWNED BY TIMETREX, TIMETREX DISCLAIMS THE
 * WARRANTY OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along
 * with this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact TimeTrex headquarters at Unit 22 - 2475 Dobbin Rd. Suite
 * #292 Westbank, BC V4T 2E9, Canada or at email address info@timetrex.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License
 * version 3, these Appropriate Legal Notices must retain the display of the
 * "Powered by TimeTrex" logo. If the display of the logo is not reasonably
 * feasible for technical reasons, the Appropriate Legal Notices must display
 * the words "Powered by TimeTrex".
 ********************************************************************************/
/*
 * $Revision: 4104 $
 * $Id: index.php 4104 2011-01-04 19:04:05Z ipso $
 * $Date: 2011-01-04 11:04:05 -0800 (Tue, 04 Jan 2011) $
 */
require_once('./includes/global.inc.php'); //Mainly to force redirect to SSL URL if required...

// Hook:Maestrano
// Load Maestrano
$authentication = new Authentication();
$maestrano = MaestranoService::getInstance();
// Require authentication straight away if intranet
// mode enabled
if ($maestrano->isSsoIntranetEnabled()) {
  if (!isset($_SESSION)) session_start();
  if (!$maestrano->getSsoSession()->isValid()) {
    header("Location: " . $maestrano->getSsoInitUrl());
    exit;
  }
}

// Hook:Maestrano

if ($maestrano->isSsoEnabled()) {
  if (!isset($_SESSION)) session_start();
  if ($authentication->Check()) {
    // Check Maestrano session is still valid
    if (!$maestrano->getSsoSession()->isValid()) {
      header("Location: " . $maestrano->getSsoInitUrl());
      exit;
    }
  } else {
    // Redirect to login
    header("Location: " . $maestrano->getSsoInitUrl());
    exit;
  }
}

?>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TimeTrex</title>
<link href="/interface/ng/libs/css/bootstrap/bootstrap.min.css" media="screen" rel="stylesheet" type="text/css">
<?php if ($maestrano->isSsoEnabled()) { ?>
<script>
var logoutRedirect = "<?php echo $maestrano->getSsoLogoutUrl();?>";
var ssoLoginRedirect = "<?php echo $maestrano->getSsoInitUrl();?>";
setInterval(function(){
  if (document.cookie.indexOf('timetrex_logout=1') != -1) {
    document.cookie = 'timetrex_logout=; expires=Thu, 01-Jan-70 00:00:01 GMT; path=/';
    window.location.replace(logoutRedirect);
  }
  if (document.cookie.indexOf('timetrex_relogin=1') != -1) {
    document.cookie = 'timetrex_relogin=; expires=Thu, 01-Jan-70 00:00:01 GMT; path=/';
    window.location.replace(ssoLoginRedirect);
  }
},1000);
</script>
<?php } ?>
</head>
<body>
  <div class="container">
    <div class="row" style="height: 100px;"></div>
    <div class="row">
        <div class="center-block" style="max-width:400px;text-align:center;"><img src="interface/images/timetrex_just_logo_512.png" alt="Timetrex Logo" class="img-rounded" size="200x200" style="height:200px;width:200px;"></div>
    </div>
    <div class="row">
      <div class="well" style="max-width: 400px; margin: 0 auto 10px;">
        <a href="/interface/ng" type="button" class="btn btn-primary btn-lg btn-block">EasyTimesheet</a>
        <a href="/interface/flex" type="button" class="btn btn-default btn-lg btn-block">Personal Space</a>
      </div>
    </div>
  </div>
</body>
</html>
