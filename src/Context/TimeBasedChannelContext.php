<?php

declare(strict_types=1);

namespace App\Context;

use App\DateTime\ClockInterface;
use Sylius\Bundle\ChannelBundle\Doctrine\ORM\ChannelRepository;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Model\ChannelInterface;

class TimeBasedChannelContext implements ChannelContextInterface
{

    private ChannelRepository $channelRepository;

    private ClockInterface $clock;

    /**
     * @param ChannelRepository $channelRepository
     */
    public function __construct(ChannelRepository $channelRepository, ClockInterface $clock)
    {
        $this->channelRepository = $channelRepository;
        $this->clock = $clock;
    }


    public function getChannel(): ChannelInterface
    {
        if($this->clock->isNight()) {
            return $this->channelRepository->findOneByCode('NIGHT');
        }

        return $this->channelRepository->findOneBy([]);
    }
}
