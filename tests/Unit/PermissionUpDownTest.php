<?php

namespace Exceedone\Exment\Tests\Unit;

use Exceedone\Exment\Model\CustomValue;
use Exceedone\Exment\Model\LoginUser;
use Exceedone\Exment\Model\CustomTable;
use Exceedone\Exment\Model\System;
use Exceedone\Exment\Enums\JoinedOrgFilterType;
use Exceedone\Exment\Tests\DatabaseTransactions;

class PermissionUpDownTest extends UnitTestBase
{
    use DatabaseTransactions;

    /**
     * @return void
     */
    protected function init()
    {
        System::clearCache();
        \Exceedone\Exment\Middleware\Morph::defineMorphMap();
    }

    // User - Organization -------------------------------------------

    /**
     * @return void
     */
    public function testOrganizationCompany()
    {
        $this->executeTestOrganizationUser(5, JoinedOrgFilterType::ONLY_JOIN, [1], true);
    }

    /**
     * @return void
     */
    public function testOrganizationDev()
    {
        $this->executeTestOrganizationUser(6, JoinedOrgFilterType::ONLY_JOIN, [2], true);
    }

    /**
     * @return void
     */
    public function testOrganizationDev1()
    {
        $this->executeTestOrganizationUser(7, JoinedOrgFilterType::ONLY_JOIN, [4], true);
    }

    /**
     * @return void
     */
    public function testOrganizationCompanyUpper()
    {
        $this->executeTestOrganizationUser(5, JoinedOrgFilterType::ONLY_UPPER, [1, 2, 3, 4, 5], true);
    }

    /**
     * @return void
     */
    public function testOrganizationDevUpper()
    {
        $this->executeTestOrganizationUser(6, JoinedOrgFilterType::ONLY_UPPER, [2, 4, 5], true);
    }

    /**
     * @return void
     */
    public function testOrganizationDev1Upper()
    {
        $this->executeTestOrganizationUser(7, JoinedOrgFilterType::ONLY_UPPER, [4], true);
    }

    /**
     * @return void
     */
    public function testOrganizationCompanyDowner()
    {
        $this->executeTestOrganizationUser(5, JoinedOrgFilterType::ONLY_DOWNER, [1], true);
    }

    /**
     * @return void
     */
    public function testOrganizationDevDowner()
    {
        $this->executeTestOrganizationUser(6, JoinedOrgFilterType::ONLY_DOWNER, [1, 2], true);
    }

    /**
     * @return void
     */
    public function testOrganizationDev1Downer()
    {
        $this->executeTestOrganizationUser(7, JoinedOrgFilterType::ONLY_DOWNER, [1, 2, 4], true);
    }

    /**
     * @return void
     */
    public function testOrganizationCompanyAll()
    {
        $this->executeTestOrganizationUser(5, JoinedOrgFilterType::ALL, [1, 2, 3, 4, 5], true);
    }

    /**
     * @return void
     */
    public function testOrganizationDevAll()
    {
        $this->executeTestOrganizationUser(6, JoinedOrgFilterType::ALL, [1, 2, 4, 5], true);
    }

    /**
     * @return void
     */
    public function testOrganizationDev1All()
    {
        $this->executeTestOrganizationUser(7, JoinedOrgFilterType::ALL, [1, 2, 4], true);
    }


    // Organization - Organization -------------------------------------------

    /**
     * @return void
     */
    public function testOrganizationOrgCompany()
    {
        $this->executeTestOrganizationOrg(1, JoinedOrgFilterType::ONLY_JOIN, [1], true);
    }

    /**
     * @return void
     */
    public function testOrganizationOrgDev()
    {
        $this->executeTestOrganizationOrg(2, JoinedOrgFilterType::ONLY_JOIN, [2], true);
    }

    /**
     * @return void
     */
    public function testOrganizationOrgDev1()
    {
        $this->executeTestOrganizationOrg(3, JoinedOrgFilterType::ONLY_JOIN, [3], true);
    }

