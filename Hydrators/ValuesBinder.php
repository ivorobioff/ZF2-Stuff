<?php
namespace Developer\Stuff\Hydrators;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ValuesBinder implements HydratorInterface
{

	/**
	 * Extract values from an object
	 *
	 * @param  object $object
	 * @return array
	 */
	public function extract($object)
	{
		$properties = get_object_vars($object);

		$data = [];

		foreach($properties as $name => $value)
		{
			if ($value === null) continue;
			$data[$name] = $value;
		}

		return $data;
	}

	/**
	 * Hydrate $object with the provided $data.
	 *
	 * @param  array $data
	 * @param  object $object
	 * @return object
	 */
	public function hydrate(array $data, $object)
	{
		$properties = get_object_vars($object);

		foreach (array_keys($properties) as $name)
		{
			if (!isset($data[$name])) continue;
			$object->$name = $data[$name];
		}
	}
}