<?php

/**
 * File containing the SortClauseVisitor\Location\Priority class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace EzSystems\EzPlatformSolrSearchEngine\Query\Location\SortClauseVisitor\Location;

use EzSystems\EzPlatformSolrSearchEngine\Query\SortClauseVisitor;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;

/**
 * Visits the sortClause tree into a Solr query.
 */
class Priority extends SortClauseVisitor
{
    /**
     * Check if visitor is applicable to current sortClause.
     *
     * @param SortClause $sortClause
     *
     * @return bool
     */
    public function canVisit(SortClause $sortClause)
    {
        return $sortClause instanceof SortClause\Location\Priority;
    }

    /**
     * Map field value to a proper Solr representation.
     *
     * @param SortClause $sortClause
     *
     * @return string
     */
    public function visit(SortClause $sortClause)
    {
        return 'priority_id' . $this->getDirection($sortClause);
    }
}
