<?php

namespace App\Http\Controllers\Downtime;

use App\Helpers\Str;
use App\Http\Controllers\Controller;
use App\OhDear\Downtime;
use App\OhDear\Services\OhDear;
use App\Traits\FindSites;
use BotMan\BotMan\BotMan;

class ShowController extends Controller
{

    use FindSites;

    /** @var \App\OhDear\Services\OhDear */
    protected $dear;

    public function __construct(OhDear $dear)
    {
        $this->dear = $dear;
    }

    /**
     * Handle the incoming request.
     *
     * @param \BotMan\BotMan\BotMan $bot
     * @param string $url
     *
     * @return void
     */
    public function __invoke(BotMan $bot, string $url)
    {
        $bot->types();

        $site = $this->find($url);

        if (! $site) {
            $bot->reply(trans('ohdear.sites.not_found'));

            return;
        }

        $downtime = $this->dear->getSiteDowntime($site->id);

        if ($downtime->isEmpty()) {
            $bot->reply(trans('ohdear.downtime.perfect'));

            return;
        }

        $bot->reply(trans('ohdear.downtime.summary', [
            'elapsed' => $downtime->first()->elapsed,
            'emoji' => $downtime->first()->getElapsedEmoji()
        ]));

        $downtime->each(function (Downtime $downtime) use ($bot) {

            $bot->reply(trans('ohdear.downtime.result', [
                'downtime' => $downtime->getDowntime(),
                'date' => $downtime->startedAt
            ]));
        });
    }


}
