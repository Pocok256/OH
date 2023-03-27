<?php

use OH\DepartmentsSpecification;
use PHPUnit\Framework\TestCase;

class DepartmentsSpecificationTest extends TestCase
{

    public function testGetDepartmentSpecification()
    {
        $testDepartment = DepartmentsSpecification::DEPARTMENTS_SPECIFICATION;
        $testDepartment = array_shift($testDepartment);
        $department = new DepartmentsSpecification($testDepartment['specification']['university'], $testDepartment['specification']['faculty'], $testDepartment['specification']['course']);
        $this->assertEquals($department->requirement, $testDepartment['requirement']);
    }

    public function testGetDepartmentSpecificationWithBadData()
    {
        $departmentRequirement = new DepartmentsSpecification("ELTE", "IK", "BAD DATA");
        $this->assertEquals([], $departmentRequirement->requirement);
    }
}
