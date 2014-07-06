<?php
namespace Developer\Stuff\Models;

use Developer\Stuff\Models\Exceptions\InvalidInputs;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
abstract class AbstractInputModel extends AbstractModel
{
	private $inputData;
	private $inputFilter;

	public function __construct(array $inputData)
	{
		$this->inputData = $inputData;
	}

	protected function populateInputFilter(InputFilterInterface $inputFilter)
	{
		//
	}

	public function getInputFilter()
	{
		if (is_null($this->inputFilter))
		{
			$inputFilter = new InputFilter();

			$this->populateInputFilter($inputFilter);
			$inputFilter->setData($this->inputData);

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}

	public function validate()
	{
		if (!$this->getInputFilter()->isValid())
		{
			throw new InvalidInputs($this->getInputFilter()->getMessages());
		}
	}

	public function save()
	{
		//
	}

	public function toArray()
	{
		return $this->getInputFilter()->getValues();
	}

	public function __isset($name)
	{
		return $this->getInputFilter()->has($name);
	}

	public function __get($name)
	{
		return $this->getInputFilter()->getValue($name);
	}

	public function __set($name, $value)
	{
		throw new \RuntimeException('Read-only property');
	}
}