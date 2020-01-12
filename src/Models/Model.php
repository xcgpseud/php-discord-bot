<?php

namespace PseudBot\Models;

class Model
{
    /**
     * @param array $array
     * @return static
     */
    public static function getFromArray(array $array): self
    {
        $class = get_called_class();
        $self = new $class();

        foreach ($array as $p => $v) {

            // Rename property
            $propSetter = sprintf(
                "set%s",
                join(array_map(fn(string $s): string => ucfirst($s), explode('_', $p)))
            );

            $self->$propSetter($v);
        }

        return $self;
    }

    /**
     * @return static
     */
    public static function make(): self
    {
        $class = get_called_class();
        return new $class();
    }
}
