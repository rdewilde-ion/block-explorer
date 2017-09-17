<?php
$config = array(
	'site' => array(
		'name' => 'ION Block Explorer',
		'contactEmails' => 'webmaster@example.com',
		'allowips' => array(
			'192.168.1.1',
			'127.0.0.1',
		)
	),
	'iond' => array(
		'rpchost' => '127.0.0.1',
		'rpcport' => 12705,
		'rpcuser' => 'user',
		'rpcpassword' => 'password',
	),
	'mysql' => array(
		'host' => 'localhost',
		'user' => 'user',
		'password' => 'password',
		'database' => 'blockexplorer',
	),
	'memcached' => array(
		'host' => 'localhost',
		'port' => 11211
	),
	'debugbar' => array(
		'allowips' => array(
			'192.168.1.1',
			'127.0.0.1',
		)
	)
);

return $config;
