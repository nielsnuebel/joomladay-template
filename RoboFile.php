<?php
require 'vendor/autoload.php';

if (!defined('JPATH_BASE'))
{
	define('JPATH_BASE', __DIR__);
}

class RoboFile extends \Robo\Tasks
{
	// Load tasks from composer, see composer.json
	use \joomla_projects\robo\loadTasks;
	use \Joomla\Jorobo\Tasks\loadTasks;

	/**
	 * Local configuration parameters
	 *
	 * @var array
	 */
	private $configuration = array();

	/**
	 * Path to the local CMS root
	 *
	 * @var string
	 */
	private $cmsPath = '';

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->configuration = $this->getConfiguration();

		$this->cmsPath = $this->getCmsPath();

		// Set default timezone (so no warnings are generated if it is not set)
		date_default_timezone_set('UTC');
	}

	/**
	 * Run the specified checker tool. Valid options are phpmd, phpcs, phpcpd
	 *
	 * @param string $tool
	 *
	 * @return bool
	 */
	public function runChecker($tool = null)
	{
		if ($tool === null) {
			$this->say('You have to specify a tool name as argument. Valid tools are phpmd, phpcs, phpcpd.');
			return false;
		}

		if (!in_array($tool, array('phpmd', 'phpcs', 'phpcpd', 'phpmamp') )) {
			$this->say('The tool you required is not known. Valid tools are phpmd, phpcs, phpcpd.');
			return false;
		}

		switch ($tool) {
			case 'phpmd':
				return $this->runPhpmd();
			case 'phpcs':
				return $this->runPhpcs();
			case 'phpcpd':
				return $this->runPhpcpd();
		}
	}


	/**
	 * Get the correct CMS root path
	 *
	 * @return string
	 */
	private function getCmsPath()
	{
		if (empty($this->configuration->cmsPath))
		{
			return 'tests/joomla-cms3';
		}

		if (!file_exists(dirname($this->configuration->cmsPath)))
		{
			$this->say("Cms path written in local configuration does not exists or is not readable");
			return 'tests/joomla-cms3';
		}

		return $this->configuration->cmsPath;
	}

	/**
	 * Get (optional) configuration from an external file
	 *
	 * @return \stdClass|null
	 */
	public function getConfiguration()
	{
		$configurationFile = __DIR__ . '/RoboFile.ini';

		if (!file_exists($configurationFile))
		{
			$this->say("No local configuration file");
			return null;
		}

		$configuration = parse_ini_file($configurationFile);
		if ($configuration === false)
		{
			$this->say('Local configuration file is empty or wrong (check is it in correct .ini format');
			return null;
		}

		return json_decode(json_encode($configuration));
	}

	/**
	 * Run the phpmd tool
	 */
	private function runPhpmd()
	{
		return $this->_exec('phpmd ' . __DIR__ . '/src xml cleancode,codesize,controversial,design,naming,unusedcode');
	}

	/**
	 * Run the phpcs tool
	 */
	private function runPhpcs()
	{
		$this->_exec('phpcs --standard=Joomla ' . __DIR__ . '/src');
	}

	/**
	 * Run the phpcpd tool
	 */
	private function runPhpcpd()
	{
		$this->_exec('phpcpd ' . __DIR__ . '/src');
	}

	/**
	 * Build the joomla extension package
	 *
	 * @param   array  $params  Additional params
	 *
	 * @return  void
	 */
	public function build($params = ['dev' => false])
	{
		if (!file_exists('jorobo.ini'))
		{
			$this->_copy('jorobo.dist.ini', 'jorobo.ini');
		}

		$this->taskBuild($params)->run();
	}

	/**
	 * Map into Joomla installation.
	 *
	 * @param   String   $target    The target joomla instance
	 *
	 * @return  void
	 */
	public function map($target)
	{
		$this->taskMap($target)->run();;
	}

	/**
	 * Update copyright headers for this project. (Set the text up in the jorobo.ini)
	 *
	 * @param   array  $params  - Opt params
	 *
	 * @return  void
	 */
	public function headers($params = array())
	{
		$this->taskCopyrightHeaders($params)->run();
	}
}