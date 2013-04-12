<?php

namespace CultuurNet\Search\Command;

use \CultuurNet\Auth\Command\Command;

use \CultuurNet\Search\Guzzle\Service;

use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Input\InputOption;

class SuggestCommand extends Command {

    public function configure() {
        $this
            ->setName('suggest')
            ->addOption(
                'search-base-url',
                NULL,
                InputOption::VALUE_REQUIRED,
                'Base url of the search web service'
            )
            ->addOption(
                'type',
                NULL,
                InputOption::VALUE_REQUIRED,
                'Type of suggestions to return',
                NULL
            )
            ->addArgument(
                'query',
                InputArgument::REQUIRED,
                'Search query'
            );
    }

    public function execute(InputInterface $in, OutputInterface $out) {
        parent::execute($in, $out);

        $searchBaseUrl = $this->resolveBaseUrl('search', $in);

        $user = $this->session->getUser();
        $tokenCredentials = NULL !== $user ? $user->getTokenCredentials() : NULL;

        $service = new Service(
            $searchBaseUrl,
            $this->session->getConsumerCredentials(),
            $tokenCredentials
        );

        $query = $in->getArgument('query');
        $type = $in->getOption('type');

        $suggestionsResult = $service->searchSuggestions($query, $type);

        $suggestions = $suggestionsResult->getSuggestions();

        $out->writeln($suggestions);
    }
}
