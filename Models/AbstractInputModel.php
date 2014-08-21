<?php
namespace Developer\Stuff\Models;

use Developer\Stuff\Models\Exceptions\InvalidInputs;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
abstract class AbstractInputModel extends AbstractModel implements InputModelInterface
{
	private $inputData;
	private $queryData;
	private $inputFilter;

	public function __construct(array $inputData = null, array $queryData = null)
	{
		if (!is_null($inputData))
		{
			$this->setInputData($inputData);
		}

		if (!is_null($queryData))
		{
			$this->setQueryData($queryData);
		}
	}

	public function setInputData(array $data)
	{
		$this->inputData = $data;
	}

	public function setQueryData(array $data)
	{
		$this->queryData = $data;
	}

	public function getInputData()
	{
		return $this->inputData;
	}

	public function getQueryData()
	{
		return $this->queryData;
	}

	protected function populateInputFilter(InputFilterInterface $inputFilter)
	{
		//
	}

	protected function prepareData()
	{
		return $this->getInputData();
	}

	public function getInputFilter()
	{
		if (is_null($this->inputFilter))
		{
			$inputFilter = new InputFilter();

			$this->populateInputFilter($inputFilter);
			$inputFilter->setData($this->prepareData());

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
		if (!$this->getInputFilter()->has($name))
		{
			return false;
		}

		return $this->getInputFilter()->getValue($name) !== null;
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