    /**
     * @return void
     */
    public function testOrganizationOrgCompanyUpper()
    {
        $this->executeTestOrganizationOrg(1, JoinedOrgFilterType::ONLY_UPPER, [1, 2, 3, 4, 5], true);
    }

    /**
     * @return void
     */
    public function testOrganizationOrgDevUpper()
    {
        $this->executeTestOrganizationOrg(2, JoinedOrgFilterType::ONLY_UPPER, [2, 4, 5], true);
    }

    /**
     * @return void
     */
    public function testOrganizationOrgDev1Upper()
    {
        $this->executeTestOrganizationOrg(4, JoinedOrgFilterType::ONLY_UPPER, [4], true);
    }

    /**
     * @return void
     */
    public function testOrganizationOrgCompanyDowner()
    {
        $this->executeTestOrganizationOrg(1, JoinedOrgFilterType::ONLY_DOWNER, [1], true);
    }

    /**
     * @return void
     */
    public function testOrganizationOrgDevDowner()
    {
        $this->executeTestOrganizationOrg(2, JoinedOrgFilterType::ONLY_DOWNER, [1, 2], true);
    }

    /**
     * @return void
     */
    public function testOrganizationOrgDev1Downer()
    {
        $this->executeTestOrganizationOrg(4, JoinedOrgFilterType::ONLY_DOWNER, [1, 2, 4], true);
    }

    /**
     * @return void
     */
    public function testOrganizationOrgCompanyAll()
    {
        $this->executeTestOrganizationOrg(1, JoinedOrgFilterType::ALL, [1, 2, 3, 4, 5], true);
    }

    /**
     * @return void
     */
    public function testOrganizationOrgDevAll()
    {
        $this->executeTestOrganizationOrg(2, JoinedOrgFilterType::ALL, [1, 2, 4, 5], true);
    }

    /**
     * @return void
     */
    public function testOrganizationOrgDev1All()
    {
        $this->executeTestOrganizationOrg(4, JoinedOrgFilterType::ALL, [1, 2, 4], true);
    }


    // Role Group -------------------------------------------

    /**
     * @return void
     */
    public function testRoleGroupAdmin()
    {
        $this->executeTestRoleGroup(1, JoinedOrgFilterType::ALL, true);
    }

    /**
     * @return void
     */
    public function testRoleGroupDirect()
    {
        $this->executeTestRoleGroup(6, JoinedOrgFilterType::ALL, true);
    }

    /**
     * @return void
     */
    public function testRoleGroupUpper()
    {
        $this->executeTestRoleGroup(5, JoinedOrgFilterType::ALL, true);
    }

    /**
     * @return void
     */
    public function testRoleGroupDowner()
    {
        $this->executeTestRoleGroup(7, JoinedOrgFilterType::ALL, true);
    }

    /**
     * @return void
     */
    public function testRoleGroupOtherOrg()
    {
        $this->executeTestRoleGroup(10, JoinedOrgFilterType::ALL, false);
    }

    /**
     * @return void
     */
    public function testRoleGroupDirectOnlyUpper()
    {
        $this->executeTestRoleGroup(6, JoinedOrgFilterType::ONLY_UPPER, true);
    }

    /**
     * @return void
     */
    public function testRoleGroupUpperOnlyUpper()
    {
        $this->executeTestRoleGroup(5, JoinedOrgFilterType::ONLY_UPPER, true);
    }

    /**
     * @return void
     */
    public function testRoleGroupDownerOnlyUpper()
    {
        $this->executeTestRoleGroup(7, JoinedOrgFilterType::ONLY_UPPER, false);
    }

    /**
     * @return void
     */
    public function testRoleGroupOtherOrgOnlyUpper()
    {
        $this->executeTestRoleGroup(10, JoinedOrgFilterType::ONLY_UPPER, false);
    }

    /**
     * @return void
     */
    public function testRoleGroupDirectOnlyDowner()
    {
        $this->executeTestRoleGroup(6, JoinedOrgFilterType::ONLY_DOWNER, true);
    }

