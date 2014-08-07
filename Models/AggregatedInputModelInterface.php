<?php
namespace Developer\Stuff\Models;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
interface AggregatedInputModelInterface
{
	/**
	 * @param $name
	 * @param InputModelInterface $model
	 * @return mixed
	 */
	public function addModel($name, InputModelInterface $model);

	/**
	 * @param $name
	 * @return InputModelInterface
	 */
	public function getModel($name);

	/**
	 * @param $name
	 * @return bool
	 */
	public function hasModel($name);

	/**
	 * @return InputModelInterface[]
	 */
	public function getModels();
} 