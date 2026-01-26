<?php

declare( strict_types=1 );

$finder = PhpCsFixer\Finder::create()
	->in( __DIR__ )
	->exclude( [
		'vendor',
		'storage',
		'bootstrap/cache',
	] )
	->name( '*.php' );

return ( new PhpCsFixer\Config() )
	->setRiskyAllowed( true )
	->setIndent( "\t" )
	->setLineEnding( "\n" )
	->setRules( [
		'@PSR12' => true,

		'is_null' => true,

		'spaces_inside_parentheses' => [ 'space' => 'single' ],
	] )
	->setFinder( $finder );
