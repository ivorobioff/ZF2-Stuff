<?php
namespace Developer\Stuff\Models;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class AggregatedInputModel extends AbstractInputModel implements AggregatedInputModelInterface
{
	/**
	 * @var InputModelInterface[]
	 */
	private $models = [];

	public function addModel($name, InputModelInterface $model)
	{
		$this->models[$name] = $model;
	}

	/**
	 * @param $name
	 * @return InputModelInterface
	 */
	public function getModel($name)
	{
		return $this->models[$name];
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function hasModel($name)
	{
		return isset($this->models[$name]);
	}

	/**
	 * @return InputModelInterface[]
	 */
	public function getModels()
	{
		return $this->models;
	}


	public function validate()
	{
		parent::validate();

		foreach ($this->getModels() as $model)
		{
			$model->validate();
		}
	}

	public function toArray()
	{
		$values = parent::toArray();

		foreach ($this->getModels() as $name => $model)
		{
			$values[$name] = $model->getInputFilter()->getValues();
		}

		return $values;
	}

	public function __isset($name)
	{
		if (parent::__isset($name))
		{
			return true;
		}

		return $this->hasModel($name);
	}

	public function __get($name)
	{
		if (parent::__isset($name))
		{
			return parent::__get($name);
		}

		$model = $this->getModel($name);
		return $model->getInputFilter()->getValues();
	}
}