<?php
namespace Developer\Stuff\JsComposer;

use Developer\Stuff\JsComposer\Exceptions\ErrorLoadingBootfile;
use Developer\Stuff\JsComposer\Exceptions\ErrorSavingFile;
use Developer\Stuff\JsComposer\Exceptions\ErrorLoadingClass;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class Composer
{
	private $bootfiles = array();
	private $classesDir;

	private $classes = array();

	/**
	 * @param $classesDir
	 */
	public function __construct($classesDir)
	{
		$this->classesDir = $classesDir;
	}

	public function addBootfile($filename)
	{
		$this->bootfiles[] = $filename;
		return $this;
	}

	public function process($filename)
	{
		if (!$this->bootfiles) return false;

		$classes = array();

		foreach ($this->bootfiles as $bootfile)
		{
			$classes = array_merge($classes, $this->getBootClasses($bootfile));
		}

		if (!$classes) return false;

		$this->loadClasses($classes);
		$this->save($filename);

		return true;
	}

	private function save($filename)
	{
		$this->classes = array_reverse($this->classes);

		$result = '';

		foreach ($this->classes as $class)
		{
			$result .= $this->getFileContentByClass($class)."\n";
		}

		if (file_put_contents($filename, $result) === false)
		{
			throw new ErrorSavingFile('Error saving the file "'.$filename.'"');
		}
	}


	private function getBootClasses($filename)
	{
		if (!is_readable($filename))
		{
			throw new ErrorLoadingBootfile('The boot-file "'.$filename.'" MUST be readable');
		}

		$content = file_get_contents($filename);

		if ($content === false)
		{
			throw new ErrorLoadingBootfile('Error loading the boot-file "'.$filename.'"');
		}

		return $this->parseHeader($content);
	}

	private function loadClasses($classes)
	{
		$classes = array_unique($classes);

		foreach ($classes as $class)
		{
			$keyClass = array_search($class, $this->classes);

			if ($keyClass !== false)
			{
				unset($this->classes[$keyClass]);
			}

			$this->classes[] = $class;

			$content = $this->getFileContentByClass($class);
			$parentClasses = $this->parseHeader($content);

			if (!$parentClasses) continue ;

			$this->loadClasses($parentClasses);
		}
	}

	private function parseHeader($file)
	{
		$loads = array();

		$begin = strpos($file, '/**');
		$end = strpos($file, '*/');

		$header = substr($file, $begin, ($end - $begin) + 1);

		if (!preg_match_all('/@load [a-zA-Z\/]*/s', $header, $loads))
		{
			return array();
		}

		$loads = $loads[0];

		foreach ($loads as &$value)
		{
			$value = trim(ltrim($value, '@load'));
		}

		return $loads;

	}

	private function getFileContentByClass($class)
	{
		$file = $this->classesDir.'/'.str_replace('.', '/', $class).'.js';

		if (!is_readable($file))
		{
			throw new ErrorLoadingClass('The class file "'.$file.'" MUST be readable');
		}

		$content = file_get_contents($file);

		if ($content === false)
		{
			throw new ErrorLoadingClass('Error loading the class "'.$class.'"');
		}

		return $content;
	}
}
