<?php
// src/Dahlberg/PodrBundle/Lib/DataManipulator.php;
namespace Dahlberg\PodrBundle\Lib;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Traversable;

/**
 * Class DataManipulator
 * @package Dahlberg\PodrBundle\Lib
 */
class DataManipulator {
    /**
     * Associates any traversable input into its key and value
     *
     * @param  mixed  $input A Traversable input
     * @param  string $key   Key to associate
     * @param  string $value Value to associate
     * @return array  Associated array
     *
     * @throws InvalidArgumentException When Input is not traversable
     */
    public function associate($input, $key, $value)
    {
        if (!is_array($input) && !($input instanceof Traversable)) {
            throw new InvalidArgumentException("Expected traversable");
        }

        $out = array();

        foreach ($input as $row) {
            $out[$this->getInput($row, $key)] = $this->getInput($row, $value);
        }

        return $out;
    }

    /**
     * Fetches the input of a given property
     *
     * @param  mixed  $row  An array or an object
     * @param  string $find Property to find
     * @return mixed  Property's value
     *
     * @throws UnexpectedValueException When no matching with $find where found
     */
    protected function getInput($row, $find)
    {
        if (is_array($row) && array_key_exists($find, $row)) {
            return $row[$find];
        }

        if (is_object($row)) {
            if (isset($row->$find)) {
                return $row->$find;
            }

            $method = sprintf("get%s", $find);

            if (method_exists($row, $method)) {
                return $row->$method();
            }
        }

        throw new UnexpectedValueException("Could not find any method to resolve");
    }
}