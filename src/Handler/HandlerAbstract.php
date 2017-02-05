<?php

namespace Tailer\Handler;

/**
 * Class HandlerAbstract
 */
abstract class HandlerAbstract
{
    /**
     * Magic invoke
     */
    public function __invoke()
    {
        $this->invoke();
    }

    /**
     * @return mixed
     */
    abstract public function invoke();
}
