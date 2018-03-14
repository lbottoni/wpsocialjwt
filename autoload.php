<?php
spl_autoload_register(function ($class) {
	$prefix = 'WPSOCIALJWT\\';//prefix namespace
	$baseDir=plugin_dir_path( __FILE__ );//my plugin dir
	$baseDir = $customBaseDir ?: __DIR__ . '/';
	// does the class use the namespace prefix?
	$len = strlen($prefix);
	if (strncmp($prefix, $class, $len) !== 0) {
	// no, move to the next registered autoloader
	return;
	}
	// get the relative class name
	$relativeClass = substr($class, $len);

	// replace the namespace prefix with the base directory, replace namespace
	// separators with directory separators in the relative class name, append
	// with .php
	$file = rtrim($baseDir, '/') . '/' . str_replace('\\', '/', $relativeClass) . '.php';

	// if the file exists, require it
	if (file_exists($file)) {
	require $file;
	}
});

