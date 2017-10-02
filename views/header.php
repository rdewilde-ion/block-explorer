<?php
$pageTitle = 'ION Ledger';
$pageName = '';
$pageDescription = 'ION Block Explorer & Currency Statistics. View detailed information on all ION transactions and blocks.';
$debugbarRenderer = false;
$q = isset($_REQUEST['q']) ? strip_tags($_REQUEST['q']) : '';

if (DEBUG_BAR ) {
	$debugbarRenderer = \lib\Bootstrap::getInstance()->debugbar->getJavascriptRenderer();
}
if (isset($this)) {
	$cacheTime = $this->getData('cacheTime', 0);
	if ($cacheTime > 0) {
		$ts = gmdate("D, d M Y H:i:s", time() + $cacheTime) . " GMT";
		header("Expires: $ts");
		header("Pragma: cache");
		header("Cache-Control: max-age=$cacheTime");
	}
	$pageTitle = $this->getData('pageTitle', 'ION Ledger');
	$pageName = $this->getData('pageName', 'Home');
	$pageDescription = $this->getData('pageName', $pageDescription);
	if (DEBUG_BAR) {
		$debugbarRenderer = \lib\Bootstrap::getInstance()->debugbar->getJavascriptRenderer();
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" CONTENT="<?php echo htmlspecialchars($pageDescription); ?>">

	<title><?php echo htmlspecialchars($pageTitle)  ?></title>

	<?php
	if (isset($this)) {
		echo $this->getHeaderAssets();
	}
	?>

	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/main.css?cb=<?php echo APP_VERSION ?>" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="/css/simple-sidebar.css" rel="stylesheet">

	<link href='//fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>

	<?php if ($debugbarRenderer) {
		echo $debugbarRenderer->renderHead();
	} ?>
</head>
<body>


	<div id="wrapper">

		<!-- Sidebar -->
		<div id="sidebar-wrapper">
			<ul class="sidebar-nav">

				<li class="sidebar-brand">
					<a href="/">
						<div class="logo"></div>
					</a>
				</li>
				<?php
				$activeTab = $this->getData('activeTab', '');
				$activePulldown = $this->getData('activePulldown', '');
				$menuItems = array(
//					array('href' => '/', 'name' => 'Home').
					array('href' => '/', 'name' => 'Blocks'),
					array('href' => '/latesttransactions', 'name' => 'Transactions'),
					array('href' => '/tagging', 'name' => 'Tagging'),
					array('href' => '/richlist', 'name' => 'Rich List'),
					array('href' => '/api', 'name' => 'API'),
					array('href' => '/faq', 'name' => 'FAQ'),
//					array('href' => '/about', 'name' => 'About'),
//					array('href' => '/contact', 'name' => 'Contact'),
				);

				foreach ($menuItems as $menuItem) {
					if (empty($menuItem)) continue;

					echo '<li';
					if ($menuItem['href'] == $_SERVER['REQUEST_URI']) {
						echo ' class="active open"';
					}
					echo '><a href="' . $menuItem['href'] . '">'  . $menuItem['name'];
					echo '</a></li>';
				}
				?>
				<li role="presentation" class="dropdown <?php if ($activeTab == 'Network') { echo ' active open'; } ?>">
					<a class="dropdown-toggle" data-toggle="dropdown" href="/network" role="button" aria-expanded="false">
						Network <span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu">
						<li <?php if ($activePulldown == 'Versions') { echo ' class="active open"'; } ?>><a href="/network">Versions</a></li>
						<li <?php if ($activePulldown == 'Network Map') { echo ' class="active open"'; } ?>><a href="/network/map">Network Map</a></li>
					</ul>
				</li>

				<li role="presentation" class="dropdown <?php if ($activeTab == 'Charts') { echo ' active open'; } ?>">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
						Charts <span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu">
						<li <?php if ($activePulldown == 'Outstanding') { echo ' class="active open"'; } ?>><a href="/charts/outstanding">Outstanding Coins</a></li>
						<li <?php if ($activePulldown == 'Transactions Per Block') { echo ' class="active open"'; } ?>><a href="/charts/block/transactions">Transaction Per Block</a></li>
						<li <?php if ($activePulldown == 'Value Per Block') { echo ' class="active open"'; } ?>><a href="/charts/block/value">Value Per Block</a></li>
					</ul>
				</li>
			</ul>
<!--			<a href="#menu-toggle" class="btn btn-default" id="menu-toggle">Toggle Menu</a>-->
		</div>
		<!-- /#sidebar-wrapper -->

		<!-- Page Content -->
		<div id="page-content-wrapper">
			<div class="container-fluid">

				<form id="searchform" method="get" action="/search/">
					<div class="row">
						<div class="col-xs-1">
							<a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><img src="/img/menu.svg"></a>
						</div>
						<div class="col-xs-10">
							<input name="q" type="text" placeholder="Search address, block, transaction, tag..."  value="<?php echo htmlspecialchars($q); ?>" style="width: 100%;;">
						</div>
						<div class="col-xs-1">
							<button class="btn btn-default" type="submit">
								<span class="glyphicon glyphicon-search"></span>
							</button>
						</div>
					</div>
				</form>
				<div class="row">
