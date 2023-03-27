<?php

use OH\RuleValidator;
use PHPUnit\Framework\TestCase;

class RuleTest extends TestCase
{

    const ELTE_IK_PROGRAMTERVEZO = [
        'egyetem' => 'ELTE',
        'kar' => 'IK',
        'szak' => 'Programtervező informatikus'
    ];

    public function __construct(string $name, public RuleValidator $ruleValidator = new RuleValidator())
    {
        parent::__construct($name);
    }

    public function testExamArrayIntegrationIsGood()
    {
        $examArray = [
            'valasztott-szak' => 'test',
            'erettsegi-eredmenyek' => 'test',
            'tobbletpontok' => 'test'
        ];
        $this->assertTrue($this->ruleValidator->isExamArrayCorrect($examArray));
    }

    public function testExamArrayIntegrationOneRequiredKeyIsMissing()
    {
        $examArray = [
            'valasztott-szak' => 'test',
            'erettsegi-eredmenyek' => 'test',
        ];
        $this->assertFalse($this->ruleValidator->isExamArrayCorrect($examArray));
    }

    public function testMinExamPercentMinValue()
    {
        $examArray = [
            'valasztott-szak' => 'test',
            'erettsegi-eredmenyek' => [
                [
                    'eredmeny' => '20%'
                ]
            ],
            'tobbletpontok' => 'test'
        ];
        $this->assertTrue($this->ruleValidator->isReachTheMinimumExamPercent($examArray));
    }

    public function testMinExamPercentFirstValueIsLowerThanMinValue()
    {
        $examArray = [
            'valasztott-szak' => 'test',
            'erettsegi-eredmenyek' => [
                [
                    'nev' => 'magyar nyelv és irodalom',
                    'eredmeny' => '19%'
                ]
            ],
            'tobbletpontok' => 'test'
        ];
        $this->assertFalse($this->ruleValidator->isReachTheMinimumExamPercent($examArray));
    }
}