    /**
     * @return void
     */
    public function testRoleGroupUpperOnlyDowner()
    {
        $this->executeTestRoleGroup(5, JoinedOrgFilterType::ONLY_DOWNER, false);
    }

    /**
     * @return void
     */
    public function testRoleGroupDownerOnlyDowner()
    {
        $this->executeTestRoleGroup(7, JoinedOrgFilterType::ONLY_DOWNER, true);
    }

    /**
     * @return void
     */
    public function testRoleGroupOtherOrgOnlyDowner()
    {
        $this->executeTestRoleGroup(10, JoinedOrgFilterType::ONLY_DOWNER, false);
    }

    /**
     * @return void
     */
    public function testRoleGroupDirectOnlyJoin()
    {
        $this->executeTestRoleGroup(6, JoinedOrgFilterType::ONLY_JOIN, true);
    }

    /**
     * @return void
     */
    public function testRoleGroupUpperOnlyJoin()
    {
        $this->executeTestRoleGroup(5, JoinedOrgFilterType::ONLY_JOIN, false);
    }

    /**
     * @return void
     */
    public function testRoleGroupDownerOnlyJoin()
    {
        $this->executeTestRoleGroup(7, JoinedOrgFilterType::ONLY_JOIN, false);
    }

    /**
     * @return void
     */
    public function testRoleGroupOtherOrgOnlyJoin()
    {
        $this->executeTestRoleGroup(10, JoinedOrgFilterType::ONLY_JOIN, false);
    }


    // Custom Value -------------------------------------------

    /**
     * @return void
     */
    public function testCustomValueAdmin()
    {
        $this->executeTestCustomValue(1, JoinedOrgFilterType::ONLY_JOIN, true);
    }

    /**
     * @return void
     */
    public function testCustomValueDirect()
    {
        $this->executeTestCustomValue(6, JoinedOrgFilterType::ONLY_JOIN, true);
    }

    /**
     * @return void
     */
    public function testCustomValueUpper()
    {
        $this->executeTestCustomValue(5, JoinedOrgFilterType::ONLY_JOIN, false);
    }

    /**
     * @return void
     */
    public function testCustomValueDowner()
    {
        $this->executeTestCustomValue(7, JoinedOrgFilterType::ONLY_JOIN, false);
    }

    /**
     * @return void
     */
    public function testCustomValueOtherOrg()
    {
        $this->executeTestCustomValue(10, JoinedOrgFilterType::ONLY_JOIN, false);
    }

    /**
     * @return void
     */
    public function testCustomValueDirectOnlyUpper()
    {
        $this->executeTestCustomValue(6, JoinedOrgFilterType::ONLY_UPPER, true);
    }

    /**
     * @return void
     */
    public function testCustomValueUpperOnlyUpper()
    {
        $this->executeTestCustomValue(5, JoinedOrgFilterType::ONLY_UPPER, true);
    }

    /**
     * @return void
     */
    public function testCustomValueDownerOnlyUpper()
    {
        $this->executeTestCustomValue(7, JoinedOrgFilterType::ONLY_UPPER, false);
    }

    /**
     * @return void
     */
    public function testCustomValueOtherOrgOnlyUpper()
    {
        $this->executeTestCustomValue(10, JoinedOrgFilterType::ONLY_UPPER, false);
    }


    /**
     * @return void
     */
    public function testCustomValueDirectOnlyDowner()
    {
        $this->executeTestCustomValue(6, JoinedOrgFilterType::ONLY_DOWNER, true);
    }

    /**
     * @return void
     */
    public function testCustomValueUpperOnlyDowner()
    {
        $this->executeTestCustomValue(5, JoinedOrgFilterType::ONLY_DOWNER, false);
    }

    /**
     * @return void
     */
    public function testCustomValueDownerOnlyDowner()
    {
        $this->executeTestCustomValue(7, JoinedOrgFilterType::ONLY_DOWNER, true);
    }

    /**
     * @return void
     */
    public function testCustomValueOtherOrgOnlyDowner()
    {
        $this->executeTestCustomValue(10, JoinedOrgFilterType::ONLY_DOWNER, false);
    }


