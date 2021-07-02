<?php
/**
* PHP-CS-Fixer Config.
*
* @author SignpostMarv
*/
declare(strict_types=1);

namespace SignpostMarv\CS;

return ConfigUsedWithStaticAnalysis::createWithPaths(...[
	__FILE__,
	__DIR__ . '/src/',
	__DIR__ . '/Tests/',
]);
