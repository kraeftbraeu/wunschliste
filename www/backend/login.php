<?php
	require_once "ht/connect.php";
	require_once $pathToVendor."vendor/autoload.php";
	require_once "service/JwtService.php";
	require_once "service/LogService.php";
	require_once "service/SqlService.php";
	
	$logService = new LogService();
	
	// allow cross-origin request
	header('Access-Control-Allow-Origin: *'); 

	$method = $_SERVER['REQUEST_METHOD'];
	if($method === 'OPTIONS')
	{
		header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE,OPTIONS'); 
		header('Access-Control-Allow-Headers: Accept, Accept-CH, Accept-Charset, Accept-Datetime, Accept-Encoding, Accept-Ext, Accept-Features, Accept-Language, Accept-Params, Accept-Ranges, Access-Control-Allow-Credentials, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Access-Control-Allow-Origin, Access-Control-Expose-Headers, Access-Control-Max-Age, Access-Control-Request-Headers, Access-Control-Request-Method, Age, Allow, Alternates, Authentication-Info, Authorization, C-Ext, C-Man, C-Opt, C-PEP, C-PEP-Info, CONNECT, Cache-Control, Compliance, Connection, Content-Base, Content-Disposition, Content-Encoding, Content-ID, Content-Language, Content-Length, Content-Location, Content-MD5, Content-Range, Content-Script-Type, Content-Security-Policy, Content-Style-Type, Content-Transfer-Encoding, Content-Type, Content-Version, Cookie, Cost, DAV, DELETE, DNT, DPR, Date, Default-Style, Delta-Base, Depth, Derived-From, Destination, Differential-ID, Digest, ETag, Expect, Expires, Ext, From, GET, GetProfile, HEAD, HTTP-date, Host, IM, If, If-Match, If-Modified-Since, If-None-Match, If-Range, If-Unmodified-Since, Keep-Alive, Label, Last-Event-ID, Last-Modified, Link, Location, Lock-Token, MIME-Version, Man, Max-Forwards, Media-Range, Message-ID, Meter, Negotiate, Non-Compliance, OPTION, OPTIONS, OWS, Opt, Optional, Ordering-Type, Origin, Overwrite, P3P, PEP, PICS-Label, POST, PUT, Pep-Info, Permanent, Position, Pragma, ProfileObject, Protocol, Protocol-Query, Protocol-Request, Proxy-Authenticate, Proxy-Authentication-Info, Proxy-Authorization, Proxy-Features, Proxy-Instruction, Public, RWS, Range, Referer, Refresh, Resolution-Hint, Resolver-Location, Retry-After, Safe, Sec-Websocket-Extensions, Sec-Websocket-Key, Sec-Websocket-Origin, Sec-Websocket-Protocol, Sec-Websocket-Version, Security-Scheme, Server, Set-Cookie, Set-Cookie2, SetProfile, SoapAction, Status, Status-URI, Strict-Transport-Security, SubOK, Subst, Surrogate-Capability, Surrogate-Control, TCN, TE, TRACE, Timeout, Title, Trailer, Transfer-Encoding, UA-Color, UA-Media, UA-Pixels, UA-Resolution, UA-Windowpixels, URI, Upgrade, User-Agent, Variant-Vary, Vary, Version, Via, Viewport-Width, WWW-Authenticate, Want-Digest, Warning, Width, X-Content-Duration, X-Content-Security-Policy, X-Content-Type-Options, X-CustomHeader, X-DNSPrefetch-Control, X-Forwarded-For, X-Forwarded-Port, X-Forwarded-Proto, X-Frame-Options, X-Modified, X-OTHER, X-PING, X-PINGOTHER, X-Powered-By, X-Requested-With'); 
		exit;
	}
	else if($method !== 'POST')
	{
		$logService->logError("attack on login");
		exit;
	}

	$sqlService = new SqlService($link, $logService);
	$jwtService = new JwtService($logService);
	
	// login
	$jsonData = json_decode(file_get_contents('php://input'), true);
	$loginUser = $jsonData['name'];
	$loginPw = $jsonData['password'];
	if(!isset($loginUser) || !isset($loginPw))
	{
		http_response_code(400);
		die("no login parameters found");
	}
	
	$userObject = $sqlService->selectUnique("SELECT * FROM user WHERE name = '".$loginUser."'");
	if($userObject === null)
	{
		http_response_code(401);
		die("no unique user found");
	}
	$dbPwHash = $userObject->password;
	if (!password_verify($loginPw, $dbPwHash))
	{
		$logService->logError("wrong password for user ".$loginUser);
		mysqli_close($link);
		http_response_code(401);
		die("no unique user found");
	}
	$userId = $userObject->id;
	$userName = $userObject->name;
	if($userId < 0 || empty($userName))
	{
		$logService->logError("no user found");
		mysqli_close($link);
		http_response_code(401);
		die("no unique user found");
	}
	// login was successful

	// update lastlogin, password
	$newPwHash = null;
	if(password_needs_rehash($dbPwHash, PASSWORD_DEFAULT))
		$newPwHash = password_hash($loginPw, PASSWORD_DEFAULT);
	//$sql = "UPDATE user SET changed = '".date(SqlService::$dateFormat)."'";
	$sql = "UPDATE user SET changed = NOW()";
	if($newPwHash != null)
		$sql .= ", password = '".$newPwHash."'";
	$sql .= " WHERE id = '".$userId."'";
	$sqlResult = $sqlService->execute($sql);
	if($sqlResult === null)
	{
		http_response_code(400);
		die("SQL error");
	}
	
	echo json_encode(array(
		'token' => $jwtService->getToken($userObject)
	));
	
	mysqli_close($link);
?>