<?php

/**
 * This file is part of the eZ Platform Solr Search Engine package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace EzSystems\EzPlatformSolrSearchEngine\Query\Common\FacetBuilderVisitor;

use EzSystems\EzPlatformSolrSearchEngine\Query\FacetBuilderVisitor;
use eZ\Publish\API\Repository\Values\Content\Query\FacetBuilder;
use eZ\Publish\API\Repository\Exceptions\NotImplementedException;

/**
 * Visits the facet builder tree into a Solr query.
 */
class Aggregate extends FacetBuilderVisitor
{
    /**
     * Array of available visitors.
     *
     * @var \EzSystems\EzPlatformSolrSearchEngine\Query\FacetBuilderVisitor[]
     */
    protected $visitors = array();

    /**
     * Construct from optional visitor array.
     *
     * @param \EzSystems\EzPlatformSolrSearchEngine\Query\FacetBuilderVisitor[] $visitors
     */
    public function __construct(array $visitors = array())
    {
        foreach ($visitors as $visitor) {
            $this->addVisitor($visitor);
        }
    }

    /**
     * Adds visitor.
     *
     * @param \EzSystems\EzPlatformSolrSearchEngine\Query\FacetBuilderVisitor $visitor
     */
    public function addVisitor(FacetBuilderVisitor $visitor)
    {
        $this->visitors[] = $visitor;
    }

    /**
     * Check if visitor is applicable to current facet result.
     *
     * @param string $field
     *
     * @return bool
     */
    public function canMap($field)
    {
        return true;
    }

    /**
     * Map Solr facet result back to facet objects.
     *
     * @param string $field
     * @param array $data
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Search\Facet
     */
    public function map($field, array $data)
    {
        foreach ($this->visitors as $visitor) {
            if ($visitor->canMap($field)) {
                return $visitor->map($field, $data);
            }
        }

        throw new \OutOfRangeException('No visitor available for: ' . $field);
    }

    /**
     * CHeck if visitor is applicable to current facet builder.
     *
     * @param FacetBuilder $facetBuilder
     *
     * @return bool
     */
    public function canVisit(FacetBuilder $facetBuilder)
    {
        return true;
    }

    /**
     * Map field value to a proper Solr representation.
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotImplementedException
     *
     * @param FacetBuilder $facetBuilder
     *
     * @return string
     */
    public function visit(FacetBuilder $facetBuilder)
    {
        foreach ($this->visitors as $visitor) {
            if ($visitor->canVisit($facetBuilder)) {
                return $visitor->visit($facetBuilder);
            }
        }

        throw new NotImplementedException(
            'No visitor available for: ' . get_class($facetBuilder)
        );
    }
}
