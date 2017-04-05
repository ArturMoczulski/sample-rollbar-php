<?php

namespace SampleRollbarPHP;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class App {

	private $log;

	public function __construct()
	{
		// Set up a logger
		$this->log = new Logger('SampleRollbarPHP\App');
		$this->log->pushHandler(new StreamHandler('php://stdout', Logger::INFO));

		// Set up Rollbar
		// require_once 'rollbar.php';

		$config = array(
		    // required
		    'access_token' => 'eb2561a52efb4d4bba5a1d4b68be13e9',
		    // optional - environment name. any string will do.
		    'environment' => 'production',
		    // optional - path to directory your code is in. used for linking stack traces.
		    'root' => '/home/vagrant/Code/sample-rollbar-php'
		);
		\Rollbar::init($config);
	}

	public function out(string $message)
	{
		$this->log->info($message);
	}

	public function run()
	{

		\Rollbar::report_message('testing 123', 'info');
		$this->out("Sent message to rollbar");

	}
}