    /**
     * @return void
     */
    public function testCustomValueDirectAll()
    {
        $this->executeTestCustomValue(6, JoinedOrgFilterType::ALL, true);
    }

    /**
     * @return void
     */
    public function testCustomValueUpperAll()
    {
        $this->executeTestCustomValue(5, JoinedOrgFilterType::ALL, true);
    }

    /**
     * @return void
     */
    public function testCustomValueDownerAll()
    {
        $this->executeTestCustomValue(7, JoinedOrgFilterType::ALL, true);
    }

    /**
     * @return void
     */
    public function testCustomValueOtherOrgAll()
    {
        $this->executeTestCustomValue(10, JoinedOrgFilterType::ALL, false);
    }

    /**
     * @param int $loginId
     * @param string $joinedOrgFilterType
     * @param array<int> $antiOrganizations
     * @param bool $antiResult
     * @return void
     */
    protected function executeTestOrganizationUser($loginId, $joinedOrgFilterType, $antiOrganizations, bool $antiResult)
    {
        $this->init();

        $user = CustomTable::getEloquent('user')->getValueModel($loginId);
        /** @phpstan-ignore-next-line $user is generated class */
        $organizations = $user->getOrganizationIdsForQuery($joinedOrgFilterType);

        sort($organizations);
        sort($antiOrganizations);

        $result = true;
        if (count($organizations) != count($antiOrganizations)) {
            $result = false;
        } else {
            for ($i = 0; $i < count($organizations); $i++) {
                if ($organizations[$i] != $antiOrganizations[$i]) {
                    $result = false;
                    break;
                }
            }
        }

        $func = $antiResult ? 'assertTrue' : 'assertFalse';
        $this->{$func}(
            $result
        );
    }

    /**
     * @param int $id
     * @param string $joinedOrgFilterType
     * @param array<int> $antiOrganizations
     * @param bool $antiResult
     * @return void
     */
    protected function executeTestOrganizationOrg($id, $joinedOrgFilterType, $antiOrganizations, bool $antiResult)
    {
        $this->init();

        $organization = CustomTable::getEloquent('organization')->getValueModel($id);
        /** @phpstan-ignore-next-line $organization is generated class */
        $organizations = $organization->getOrganizationIdsForQuery($joinedOrgFilterType);

        sort($organizations);
        sort($antiOrganizations);

        $result = true;
        if (count($organizations) != count($antiOrganizations)) {
            $result = false;
        } else {
            for ($i = 0; $i < count($organizations); $i++) {
                if ($organizations[$i] != $antiOrganizations[$i]) {
                    $result = false;
                    break;
                }
            }
        }

        $func = $antiResult ? 'assertTrue' : 'assertFalse';
        $this->{$func}(
            $result
        );
    }

    /**
     * @param int $loginId
     * @param string $joinedOrgFilterType
     * @param bool $result
     * @return void
     */
    protected function executeTestRoleGroup($loginId, $joinedOrgFilterType, bool $result)
    {
        $this->init();
        $this->be(LoginUser::find($loginId));
        System::org_joined_type_role_group($joinedOrgFilterType);

        $func = $result ? 'assertTrue' : 'assertFalse';
        $this->{$func}(CustomTable::getEloquent('custom_value_edit')->hasPermission());
    }

    /**
     * @param int $loginId
     * @param string $joinedOrgFilterType
     * @param bool $result
     * @return void
     */
    protected function executeTestCustomValue($loginId, $joinedOrgFilterType, bool $result)
    {
        $this->init();
        $this->be(LoginUser::find($loginId));
        System::org_joined_type_role_group($joinedOrgFilterType);
        System::org_joined_type_custom_value($joinedOrgFilterType);

        $func = $result ? 'assertTrue' : 'assertFalse';
        $custom_value = CustomTable::getEloquent('custom_value_edit')->getValueModel()->find(51); // 51 --- created by dev user
        $this->{$func}(isset($custom_value));
    }
}
