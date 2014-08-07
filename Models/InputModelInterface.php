<?php
namespace Developer\Stuff\Models;
use Zend\InputFilter\InputFilterInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
interface InputModelInterface 
{
	public function setInputData(array $data);
	public function validate();

	/**
	 * @return InputFilterInterface
	 */
	public function getInputFilter();
	public function toArray();
} 