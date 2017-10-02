<?php
$activeTab = $this->getData('activeTab', '');
$activePulldown = $this->getData('activePulldown', '');
$limit = $this->getData('limit');
$tabs = array(
	array('href' => '/', 'name' => 'Blocks'),
	array('href' => '/latesttransactions', 'name' => 'Transactions'),
	array('href' => '/richlist', 'name' => 'Rich List'),
//	array('href' => '/primebids', 'name' => 'Prime Bids'),
//	array('href' => '/primestakes', 'name' => 'Prime Stakes'),
//	array('href' => '/network', 'name' => 'Network'),
//	array('href' => '/about', 'name' => 'About'),
);
?>
<ul class="nav nav-tabs">
	<?php
	$tabs = array(
//		array('href' => '/', 'name' => 'Blocks'),
//		array('href' => '/latesttransactions', 'name' => 'Transactions'),
//		array('href' => '/richlist', 'name' => 'Rich List'),
//		array('href' => '/primebids', 'name' => 'Prime Bids'),
//		array('href' => '/primestakes', 'name' => 'Prime Stakes'),
//		//array('href' => '/network', 'name' => 'Network'),
//		array('href' => '/about', 'name' => 'About'),
	);
	foreach ($tabs as $tab) {
		echo '<li role="presentation" ';
		if ($tab['name'] == $activeTab) {
			echo 'class="active" ';
		}
		echo '><a href="' . $tab['href'] . '">' . $tab['name'] . '</a></li>';
	}
	?>
	<?php if ($this->getData('enableLimitSelector') === true ) { ?>

	<li class="pull-right">
		<form method="post">
			<div class="form-group col-sm-8" style="margin-bottom: 0px">
				<select name="limit" class="form-control">
					<option value="25">Top 25</option>
					<option value="100" <?php if ($limit == 100) { echo 'selected'; } ?> >Top 100</option>
					<option value="1000" <?php if ($limit == 1000) { echo 'selected'; } ?> >Top 1000</option>
				</select>
			</div>
			<div class="col-sm-2 form-group" style="margin-bottom: 0px">
				<input type="submit" value="Go" class="btn btn-default">
			</div>
		</form>
	</li>

	<?php } ?>
	<?php if (!empty($this->getData('limitSelector'))) {
		echo $this->getData('limitSelector');
	} ?>

</ul>