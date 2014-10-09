<?php

namespace CultuurNet\Search\Command;

use CultuurNet\Auth\ConsumerCredentials;
use CultuurNet\Auth\TokenCredentials;
use CultuurNet\Search\Guzzle\Service;

use Guzzle\Log\ClosureLogAdapter;
use Guzzle\Plugin\Log\LogPlugin;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SearchServiceFactory {

    private function __construct() {}

    /**
     * Creates a search service suitable for the command line.
     *
     * @param InputInterface $in
     * @param OutputInterface $out
     * @param string $baseUrl
     * @param ConsumerCredentials $consumer
     * @param TokenCredentials $token
     *
     * @return Service
     */
    public static function createSearchService(
      InputInterface $in,
      OutputInterface $out,
      $baseUrl,
      ConsumerCredentials $consumer,
      TokenCredentials $token)
    {
        $searchService = new Service($baseUrl, $consumer, $token);

        if (TRUE == $in->getOption('debug')) {
            $adapter = new ClosureLogAdapter(function ($message, $priority, $extras) use ($out) {
                // @todo handle $priority
                $out->writeln($message);
            });
            $format = "\n\n# Request:\n{request}\n\n# Response:\n{response}\n\n# Errors: {curl_code} {curl_error}\n\n";
            $log = new LogPlugin($adapter, $format);

            $searchService->getHttpClientFactory()->addSubscriber($log);
        }

        return $searchService;
    }
}
