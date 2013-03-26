<?php

namespace CultuurNet\Search\Command;

use \CultuurNet\Auth\Command\Command;

use \CultuurNet\Search\Guzzle\Service;

use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Input\InputOption;

use \CultuurNet\Search\Parameter as Parameter;

use \CultuurNet\Search\Component\Facet\FacetComponent;

class SearchCommand extends Command {

  public function configure() {
    $this
    ->setName('search')
    ->addOption(
      'search-base-url',
      NULL,
      InputOption::VALUE_REQUIRED,
      'Base url of the search web service'
    )
    ->addOption(
      'facetField',
      NULL,
      InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
      'Facet fields to use'
    )
    ->addOption(
      'fq',
      NULL,
      InputOption::VALUE_REQUIRED,
      'Filter query'
    )
    ->addOption(
      'group',
      NULL,
      InputOption::VALUE_NONE,
      'Group results by CDBID'
    )
    ->addOption(
      'rows',
      NULL,
      InputOption::VALUE_REQUIRED,
      'How many rows to return at once',
      10
    )
    ->addOption(
      'start',
      NULL,
      InputOption::VALUE_REQUIRED,
      'Offset to start'
    )
    ->addOption(
      'sort',
      NULL,
      InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY
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

    $query = new Parameter\Query($in->getArgument('query'));

    $parameters = array(
      $query,
    );

    $facets = $in->getOption('facetField');
    if (!empty($facets)) {
      $facetComponent = new FacetComponent();

      foreach ($facets as $facet) {
        $parameters[] = $facetComponent->facetField($facet);
      }
    }

    $fq = $in->getOption('fq');
    if ($fq) {
      $parameters[] = new Parameter\FilterQuery($fq);
    }

    if ($in->getOption('group')) {
      $parameters[] = new Parameter\Group();
    }

    if ($in->getOptions('rows')) {
      $parameters[] = new Parameter\Rows($in->getOption('rows'));
    }

    if ($in->getOption('start')) {
      $parameters[] = new Parameter\Start($in->getOption('start'));
    }

    $sorts = $in->getOption('sort');
    foreach ($sorts as $sort) {
      // @todo validate the given sort option
      list($field, $direction) = explode(' ', $sort);
      $parameters[] = new Parameter\Sort($field, $direction);
    }

    $result = $service->search($parameters);

    $out->writeln('total: ' . $result->getTotalCount());
    $out->writeln('current: ' . $result->getCurrentCount());

    // @todo consider registering the facet component up-front
    // as a listener to the service, to avoid the need to actively
    // obtain the results afterwards
    if (isset($facetComponent)) {
      $facetComponent->obtainResults($result);

      foreach ($facetComponent->getFacets() as $facet) {
        $out->writeln(str_repeat('-', 10));
        $out->writeln('Facet: ' . $facet->getKey());
        $out->writeln('Results:');
        foreach ($facet->getResult()->getItems() as $name => $number) {
          $out->writeln("{$name} ({$number})");
        }

        $out->writeln(str_repeat('-', 10));
      }
    }

    $currentCount = $result->getCurrentCount();

    if (0 === $currentCount) {
      return;
    }

    $dialog = $this->getHelperSet()->get('dialog');
    /* @var \Symfony\Component\Console\Helper\DialogHelper $dialog */

    // @todo provide misc. interactive options, like showing summary of all results
    //   and to show details of 1 item (implemented now)
    //   repeat till 'exit' option is used

    do {
      $answer = $dialog->askAndValidate($out, "Specify a number (1 to {$currentCount}) to show details, or 'exit' to quit: ", function($answer) use ($currentCount) {
        if (!(ctype_digit($answer) && $answer > 0 && $answer <= $currentCount) && ('exit' !== $answer)) {
          throw new \RuntimeException('Invalid value');
        }

        return $answer;
      });

      if (ctype_digit($answer)) {
        $items = $result->getItems();
        $item = $items[((int)$answer) - 1];

        /* @var \CultureFeed_Cdb_Item_Event $entity */
        $entity = $item->getEntity();

        $details = $entity->getDetails();
        foreach ($details as $detail) {
          // @todo introduce a command line option to specify the language
          if ('nl' === $detail->getLanguage()) {
            $out->writeln($detail->getTitle());

          }
        }
        $out->writeln($entity->getCdbId());
      }

    } while ('exit' !== $answer);
  }
}
