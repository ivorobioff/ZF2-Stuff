<?php
namespace Developer\Stuff\Filters;
use Zend\Filter\Exception;
use Zend\Filter\FilterInterface;
use Zend\Validator\ValidatorChain as ZendValidatorChain;
use Zend\Validator\ValidatorInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ValidatorChain implements FilterInterface
{
	protected $options = [
		'fallback_value' => null,
		'validator_chain' => [],
	];

	public function __construct($options = [])
	{
		$this->options = array_merge($this->options, $options);
	}

	/**
	 * Returns the result of filtering $value
	 *
	 * @param  mixed $value
	 * @throws Exception\RuntimeException If filtering $value is impossible
	 * @return mixed
	 */
	public function filter($value)
	{
		$validatorChain = new ZendValidatorChain();

		foreach ($this->options['validator_chain'] as $validator)
		{
			$name = $validator['name'];
			$options = [];

			if (isset($validator['options']))
			{
				$options = $validator['options'];
			}

			$options['break_on_failure'] = true;

			if (class_exists($name))
			{
				/**
				 * @var ValidatorInterface $validator
				 */
				$validator = new $name($options);
				$validatorChain->attach($validator, true);
			}
			else
			{
				$validatorChain->attachByName($name, $options, true);
			}
		}

		if (!$validatorChain->isValid($value))
		{
			return $this->options['fallback_value'];
		}

		return $value;
	}
}