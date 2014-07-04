<?php
namespace Developer\Stuff\Iterators;

/**
 * Igor Vorobiov<igor.vorobioff@gmail.com> 
 */
class SubIteratorIterator implements \Iterator
{
	/**
	 * @var \Iterator
	 */
	private $_iterator;

	/**
	 * @var \Iterator
	 */
	private $_sub_iterator;

	/**
	 * @var callable
	 */
	private $_factory;

	/**
	 * @var int
	 */
	private $_counter;

	public function __construct(\Iterator $iterator, \Closure $factory = null)
	{
		$this->_iterator = $iterator;
		$this->_factory = $factory;
	}

	public function current()
	{
		return $this->_sub_iterator->current();
	}

	public function next()
	{
		$this->_counter ++;

		$this->_sub_iterator->next();

		if ($this->_sub_iterator->valid()) return ;

		$this->_sub_iterator = $this->_findNextSubIterator();
	}

	public function key()
	{
		return $this->_counter;
	}

	public function valid()
	{
		return $this->_iterator->valid() || $this->_sub_iterator->valid();
	}

	public function rewind()
	{
		$this->_counter = 0;
		$this->_sub_iterator = $this->_findNextSubIterator(true);
	}

	/**
	 * @return \Iterator
	 */
	protected function _getSubIterator()
	{
		return $this->_sub_iterator;
	}

	/**
	 * @return \Iterator
	 */
	protected function _getIterator()
	{
		$this->_iterator;
	}

	/**
	 * @return \Iterator
	 */
	private function _buildSubIterator()
	{
		if (is_null($this->_factory)) return $this->_iterator->current();
		return call_user_func($this->_factory, $this->_iterator->current());
	}

	private function _findNextSubIterator($is_rewind = false)
	{
		if ($is_rewind)
		{
			$this->_iterator->rewind();
		}
		else
		{
			$this->_iterator->next();
		}

		if (!$this->_iterator->valid()) return new \ArrayIterator();

		$sub_iterator = $this->_buildSubIterator();
		$sub_iterator->rewind();

		if (!$sub_iterator->valid())
		{
			return $this->_findNextSubIterator();
		}

		return $sub_iterator;
	}
} 