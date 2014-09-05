<?php
/**
 * @file
 */ 

class FacetFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testFactoryCreateFromXML()
    {
        $facetsXml = simplexml_load_file(
            __DIR__ . '/samples/facets.xml',
            "SimpleXMLElement",
            0,
            "http://www.cultuurdatabank.com/XMLSchema/CdbXSD/3.2/FINAL"
        );

        $facetFactory = new \CultuurNet\Search\Component\Facet\FacetFactory();

        $facets = $facetFactory->createFromXML($facetsXml);

        $this->assertInternalType('array', $facets);
        $this->assertCount(2, $facets);

        $this->assertContainsOnly('CultuurNet\Search\Component\Facet\Facet', $facets);

        reset($facets);
        $this->assertEquals('category_flanderstouristregion_id', key($facets));
        /**  @var \CultuurNet\Search\Component\Facet\Facet $facet **/
        $facet = current($facets);

        $result = $facet->getResult();
        $this->assertInstanceOf('CultuurNet\Search\Component\Facet\FacetResult', $result);

        $itemsLevel1 = $result->getItems();
        $this->assertInternalType('array', $itemsLevel1);
        $this->assertCount(2, $itemsLevel1);
        $this->assertContainsOnly('CultuurNet\Search\Component\Facet\FacetResultItem', $itemsLevel1);

        reset($itemsLevel1);
        /** @var \CultuurNet\Search\Component\Facet\FacetResultItem $resultItem */
        $resultItem = current($itemsLevel1);
        $this->assertEquals('Kunststad Leuven', $resultItem->getLabel());
        $this->assertEquals('reg.367', $resultItem->getValue());
        $this->assertEquals('5', $resultItem->getTotalResults());
        $itemsLevel2 = $resultItem->getSubItems();
        $this->assertInternalType('array', $itemsLevel2);
        $this->assertCount(0, $itemsLevel2);

        $resultItem = next($itemsLevel1);
        $this->assertEquals('Groene Gordel', $resultItem->getLabel());
        $this->assertEquals('reg.364', $resultItem->getValue());
        $this->assertEquals('50', $resultItem->getTotalResults());
        $itemsLevel2 = $resultItem->getSubItems();
        $this->assertInternalType('array', $itemsLevel2);
        $this->assertCount(0, $itemsLevel2);

        next($facets);
        $this->assertEquals('category_flandersregion_id', key($facets));
        /**  @var \CultuurNet\Search\Component\Facet\Facet $facet **/
        $facet = current($facets);

        $result = $facet->getResult();
        $this->assertInstanceOf('CultuurNet\Search\Component\Facet\FacetResult', $result);

        $itemsLevel1 = $result->getItems();
        $this->assertInternalType('array', $itemsLevel1);
        $this->assertCount(1, $itemsLevel1);
        $this->assertContainsOnly('CultuurNet\Search\Component\Facet\FacetResultItem', $itemsLevel1);

        $resultItem = reset($itemsLevel1);
        $this->assertEquals('Provincie Vlaams-Brabant', $resultItem->getLabel());
        $this->assertEquals('reg.31', $resultItem->getValue());
        $this->assertEquals('721', $resultItem->getTotalResults());
        $itemsLevel2 = $resultItem->getSubItems();
        $this->assertInternalType('array', $itemsLevel2);
        $this->assertCount(2, $itemsLevel2);
        $this->assertContainsOnly('CultuurNet\Search\Component\Facet\FacetResultItem', $itemsLevel2);

        /** @var \CultuurNet\Search\Component\Facet\FacetResultItem $itemLevel2 */
        $itemLevel2 = reset($itemsLevel2);
        $this->assertEquals('Regio Halle - Dilbeek', $itemLevel2->getLabel());
        $this->assertEquals('reg.12', $itemLevel2->getValue());
        $this->assertEquals('5', $itemLevel2->getTotalResults());
        $itemsLevel3 = $itemLevel2->getSubItems();
        $this->assertInternalType('array', $itemsLevel3);
        $this->assertCount(1, $itemsLevel3);

        $itemLevel3 = reset($itemsLevel3);
        $this->assertEquals('Dilbeek', $itemLevel3->getLabel());
        $this->assertEquals('reg.47', $itemLevel3->getValue());
        $this->assertEquals('5', $itemLevel3->getTotalResults());
        $itemsLevel4 = $itemLevel3->getSubItems();
        $this->assertInternalType('array', $itemsLevel4);
        $this->assertCount(1, $itemsLevel4);

        $itemLevel4 = reset($itemsLevel4);
        $this->assertEquals('1701 Itterbeek (Dilbeek)', $itemLevel4->getLabel());
        $this->assertEquals('reg.405', $itemLevel4->getValue());
        $this->assertEquals('5', $itemLevel4->getTotalResults());
        $itemsLevel5 = $itemLevel4->getSubItems();
        $this->assertInternalType('array', $itemsLevel5);
        $this->assertCount(0, $itemsLevel5);
    }
}
