<?php

namespace SampleRollbarPHP;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RollbarHandler;

class App {

	private $log;

	public function __construct()
	{
		// Set up a logger
		$this->log = new Logger('SampleRollbarPHP\App');
		$this->log->pushHandler(new StreamHandler('php://stdout', Logger::INFO));

		// Set up Rollbar
		$config = array(
		    // required
		    'access_token' => 'eb2561a52efb4d4bba5a1d4b68be13e9',
		    // optional - environment name. any string will do.
		    'environment' => 'production',
		    // optional - path to directory your code is in. used for linking stack traces.
		    'root' => '/home/vagrant/Code/sample-rollbar-php'
		);
		\Rollbar::init($config);

		// Set up Rollbar with Monolog
	}

	public function out(string $message)
	{
		$this->log->info($message);
	}

	public function run()
	{

		$this->sendMessage("Test message sent to Rollbar with level 'info'.", "info");
		$this->out("Sent message to Rollbar.");

		$this->monolog("Test error reported through Monolog.");
		$this->out("Logged and error with Monolog.");

		$this->sendException("This is an exception explicitly sent to Rollbar.");
		$this->out("Sent an exception to Rollbar.");

		$this->out("Triggering an uncaught exception.");
		$this->uncaughtException("This is an uncaught exception that should be automatically sent to Rollbar.");

	}

	public function sendMessage(string $msg, string $level = 'info')
	{
		\Rollbar::report_message($msg, $level);
	}

	public function sendException(string $msg)
	{
		try {
			throw new \Exception($msg);
		} catch(\Exception $e) {
			\Rollbar::report_exception($e);
		}
	}

	public function uncaughtException(string $msg)
	{
		throw new \Exception($msg);
	}

	public function monolog(string $msg)
	{
		$log = new Logger('SampleRollbarPHP\Monolog');
		$log->pushHandler(new RollbarHandler(\Rollbar::$instance));

		$log->error($msg);
	}
}