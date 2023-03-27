<?php

namespace OH;

const EXAM_LANGUAGE_POINT = [
    'B2' => 28,
    'C1' => 40,
];

const EXAM_BONUS_POINT_ADVANCED_LEVEL = 50;

/**
 *
 */
class Calculator
{
    /**
     * @param array $examData
     * @return string
     */
    public function calculate(array $examData)
    {
        $basePoint = $this->getBasePointTotal($examData);
        $bonusPoint = $this->getBonusPointTotal($examData);
        return "Full points: " . $basePoint + $bonusPoint . " Base points: " . $basePoint . " Bonus points: " . $bonusPoint;

    }

    /**
     * @param $examData
     * @return float|int
     */
    public function getLangBonusPoint($examData)
    {
        $langPoint = [];
        foreach ($examData as $exam) {
            if (!isset($langPoint[$exam['nyelv']])) {
                $langPoint[$exam['nyelv']] = EXAM_LANGUAGE_POINT[$exam['tipus']];
            }
            $langPoint[$exam['nyelv']] = ($langPoint[$exam['nyelv']] < EXAM_LANGUAGE_POINT[$exam['tipus']]) ? EXAM_LANGUAGE_POINT[$exam['tipus']] : $langPoint[$exam['nyelv']];
        }
        return array_sum($langPoint);
    }

    /**
     * @param $examData
     * @return int
     */
    public function getAdvancedLevelExamPoints($examData)
    {
        $advancedLevelExamPoints = 0;
        foreach ($examData as $exam) {
            if ($exam['tipus'] === 'emelt') {
                $advancedLevelExamPoints += EXAM_BONUS_POINT_ADVANCED_LEVEL;
            }
        }
        return $advancedLevelExamPoints;
    }

    /**
     * @param $examData
     * @return mixed
     */
    public function getBonusPointTotal($examData)
    {
        $totalPoint = $this->getLangBonusPoint($examData['tobbletpontok']) + $this->getAdvancedLevelExamPoints($examData['erettsegi-eredmenyek']);
        return min($totalPoint, 100);
    }

    /**
     * @param $examData
     * @return float|int
     */
    public function getBasePointTotal($examData)
    {
        return ($this->getRequiredExamPoint($examData) + $this->getMandatoryOptionalExamPoint($examData)) * 2;
    }

    /**
     * @param $examData
     * @return int
     */
    public function getRequiredExamPoint($examData)
    {
        $requiredExamNames = (new DepartmentsSpecification($examData['valasztott-szak']['egyetem'], $examData['valasztott-szak']['kar'], $examData['valasztott-szak']['szak']))->requirement['required'];
        $requiredExamPoint = 0;
        foreach ($examData['erettsegi-eredmenyek'] as $exam) {
            if (in_array($exam['nev'], $requiredExamNames)) {
                $requiredExamPoint += (int)$exam['eredmeny'];
            }
        }
        return $requiredExamPoint;
    }

    /**
     * @param $examData
     * @return int
     */
    public function getMandatoryOptionalExamPoint($examData): int
    {
        $mandatoryOptionalExamNames = (new DepartmentsSpecification($examData['valasztott-szak']['egyetem'], $examData['valasztott-szak']['kar'], $examData['valasztott-szak']['szak']))->requirement['mandatory_optional'];
        $mandatoryOptionalExamPoint = 0;
        foreach ($examData['erettsegi-eredmenyek'] as $exam) {
            if (in_array($exam['nev'], $mandatoryOptionalExamNames)) {
                $mandatoryOptionalExamPoint = max($mandatoryOptionalExamPoint, (int)$exam['eredmeny']);
            }
        }
        return (int)$mandatoryOptionalExamPoint;
    }
}