<?php

namespace Exceedone\Exment\Tests\Feature;

use Exceedone\Exment\Model\CustomTable;
use Exceedone\Exment\Model\CustomColumn;
use Exceedone\Exment\Tests\TestDefine;
use Exceedone\Exment\Enums\FilterType;
use Exceedone\Exment\Enums\FilterOption;
use Exceedone\Exment\Enums\ColumnType;
use Exceedone\Exment\Enums\SystemColumn;
use Exceedone\Exment\Enums\ConditionTypeDetail;
use Exceedone\Exment\Tests\Browser\ExmentKitTestCase;

/**
 * Filter condition test. For use custom view filter, form priority, workflow, etc.
 */
class ApiFilterConditionTest extends ExmentKitTestCase
{
    /**
     * pre-excecute process before test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->login();
    }

    /**
     * @return void
     */
    public function testConditionApiColumnText()
    {
        $this->__testConditionApiColumn(ColumnType::TEXT, FilterType::DEFAULT);
    }

    /**
     * @return void
     */
    public function testConditionApiColumnTestarea()
    {
        $this->__testConditionApiColumn(ColumnType::TEXTAREA, FilterType::DEFAULT);
    }

    /**
     * @return void
     */
    public function testConditionApiColumnEditor()
    {
        $this->__testConditionApiColumn(ColumnType::EDITOR, FilterType::DEFAULT);
    }

    /**
     * @return void
     */
    public function testConditionApiColumnUrl()
    {
        $this->__testConditionApiColumn(ColumnType::URL, FilterType::DEFAULT);
    }

    /**
     * @return void
     */
    public function testConditionApiColumnEmail()
    {
        $this->__testConditionApiColumn(ColumnType::EMAIL, FilterType::DEFAULT);
    }

    /**
     * @return void
     */
    public function testConditionApiColumnInteger()
    {
        $this->__testConditionApiColumn(ColumnType::INTEGER, FilterType::NUMBER);
    }

    /**
     * @return void
     */
    public function testConditionApiColumnDecimal()
    {
        $this->__testConditionApiColumn(ColumnType::DECIMAL, FilterType::NUMBER);
    }

    /**
     * @return void
     */
    public function testConditionApiColumnCurrency()
    {
        $this->__testConditionApiColumn(ColumnType::CURRENCY, FilterType::NUMBER);
    }

    /**
     * @return void
     */
    public function testConditionApiColumnDate()
    {
        $this->__testConditionApiColumn(ColumnType::DATE, FilterType::DAY);
    }

    /**
     * @return void
     */
    public function testConditionApiColumnTime()
    {
        $this->__testConditionApiColumn(ColumnType::TIME, FilterType::DEFAULT);
    }

    /**
     * @return void
     */
    public function testConditionApiColumnDateTime()
    {
        $this->__testConditionApiColumn(ColumnType::DATETIME, FilterType::DAY);
    }

    /**
     * @return void
     */
    public function testConditionApiColumnSelect()
    {
        $this->__testConditionApiColumn(ColumnType::SELECT, FilterType::SELECT);
    }

    /**
     * @return void
     */
    public function testConditionApiColumnSelectValtext()
    {
        $this->__testConditionApiColumn(ColumnType::SELECT_VALTEXT, FilterType::SELECT);
    }

    /**
     * @return void
     */
    public function testConditionApiColumnSelectTable()
    {
        $this->__testConditionApiColumn(ColumnType::SELECT_TABLE, FilterType::SELECT);
    }

    /**
     * @return void
     */
    public function testConditionApiColumnYesNo()
    {
        $this->__testConditionApiColumn(ColumnType::YESNO, FilterType::YESNO);
    }

    /**
     * @return void
     */
    public function testConditionApiColumnBoolean()
    {
        $this->__testConditionApiColumn(ColumnType::BOOLEAN, FilterType::YESNO);
    }

    /**
     * @return void
     */
    public function testConditionApiColumnAutoNumber()
    {
        $this->__testConditionApiColumn(ColumnType::AUTO_NUMBER, FilterType::DEFAULT);
    }

    /**
     * @return void
     */
    public function testConditionApiColumnImage()
    {
        $this->__testConditionApiColumn(ColumnType::IMAGE, FilterType::FILE);
    }

    /**
     * @return void
     */
    public function testConditionApiColumnFile()
    {
        $this->__testConditionApiColumn(ColumnType::FILE, FilterType::FILE);
    }

    /**
     * @return void
     */
    public function testConditionApiColumnUser()
    {
        $this->__testConditionApiColumn(ColumnType::USER, FilterType::USER);
    }

    /**
     * @return void
     */
    public function testConditionApiColumnOrganization()
    {
        $this->__testConditionApiColumn(ColumnType::ORGANIZATION, FilterType::SELECT);
    }

    // System Column ----------------------------------------------------
    /**
     * @return void
     */
    public function testConditionApiSystemId()
    {
        $this->__testConditionApiSystem(SystemColumn::ID, FilterType::DEFAULT);
    }

    /**
     * @return void
     */
    public function testConditionApiSystemSuuid()
    {
        $this->__testConditionApiSystem(SystemColumn::SUUID, FilterType::DEFAULT);
    }

    /**
     * @return void
     */
    public function testConditionApiSystemCreatedAt()
    {
        $this->__testConditionApiSystem(SystemColumn::CREATED_AT, FilterType::DAY);
    }

    /**
     * @return void
     */
    public function testConditionApiSystemUpdatedAt()
    {
        $this->__testConditionApiSystem(SystemColumn::UPDATED_AT, FilterType::DAY);
    }

    /**
     * @return void
     */
    public function testConditionApiSystemCreateUser()
    {
        $this->__testConditionApiSystem(SystemColumn::CREATED_USER, FilterType::USER);
    }

