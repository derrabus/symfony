<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpFoundation\Session\Storage\Handler;

/**
 * Can be used in unit testing or in a situations where persisted sessions are not desired.
 *
 * @author Drak <drak@zikula.org>
 */
class NullSessionHandler extends AbstractSessionHandler
{
    /**
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function close()
    {
        return true;
    }

    /**
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function validateId(string $sessionId)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function doRead(string $sessionId)
    {
        return '';
    }

    /**
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function updateTimestamp(string $sessionId, string $data)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function doWrite(string $sessionId, string $data)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function doDestroy(string $sessionId)
    {
        return true;
    }

    /**
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function gc(int $maxlifetime)
    {
        return true;
    }
}
