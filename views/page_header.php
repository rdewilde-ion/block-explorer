<?php
$q = $this->getData('q', '');
?>

<form id="searchform" method="get" action="/search/">
<div class="row">
	<div class="col-md-6">
		<a href="/"><img class="logo" src="/img/blockchainlogo.png" border="0" alt="ION Blockchain" title="ION Blockchain"></a>
	</div>
	<div class="col-md-6 search-box" >
		<div class="input-group">
			<input name="q" type="text" class="form-control" placeholder="Search address, block, transaction, tag..." value="<?php echo htmlspecialchars($q); ?>">
      <span class="input-group-btn" >
        <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
      </span>
	</div><!-- /input-group -->
	</div><!-- /.col-lg-6 -->

</div><!-- /.row -->
</form>


<div style="height: 30px"></div>