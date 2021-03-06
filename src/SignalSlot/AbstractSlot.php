<?php

/**
 * This file is part of the eZ Publish Kernel package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\PlatformHttpCacheBundle\SignalSlot;

use EzSystems\PlatformHttpCacheBundle\PurgeClient\PurgeClientInterface;
use eZ\Publish\Core\SignalSlot\Signal;
use eZ\Publish\Core\SignalSlot\Slot;

/**
 * A abstract slot covering common functions needed for tag based http cahe slots.
 */
abstract class AbstractSlot extends Slot
{
    /**
     * @var \EzSystems\PlatformHttpCacheBundle\PurgeClient\PurgeClientInterface
     */
    protected $purgeClient;

    /**
     * @param \EzSystems\PlatformHttpCacheBundle\PurgeClient\PurgeClientInterface $purgeClient
     */
    public function __construct(PurgeClientInterface $purgeClient)
    {
        $this->purgeClient = $purgeClient;
    }

    final public function receive(Signal $signal)
    {
        if (!$this->supports($signal)) {
            return;
        }

        $this->purgeClient->purge(
            $this->generateTags($signal)
        );
    }

    /**
     * Checks if $signal is supported by this handler.
     *
     * @param \eZ\Publish\Core\SignalSlot\Signal $signal
     *
     * @return bool
     */
    abstract protected function supports(Signal $signal);

    /**
     * Return list of tags to be clered.
     *
     * @param \eZ\Publish\Core\SignalSlot\Signal $signal
     *
     * @return array
     */
    abstract protected function generateTags(Signal $signal);
}
