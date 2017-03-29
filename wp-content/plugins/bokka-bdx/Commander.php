<?php
/**
 * This is for a chain of command pattern
 * This way we can add value handlers in a similar fashion to wordpress hooks
 */

namespace BokkaWP\BDX;

class Commander {

    private $configurations = [];
    private $filters = [];
    private static $instance;

    public function __construct()
    {

    }

    /**
	 * Singleton instantiation
	 * @return [static] instance
	 */
	public static function get_instance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function addCommand($namespace, $callable)
    {
        if (strpos($namespace, 'filter') > 0) {

            if(is_callable($callable))
                $this->filters[$namespace][] = $callable;

        } else if (strpos($namespace, 'configuration') > 0) {

            if(is_callable($callable))
                $this->configurations[$namespace][] = $callable;
        } else {
            return false;
        }
    }

    public function doCommand($namespace, $configuration, $options)
    {

        if (strpos($namespace, 'filter') > 0) {
            $value = $this->filter($namespace, $configuration, $options);
        } else if (strpos($namespace, 'configuration') > 0) {
            $value = $this->configuration($namespace, $configuration, $options);
        } else {
            return false;
        }

        return $value;
    }

    private function configuration($namespace, $configuration, $options)
    {
        if (!isset($this->configurations[$namespace]))
            return $options['value'];

        foreach ($this->configurations[$namespace] as $command) {
            $options['value'] = $command($configuration, $options);
        }

        return $options['value'];
    }

    private function filter($namespace, $configuration, $options)
    {
        if (!isset($this->filters[$namespace]))
            return false;

        foreach ($this->filters[$namespace] as $filter) {
            $value = $filter($configuration, $options);

            if ($value === true) {
                return true;
            }
        }

        return false;

    }

}
