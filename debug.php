<?php
function pre()
{
	$args = func_get_args();

	foreach ($args as $arg)
	{
		echo '<pre>';
		if ($arg instanceof Iterator)
		{
			print_r(iterator_to_array($arg));
		}
		else
		{
			print_r($arg);
		}

		echo '</pre>';
	}
}

function vre()
{
	$args = func_get_args();

	foreach ($args as $arg)
	{
		echo '<pre>';
		if ($arg instanceof Iterator)
		{
			var_dump(iterator_to_array($arg));
		}
		else
		{
			var_dump($arg);
		}

		echo '</pre>';
	}
}

function pred()
{
	pre(func_get_args());
	die;
}

function vred()
{
	vre(func_get_args());
	die;
}