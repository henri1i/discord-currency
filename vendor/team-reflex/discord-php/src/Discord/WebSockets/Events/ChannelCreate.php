<?php

/*
 * This file is apart of the DiscordPHP project.
 *
 * Copyright (c) 2021 David Cole <david.cole1340@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the LICENSE.md file.
 */

namespace Discord\WebSockets\Events;

use Discord\Parts\Channel\Channel;
use Discord\WebSockets\Event;
use Discord\Helpers\Deferred;

class ChannelCreate extends Event
{
    /**
     * {@inheritdoc}
     */
    public function handle(Deferred &$deferred, $data): void
    {
        /** @var Channel */
        $channel = $this->factory->create(Channel::class, $data, true);

        if ($channel->is_private) {
            $this->discord->private_channels->push($channel);
        } else {
            if ($guild = $this->discord->guilds->get('id', $channel->guild_id)) {
                $guild->channels->push($channel);
                $this->discord->guilds->push($guild);
            }
        }

        $deferred->resolve($channel);
    }
}
