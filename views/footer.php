				</div>
			</div>
		</div>


	</div>
	<!-- /#page-content-wrapper -->
</div><!-- /.wrapper -->

<script type="application/javascript" src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<script type="application/javascript" src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js" async></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script type="application/javascript" src="/js/jstorage.min.js?cb=<?php echo APP_VERSION ?>" ></script>
<script type="application/javascript" src="/js/main.js?cb=<?php echo APP_VERSION ?>" ></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/json2/20110223/json2.min.js" async></script>
<?php
if (isset($this)) {
	echo $this->getJSAssets();
}
?>

<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-63797741-1', 'auto');
	ga('send', 'pageview');

</script>

<!-- Menu Toggle Script -->
<script>
	$(function(){
		$("#menu-toggle").click(function(e) {
			e.preventDefault();
			$("#wrapper").toggleClass("toggled");
		});

		if($link = $('li.dropdown ul.dropdown-menu li a[href="' + $(location).attr('href').substring($(location).attr('href').lastIndexOf('/') + 1) +'"]'))
		{
			$link.closest('.dropdown').addClass('active open');
		}
	});
</script>

<?php

if (DEBUG_BAR) {
	$debugbarRenderer = \lib\Bootstrap::getInstance()->debugbar->getJavascriptRenderer();
	echo $debugbarRenderer->render();
}

?>

</body>

</html>