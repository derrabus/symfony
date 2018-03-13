<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\Event;

use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Allows to create a response for a thrown exception.
 *
 * Call setResponse() to set the response that will be returned for the
 * current request. The propagation of this event is stopped as soon as a
 * response is set.
 *
 * You can also call setException() to replace the thrown exception. This
 * exception will be thrown if no response is set during processing of this
 * event.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class GetResponseForExceptionEvent extends GetResponseEvent
{
    /**
     * The exception object.
     *
     * @var \Exception
     *
     * @deprecated
     */
    private $exception;

    /**
     * The exception object.
     *
     * @var \Throwable
     */
    private $throwable;

    /**
     * @var bool
     */
    private $allowCustomResponseCode = false;

    public function __construct(HttpKernelInterface $kernel, Request $request, int $requestType, \Throwable $e)
    {
        parent::__construct($kernel, $request, $requestType);

        $this->setThrowable($e);
    }

    /**
     * Returns the thrown exception.
     *
     * @return \Exception The thrown exception
     *
     * @deprecated since 4.1, use {@see getThrowable()} instead.
     */
    public function getException()
    {
        @trigger_error(sprintf('"%s" is deprecated since Symfony 4.1. Use "getThrowable" instead.', __METHOD__), E_USER_DEPRECATED);

        if (null === $this->exception) {
            $this->exception = $this->throwable instanceof \Exception ? $this->throwable : new FatalThrowableError($this->throwable);
        }

        return $this->exception;
    }

    /**
     * Replaces the thrown exception.
     *
     * This exception will be thrown if no response is set in the event.
     *
     * @param \Exception $exception The thrown exception
     *
     * @deprecated since 4.1, use {@see setThrowable()} instead.
     */
    public function setException(\Exception $exception)
    {
        @trigger_error(sprintf('"%s" is deprecated since Symfony 4.1. Use "setThrowable" instead.', __METHOD__), E_USER_DEPRECATED);

        $this->setThrowable($exception);
    }

    /**
     * Returns the thrown error or exception.
     *
     * @return \Throwable The thrown error or exception
     */
    public function getThrowable(): \Throwable
    {
        return $this->throwable;
    }

    /**
     * Replaces the thrown error or exception.
     *
     * @param \Throwable $throwable The thrown error or exception
     */
    public function setThrowable(\Throwable $throwable): void
    {
        $this->exception = null;
        $this->throwable = $throwable;
    }

    /**
     * Mark the event as allowing a custom response code.
     */
    public function allowCustomResponseCode()
    {
        $this->allowCustomResponseCode = true;
    }

    /**
     * Returns true if the event allows a custom response code.
     *
     * @return bool
     */
    public function isAllowingCustomResponseCode()
    {
        return $this->allowCustomResponseCode;
    }
}
