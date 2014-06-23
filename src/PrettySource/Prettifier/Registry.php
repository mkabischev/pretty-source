<?php

namespace PrettySource\Prettifier;

class Registry
{

    /**
     * @var \PrettifierInterface[]
     */
    private $prettifiers;

    public function add(PrettifierInterface $prettifier)
    {
        $this->prettifiers[$prettifier->getFormat()] = $prettifier;
    }

    /**
     * @param $input
     *
     * @return PrettifierInterface
     */
    public function find($input)
    {
        foreach ($this->prettifiers as $prettifier) {
            if ($prettifier->match($input)) {
                return $prettifier;
            }
        }

        return null;
    }

    /**
     * @param string $format
     * @return PrettifierInterface
     * @throws \OutOfBoundsException
     */
    public function get($format)
    {
        if (array_key_exists($format, $this->prettifiers)) {
            return $this->prettifiers[$format];
        }

        throw new \OutOfBoundsException("unknown format: {$format}");
    }

    /**
     * @return string[]
     */
    public function getAvailableFormats()
    {
        return array_keys($this->prettifiers);
    }


}