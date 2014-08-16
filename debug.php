<?php
function pre()
{
	_developer_debug_dump(func_get_args(), true);
}


function vre()
{
	_developer_debug_dump(func_get_args());
}

function pred()
{
	_developer_debug_dump(func_get_args(), true);
	die;
}

function vred()
{
	_developer_debug_dump(func_get_args());
	die;
}

function _developer_debug_dump(array $args, $print_r = false)
{
	foreach ($args as $arg)
	{
		echo '<pre>';
		if ($arg instanceof Iterator)
		{
			$data = iterator_to_array($arg);

			if ($print_r)
			{
				print_r($data);
			}
			else
			{
				var_dump($data);
			}
		}
		else
		{
			if ($print_r)
			{
				print_r($arg);
			}
			else
			{
				var_dump($arg);
			}
		}

		echo '</pre>';
	}
}