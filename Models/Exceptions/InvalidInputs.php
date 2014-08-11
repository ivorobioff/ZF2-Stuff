<?php
namespace Developer\Stuff\Models\Exceptions;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class InvalidInputs extends \Exception
{
	private $errors;

	public function __construct(array $errors)
	{
		$this->errors = $errors;
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function getFirstError()
	{
		$errors = $this->getErrors();
		$error = reset($errors);
		return reset($error);
	}

	public function getFirstErrors()
	{
		$errors = [];

		foreach ($this->errors as $field => $error)
		{
			$errors[$field] = reset($error);
		}

		return $errors;
	}
}