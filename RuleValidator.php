<?php

namespace OH;

class RuleValidator
{

    const EXAM_ARRAY_KEY = ['valasztott-szak', 'erettsegi-eredmenyek', 'tobbletpontok'];

    const MIN_EXAM_PERCENT = "20%";

    const REQUIRED_BASE_EXAMS = [
        "magyar nyelv és irodalom",
        "történelem",
        "matematika"
    ];

    /**
     * @var ErrorBag
     */
    protected ErrorBag $errorBag;

    public function __construct()
    {
        $this->errorBag = new ErrorBag();
    }

    /**
     * @param array $examArray
     * @return string|true
     */
    public function validate(array $examArray)
    {
        $this->isExamArrayCorrect($examArray);
        $this->isReachTheMinimumExamPercent($examArray);
        $this->isRequiredExamsPresent($examArray, $requiredExams = (new DepartmentsSpecification($examArray['valasztott-szak']['egyetem'], $examArray['valasztott-szak']['kar'], $examArray['valasztott-szak']['szak']))->requirement['required']);
        $this->isRequiredExamsPresent($examArray, self::REQUIRED_BASE_EXAMS);
        $this->isMandatoryExamPresent($examArray);
        if ($this->errorBag->hasErrors()) {
            $errorMsg = $this->errorBag->__toString();
            $this->errorBag->clearErrors();
            return $errorMsg;
        }
        return true;
    }

    /**
     * @param array $examArray
     * @return bool
     */
    public function isExamArrayCorrect(array $examArray): bool
    {
        foreach (self::EXAM_ARRAY_KEY as $key) {
            if (!array_key_exists($key, $examArray)) {
                $this->errorBag->addError("A(z) $key kulcs hiányzik az adatból!");
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $examArray
     * @return bool
     */
    public function isReachTheMinimumExamPercent(array $examArray): bool
    {
        foreach ($examArray['erettsegi-eredmenyek'] as $exam) {
            if (self::MIN_EXAM_PERCENT > $exam['eredmeny']) {
                $this->errorBag->addError("A(z) {$exam['nev']} érettségi eredménye nem érte el a minimum " . self::MIN_EXAM_PERCENT . " -ot!");
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $examArray
     * @param $requiredExams
     * @return bool
     */
    public function isRequiredExamsPresent(array $examArray, $requiredExams): bool
    {
        foreach ($requiredExams as $requiredExam) {
            $isPresent = false;
            foreach ($examArray['erettsegi-eredmenyek'] as $exam) {
                if ($exam['nev'] === $requiredExam) {
                    $isPresent = true;
                }
            }
            if (!$isPresent) {
                $this->errorBag->addError("A(z) $requiredExam érettségi hiányzik!");
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $examArray
     * @return bool
     */
    public function isMandatoryExamPresent(array $examArray): bool
    {
        $mandatoryExam = (new DepartmentsSpecification($examArray['valasztott-szak']['egyetem'], $examArray['valasztott-szak']['kar'], $examArray['valasztott-szak']['szak']))->requirement['mandatory_optional'];
        foreach ($examArray['erettsegi-eredmenyek'] as $exam) {
            if (in_array($exam['nev'], $mandatoryExam)) {
                return true;
            }
        }
        $this->errorBag->addError("A kötelező érettségi hiányzik !");
        return false;
    }
}