    /**
     * @return void
     */
    public function testConditionApiSystemUpdateUser()
    {
        $this->__testConditionApiSystem(SystemColumn::UPDATED_USER, FilterType::USER);
    }


    // ConditionDetail ----------------------------------------------------
    /**
     * @return void
     */
    public function testConditionApiConditionUser()
    {
        $this->__testConditionApiConditionDetail(ConditionTypeDetail::USER, FilterType::CONDITION);
    }

    /**
     * @return void
     */
    public function testConditionApiConditionOrganization()
    {
        $this->__testConditionApiConditionDetail(ConditionTypeDetail::ORGANIZATION, FilterType::CONDITION);
    }

    /**
     * @return void
     */
    public function testConditionApiConditionRole()
    {
        $this->__testConditionApiConditionDetail(ConditionTypeDetail::ROLE, FilterType::CONDITION);
    }

    /**
     * @return void
     */
    public function testConditionApiConditionForm()
    {
        $this->__testConditionApiConditionDetail(ConditionTypeDetail::FORM, FilterType::CONDITION);
    }



    // Workflow ----------------------------------------------------
    /**
     * @return void
     */
    public function testConditionApiWorkflowStatus()
    {
        $this->__testConditionApiWorkflow('workflow_status', FilterType::WORKFLOW);
    }

    /**
     * @return void
     */
    public function testConditionApiWorkflowWorkUser()
    {
        $this->__testConditionApiWorkflow('workflow_work_users', FilterType::WORKFLOW_WORK_USER);
    }




    /**
     * Test condition api result.
     * This condtion api returns select options, ex {'id': 1, 'name': 'eq'}
     *
     * @param string $column_name
     * @param string $filterType
     * @param string|null $table_name
     * @return void
     */
    protected function __testConditionApiColumn(string $column_name, string $filterType, ?string $table_name = null)
    {
        if (!$table_name) {
            $table_name = TestDefine::TESTDATA_TABLE_NAME_ALL_COLUMNS;
        }

        $custom_table = CustomTable::getEloquent($table_name);
        $custom_column = CustomColumn::getEloquent($column_name, $custom_table);

        $url = admin_urls_query('view', $custom_table->table_name, 'filter-condition', [
            'q' => $custom_column->id,
            'table_id' => $custom_table->id,
        ]);

        $this->checkTestResult($url, $filterType);
    }

    /**
     * Test condition api result for system
     * This condition api returns select options, ex {'id': 1, 'name': 'eq'}
     *
     * @param string $system_column_name
     * @param string $filterType
     * @param string|null $table_name
     * @return void
     */
    protected function __testConditionApiSystem(string $system_column_name, string $filterType, ?string $table_name = null)
    {
        if (!$table_name) {
            $table_name = TestDefine::TESTDATA_TABLE_NAME_ALL_COLUMNS;
        }

        $custom_table = CustomTable::getEloquent($table_name);
        $syetem_column = SystemColumn::getOption(['name' => $system_column_name]);

        $url = admin_urls_query('view', $custom_table->table_name, 'filter-condition', [
            'q' => $syetem_column['name'],
            'table_id' => $custom_table->id,
        ]);

        $this->checkTestResult($url, $filterType);
    }

    /**
     * Test condition api result for condition detail
     * This condition api returns select options, ex {'id': 1, 'name': 'eq'}
     *
     * @param string $condition_type_detail
     * @param string $filterType
     * @param string|null $table_name
     * @return void
     */
    protected function __testConditionApiConditionDetail(string $condition_type_detail, string $filterType, ?string $table_name = null)
    {
        if (!$table_name) {
            $table_name = TestDefine::TESTDATA_TABLE_NAME_ALL_COLUMNS;
        }

        $custom_table = CustomTable::getEloquent($table_name);

        $url = admin_urls_query('view', $custom_table->table_name, 'filter-condition', [
            'q' => ConditionTypeDetail::getEnum($condition_type_detail)->upperKey(),
            'table_id' => $custom_table->id,
        ]);

        $this->checkTestResult($url, $filterType);
    }

    /**
     * Test condition api result for workflow
     * This condition api returns select options, ex {'id': 1, 'name': 'eq'}
     *
     * @param string $type
     * @param string $filterType
     * @param string|null $table_name
     * @return void
     */
    protected function __testConditionApiWorkflow(string $type, string $filterType, ?string $table_name = null)
    {
        if (!$table_name) {
            $table_name = TestDefine::TESTDATA_TABLE_NAME_EDIT;
        }
        $custom_table = CustomTable::getEloquent($table_name);

        $url = admin_urls_query('view', $custom_table->table_name, 'filter-condition', [
            'q' => $type,
            'table_id' => $custom_table->id,
        ]);

        $this->checkTestResult($url, $filterType);
    }


    /**
     * @param string $url
     * @param string $filterType
     * @return void
     */
    protected function checkTestResult(string $url, string $filterType)
    {
        $this->get($url);

        $response = $this->response->getContent();
        $this->assertTrue(is_json($response), "response is not json. response is $response");

        $json = collect(json_decode_ex($response, true))->pluck('text', 'id')->toArray();

        $expectOptions = array_get(FilterOption::FILTER_OPTIONS(), $filterType, []);
        $expectOptions = collect($expectOptions)->map(function ($arr) {
            $arr['name'] = exmtrans("custom_view.filter_condition_options.{$arr['name']}");
            return $arr;
        })->pluck('name', 'id')->toArray();


        $this->assertTrue(\Exment::isContains2Array($expectOptions, $json) && \Exment::isContains2Array($json, $expectOptions), "expects array is " . json_encode($expectOptions) . ", but result array is " . json_encode($json));
    }
}
