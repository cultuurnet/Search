<?php

namespace CultuurNet\Search\Command;

use CultuurNet\Auth\Command\CommandLineServiceFactory;
use CultuurNet\Auth\ConsumerCredentials;
use CultuurNet\Auth\TokenCredentials;
use CultuurNet\Search\Guzzle\Service as SearchService;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SearchServiceFactory extends CommandLineServiceFactory{

    /**
     * @inheritdoc
     *
     * @return SearchService
     */
    public function createService(
      InputInterface $in,
      OutputInterface $out,
      $baseUrl,
      ConsumerCredentials $consumer,
      TokenCredentials $token = null)
    {
        $service = new SearchService($baseUrl, $consumer, $token);

        $this->registerSubscribers($in, $out, $service);

        return $service;
    }
}
