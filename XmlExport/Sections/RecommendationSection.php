<?php

namespace XmlExport\Sections;

use DOMElement;
use XmlExport\XmlDocumentBuilder;

/**
 * Секция рекомендаций по улучшению условий труда.
 */
class RecommendationSection
{
    /** @var XmlDocumentBuilder */
    private $builder;

    /**
     * @param XmlDocumentBuilder $builder
     */
    public function __construct($builder)
    {
        $this->builder = $builder;
    }

    /**
     * Вставить рекомендации.
     *
     * @param DOMElement $parent
     * @param array $recommendations
     * @return void
     */
    public function insert($parent, $recommendations)
    {
        if (empty($recommendations)) {
            return;
        }

        $recomendation = $this->builder->insertElement($parent, 'Recommendations');
        $improvement = $this->builder->insertElement($recomendation, 'WorkingConditionsImprovement');

        foreach ($recommendations as $value) {
            $arrangement = $this->builder->insertElement($improvement, 'Arrangement');
            $this->builder->insertElement($arrangement, 'Name', $value['sActivityName']);
            $this->builder->insertElement($arrangement, 'Purpose', $value['sActivityTarget']);
            $this->builder->insertElement($arrangement, 'DueDate', $value['sTerm']);
            $this->builder->insertElement($arrangement, 'EngagedUnits', $value['sInvolved']);
        }
    }
}
