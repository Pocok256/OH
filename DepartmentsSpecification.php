<?php

namespace OH;
class DepartmentsSpecification
{
    readonly array $requirement;
    public const DEPARTMENTS_SPECIFICATION = [
        [
            'specification' => [
                'university' => 'ELTE',
                'faculty' => 'IK',
                'course' => 'Programtervező informatikus',
            ],
            'requirement' => [
                'required' => [
                    'matematika',
                ],
                'mandatory_optional' => [
                    'biológia',
                    'fizika',
                    'informatika',
                    'kémia',
                ],
            ],

        ],
    ];

    /**
     * @param string $university
     * @param string $faculty
     * @param string $course
     */
    public function __construct(string $university, string $faculty, string $course)
    {
        $this->requirement = $this->setDepartmentRequirement($university, $faculty, $course);
    }

    /**
     * @param $university
     * @param $faculty
     * @param $course
     * @return array|mixed
     */
    private function setDepartmentRequirement($university, $faculty, $course)
    {
        foreach (self::DEPARTMENTS_SPECIFICATION as $department) {
            if (array_diff($department['specification'], [$university, $faculty, $course]) === []) {
                return $department['requirement'];
            }
        }
        // TODO: throw exception
        return [];
    }

}