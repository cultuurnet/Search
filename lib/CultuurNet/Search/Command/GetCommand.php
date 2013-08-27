<?php
/**
 *
 */

namespace CultuurNet\Search\Command;

use \CultuurNet\Auth\Command\Command;

use \CultuurNet\Search\Guzzle\Service;

use \CultuurNet\Auth\Guzzle\DefaultHttpClientFactory;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Input\InputOption;

class GetCommand extends Command {
    protected function configure() {
        $this
            ->setName('get')
            ->setDescription('Perform an arbitrary HTTP GET request on the Search API.')
            ->addOption(
                'search-base-url',
                NULL,
                InputOption::VALUE_REQUIRED,
                'Base url of the search web service'
            )
            ->addArgument(
                'path',
                InputArgument::REQUIRED,
                'Path, e.g. search, search/page, search/suggest. A query string can be appended as well.'
            )
            ->addOption(
                'query-file',
                NULL,
                InputOption::VALUE_REQUIRED,
                'JSON-formatted file with query parameters'
            );
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        parent::execute($input, $output);

        $searchBaseUrl = $this->resolveBaseUrl('search', $input);

        $user = $this->session->getUser();
        $tokenCredentials = NULL !== $user ? $user->getTokenCredentials() : NULL;

        $clientFactory = new DefaultHttpClientFactory();
        $client = $clientFactory->createClient($searchBaseUrl, $this->session->getConsumerCredentials(), $tokenCredentials);

        $request = $client->get($input->getArgument('path'));
        $request->getQuery()->setAggregateFunction(array('\Guzzle\Http\QueryString', 'aggregateUsingDuplicates'));

        // @todo add query parameters, from JSON and/or yaml file or from simple CLI options
        //$getRequest->getQuery()->set($key, $value);

        $queryFile = $input->getOption('query-file');
        if (NULL !== $queryFile) {
            $json = file_get_contents($queryFile);
            $config = json_decode($json, TRUE);
            foreach ($config as $key => $value) {
                $request->getQuery()->add($key, $value);
            }
        }

        $response = $request->send();

        $output->writeln('');
        $output->writeln('');
        $output->writeln('Request');
        $output->writeln('');
        $output->writeln((string)$request);

        $output->writeln('');
        $output->writeln('');
        $output->writeln('Response');
        $output->writeln('');
        $output->writeln((string)$response);
        $output->writeln('');
        $output->writeln('');
    }
}
