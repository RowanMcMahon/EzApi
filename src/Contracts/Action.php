<?php

namespace Contracts;

use Contracts\Request;
use ParamValidators\IsBool;
use ParamValidators\Length;
use ParamValidators\Accepts;
use ParamValidators\IsArray;
use ParamValidators\IsObject;
use ParamValidators\IsString;
use ParamValidators\IsInteger;
use ParamValidators\IsNumeric;

abstract class Action
{
    /**
     * @var array $args
     * array of arguments to be passed to the action
     */
    protected $args;

    /**
     * @var array $schema
     * defines the parameters that are accepted by the action. each parameter is an array with the following keys:
     * - index: the order of the parameter in the args array, if not provided the parameter order will be used.
     * - validation: an array of validators to use to validate the parameter. Can also be a closure for custom validation.
     * - default: the default value for the parameter
     * - required: whether the parameter is required
     * @example [
     *    'foo' => [
     *      'index' => 0,
     *      'validation' => [
     *          'string',
     *          'length' => [
     *              'max' => 255
     *          ],
     *          'accepts' => [
     *              'bar',
     *              'baz'
     *          ],
     *      ],
     *      'default' => 'bar',
     *    ]
     * ],
     */
    protected $schema = [];

    /**
     * @var array $baseValidators
     * an array of validators that are provided by default to any child classes. Can be overwritten inside child classes using the $validators property.
     */
    static $baseValidators = [
        'string' => IsString::class,
        'int' => IsInteger::class,
        'integer' => IsInteger::class,
        'number' => IsNumeric::class,
        'bool' => IsBool::class,
        'boolean' => IsBool::class,
        'array' => IsArray::class,
        'object' => IsObject::class,
        'length' => Length::class,
        'accepts' => Accepts::class,
    ];

    /**
     * @var array $validators
     * an array of validators that can be populated by child classes.
     */
    protected $validators = [];

    function __construct($args)
    {
        $this->args = $this->applySchema($args);
    }

    /**
     * Get all validator classes by merging the defaults with those defined inside child classes
     * @return array
     */
    public function getValidators()
    {
        return array_merge(self::$baseValidators, $this->validators);
    }

    /**
     * Validate the args array against the schema in $this->schema
     * @param array $args
     * @return array array of validated args
     */
    function applySchema($args)
    {
        $resolved = [];
        foreach ($this->schema as $arg => $settings)
        {
            //when no settings are provided for arg, we use the array value at index position as the parameter name
            $arg = is_int($arg) ? $settings : $arg;

            //empty settings for when none are provided
            $settings = is_int($arg) ? [] : $settings;

            //if index is not explicitly defined, use the key as the index
            $index = $settings['index'] ?? array_search($arg, array_keys($this->schema));

            //check defaults
            if (!isset($args[$index]))
            {
                if (isset($settings['default']))
                {
                    $resolved[$arg] = $settings['default'];
                    continue;
                }

                if (isset($settings['required']) && $settings['required'])
                {
                    throw new \Exception('Argument ' . $arg . ' at position ' . $index . ' is required');
                }
            }

            //run validators
            if (array_key_exists('validation', $settings))
            {
                foreach ($settings['validation'] as $validatorAlias => $validatorSettings)
                {
                    //when no settings are provided for validator, we use the array value at index position as the validator alias
                    $validatorAlias = is_int($validatorAlias) ? $validatorSettings : $validatorAlias;

                    //empty settings for when none are provided
                    $validatorSettings = is_int($validatorAlias) ? [] : $validatorSettings;

                    if (is_callable($validatorAlias))
                    {
                        $result = $validatorAlias($args[$index], $args);
                        if ($result !== true)
                        {
                            throw new \Exception('Argument ' . $arg . ' at position ' . $index . ' is invalid: ' . $result);
                        }
                    }

                    $validators = $this->getValidators();
                    if (!array_key_exists($validatorAlias, $validators))
                    {
                        throw new \Exception('Validator ' . $validatorAlias . ' does not exist');
                    }

                    $validator = new $validators[$validatorAlias]();

                    if (isset($args[$index]) && $args[$index] !== null && !$validator->validate($args[$index], $validatorSettings ?? []))
                    {
                        throw new \Exception('Argument ' . $arg . ' at position ' . $index . ' is invalid: ' . $validator->getMessage());
                    }
                }
            }

            //set resolved value
            if (isset($args[$index]))
            {
                $resolved[$arg] = $args[$index];
            }
        }

        return $resolved;
    }

    /**
     * This method can be overwritten by child classes. This allows for custom requests depending on the needs of certain actions
     * e.g. some actions require a basic auth header.
     *
     * @param string $method
     * @param string $host
     * @param string $endpoint
     * @param array $data
     * @param array $headers
     * @return void
     */
    public function request($method, $host, $endpoint, $data = [], $headers = [])
    {
        $request = new Request(
            $host,
            $endpoint,
            $method,
            $data,
            $headers
        );

        return $request->send();
    }

    /**
     * The main method of the action. This method should be overwritten by child classes.
     */
    public abstract function handle();
}
