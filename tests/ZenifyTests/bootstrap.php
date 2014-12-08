<?php

/** @var Composer\Autoload\ClassLoader $classLoader */
if (@ ! $classLoader = include __DIR__ . '/../../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer update --dev`';
	exit(1);
}
$classLoader->addPsr4('ZenifyTests\\', __DIR__);


// configure environment
Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');


// create temporary directory
define('TEMP_DIR', createAndReturnTempDir());
Tracy\Debugger::$logDirectory = TEMP_DIR;


/** @return string */
function createAndReturnTempDir() {
	@mkdir(__DIR__ . '/../tmp'); // @ - directory may exists
	@mkdir($tempDir = __DIR__ . '/../tmp/' . (isset($_SERVER['argv']) ? md5(serialize($_SERVER['argv'])) : getmypid()));
	Tester\Helpers::purge($tempDir);
	return realpath($tempDir);
}


$configurator = new Nette\Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$configurator->addConfig(__DIR__ . '/config/default.neon');
return $configurator->createContainer();
