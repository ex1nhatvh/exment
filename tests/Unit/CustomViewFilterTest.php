<?php

namespace Exceedone\Exment\Tests\Unit;

use Exceedone\Exment\Tests\DatabaseTransactions;
use Exceedone\Exment\Enums\ConditionType;
use Exceedone\Exment\Enums\FilterOption;
use Exceedone\Exment\Model\CustomTable;
use Exceedone\Exment\Model\CustomValue;
use Exceedone\Exment\Tests\TestDefine;

class CustomViewFilterTest extends UnitTestBase
{
    use CustomViewTrait;
    use DatabaseTransactions;

    /**
     * FilterOption = EQ
     * @return void
     */
    public function testFuncFilterEq()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'text',
            'filter_condition' => FilterOption::EQ,
            'filter_value_text' => 'text_2'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return array_get($data, 'value.text') == $filter_settings[0]['filter_value_text'];
        });
    }

    /**
     * FilterOption = NE
     * @return void
     */
    public function testFuncFilterNe()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'yesno',
            'filter_condition' => FilterOption::NE,
            'filter_value_text' => 1
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return array_get($data, 'value.yesno') != $filter_settings[0]['filter_value_text'];
        });
    }

    /**
     * FilterOption = NE
     * @return void
     */
    public function testFuncFilterNotNull()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'text',
            'filter_condition' => FilterOption::NOT_NULL,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return array_get($data, 'value.text') !== null;
        });
    }

    /**
     * FilterOption = NULL
     *
     * @return void
     */
    public function testFuncFilterNull()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'text',
            'filter_condition' => FilterOption::NULL,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return array_get($data, 'value.text') === null;
        });
    }

    /**
     * FilterOption = LIKE
     *
     * @return void
     */
    public function testFuncFilterLike()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'text',
            'filter_condition' => FilterOption::LIKE,
            'filter_value_text' => 'text_1'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return strpos(array_get($data, 'value.text'), $filter_settings[0]['filter_value_text']) === 0;
        });
    }

    /**
     * FilterOption = NOT LIKE
     *
     * @return void
     */
    public function testFuncFilterNotLike()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'text',
            'filter_condition' => FilterOption::NOT_LIKE,
            'filter_value_text' => 'text_1'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return !is_null(array_get($data, 'value.text')) && strpos(array_get($data, 'value.text'), $filter_settings[0]['filter_value_text']) !== 0;
        });
    }

    /**
     * FilterOption = DAY
     *
     * @return void
     */
    public function testFuncFilterDayOn()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_ON,
            'filter_value_text' => '2021-01-01'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return array_get($data, 'value.date') == $filter_settings[0]['filter_value_text'];
        });
    }

    /**
     * FilterOption >= DAY
     *
     * @return void
     */
    public function testFuncFilterDayOnOrAfter()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_ON_OR_AFTER,
            'filter_value_text' => '2021-01-01'
        ]];

        $base_date = \Carbon\Carbon::parse($filter_settings[0]['filter_value_text']);
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) use ($base_date) {
            $date = array_get($data, 'value.date');
            if (is_null($date)) {
                return false;
            }
            $date = \Carbon\Carbon::parse($date);
            return $date >= $base_date;
        });
    }

    /**
     * FilterOption <= DAY
     *
     * @return void
     */
    public function testFuncFilterDayOnOrBefore()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_ON_OR_BEFORE,
            'filter_value_text' => '2021-01-01'
        ]];

        $base_date = \Carbon\Carbon::parse($filter_settings[0]['filter_value_text']);
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) use ($base_date) {
            $date = array_get($data, 'value.date');
            if (is_null($date)) {
                return false;
            }
            $date = \Carbon\Carbon::parse($date);
            return $date <= $base_date;
        });
    }

    /**
     * FilterOption = DAY NOT NULL
     *
     * @return void
     */
    public function testFuncFilterDayNotNull()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_NOT_NULL,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return array_get($data, 'value.date') !== null;
        });
    }

    /**
     * FilterOption = DAY NULL
     *
     * @return void
     */
    public function testFuncFilterDayNull()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_NULL,
        ]];

        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return array_get($data, 'value.date') === null;
        });
    }

    /**
     * FilterOption = DAY TODAY
     *
     * @return void
     */
    public function testFuncFilterDayToday()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_TODAY,
        ]];

        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $date = array_get($data, 'value.date');
            if (is_null($date)) {
                return false;
            }
            $target_value = \Carbon\Carbon::now()->format('Y-m-d');
            return $date === $target_value;
        });
    }

    /**
     * FilterOption = DAY TODAY OR AFTER
     *
     * @return void
     */
    public function testFuncFilterDayTodayOrAfter()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_TODAY_OR_AFTER,
        ]];

        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $date = array_get($data, 'value.date');
            if (is_null($date)) {
                return false;
            }
            $target_value = \Carbon\Carbon::now()->format('Y-m-d');
            return $date >= $target_value;
        });
    }

    /**
     * FilterOption = DAY TODAY OR BEFORE
     *
     * @return void
     */
    public function testFuncFilterDayTodayOrBefore()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_TODAY_OR_BEFORE,
        ]];

        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $date = array_get($data, 'value.date');
            if (is_null($date)) {
                return false;
            }
            $target_value = \Carbon\Carbon::now()->format('Y-m-d');
            return $date <= $target_value;
        });
    }

    /**
     * FilterOption = DAY YESTERDAY
     *
     * @return void
     */
    public function testFuncFilterDayYesterday()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_YESTERDAY,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $date = array_get($data, 'value.date');
            if (is_null($date)) {
                return false;
            }
            $target_value = \Carbon\Carbon::yesterday()->format('Y-m-d');
            return $date === $target_value;
        });
    }

    /**
     * FilterOption = DAY TOMORROW
     *
     * @return void
     */
    public function testFuncFilterDayTomorrow()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_TOMORROW,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $date = array_get($data, 'value.date');
            if (is_null($date)) {
                return false;
            }
            $target_value = \Carbon\Carbon::tomorrow()->format('Y-m-d');
            return $date === $target_value;
        });
    }

    /**
     * FilterOption = DAY THIS MONTH
     *
     * @return void
     */
    public function testFuncFilterDayThisMonth()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_THIS_MONTH,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $date = array_get($data, 'value.date');
            if (is_null($date)) {
                return false;
            }
            $date = \Carbon\Carbon::parse($date);
            $today = \Carbon\Carbon::today();
            return $today->format('Y-m') == $date->format('Y-m');
        });
    }

    /**
     * FilterOption = DAY LAST MONTH
     *
     * @return void
     */
    public function testFuncFilterDayLastMonth()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_LAST_MONTH,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $date = array_get($data, 'value.date');
            if (is_null($date)) {
                return false;
            }
            $date = \Carbon\Carbon::parse($date);
            $today = \Carbon\Carbon::today();
            return $today->firstOfMonth()->subMonthNoOverflow()->format('Y-m') == $date->format('Y-m');
        });
    }

    /**
     * FilterOption = DAY NEXT MONTH
     *
     * @return void
     */
    public function testFuncFilterDayNextMonth()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_NEXT_MONTH,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $date = array_get($data, 'value.date');
            if (is_null($date)) {
                return false;
            }
            $date = \Carbon\Carbon::parse($date);
            $today = \Carbon\Carbon::today();
            return $today->addMonthsNoOverflow(1)->format('Y-m') == $date->format('Y-m');
        });
    }

    /**
     * FilterOption = DAY THIS YEAR
     *
     * @return void
     */
    public function testFuncFilterDayThisYear()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_THIS_YEAR,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $date = array_get($data, 'value.date');
            if (is_null($date)) {
                return false;
            }
            $date = \Carbon\Carbon::parse($date);
            $today = \Carbon\Carbon::today();
            return $today->format('Y') == $date->format('Y');
        });
    }

    /**
     * FilterOption = DAY LAST YEAR
     *
     * @return void
     */
    public function testFuncFilterDayLastYear()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_LAST_YEAR,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $date = array_get($data, 'value.date');
            if (is_null($date)) {
                return false;
            }
            $date = \Carbon\Carbon::parse($date);
            $today = \Carbon\Carbon::today();
            return $today->subYearNoOverflow()->format('Y') == $date->format('Y');
        });
    }

    /**
     * FilterOption = DAY NEXT YEAR
     *
     * @return void
     */
    public function testFuncFilterDayNextYear()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_NEXT_YEAR,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $date = array_get($data, 'value.date');
            if (is_null($date)) {
                return false;
            }
            $date = \Carbon\Carbon::parse($date);
            $today = \Carbon\Carbon::today();
            return $today->addYearNoOverflow()->format('Y') == $date->format('Y');
        });
    }

    /**
     * FilterOption = DAY LAST X DAY OR AFTER
     *
     * @return void
     */
    public function testFuncFilterDayLastXDayOrAfter()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_LAST_X_DAY_OR_AFTER,
            'filter_value_text' => 3
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $date = array_get($data, 'value.date');
            if (is_null($date)) {
                return false;
            }
            $date = \Carbon\Carbon::parse($date);
            $diff = \Carbon\Carbon::today()->diffInDays($date, false);
            return $diff >= -3;
        });
    }

    /**
     * FilterOption = DAY LAST X DAY OR BEFORE
     *
     * @return void
     */
    public function testFuncFilterDayLastXDayOrBefore()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_LAST_X_DAY_OR_BEFORE,
            'filter_value_text' => 3
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $date = array_get($data, 'value.date');
            if (is_null($date)) {
                return false;
            }
            $date = \Carbon\Carbon::parse($date);
            $diff = \Carbon\Carbon::today()->diffInDays($date, false);
            return $diff <= -3;
        });
    }

    /**
     * FilterOption = DAY NEXT X DAY OR AFTER
     *
     * @return void
     */
    public function testFuncFilterDayNextXDayOrAfter()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_NEXT_X_DAY_OR_AFTER,
            'filter_value_text' => 3
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $date = array_get($data, 'value.date');
            if (is_null($date)) {
                return false;
            }
            $date = \Carbon\Carbon::parse($date);
            $diff = \Carbon\Carbon::today()->diffInDays($date, false);
            return $diff >= 3;
        });
    }

    /**
     * FilterOption = DAY NEXT X DAY OR BEFORE
     *
     * @return void
     */
    public function testFuncFilterDayNextXDayOrBefore()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_NEXT_X_DAY_OR_BEFORE,
            'filter_value_text' => 3
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $date = array_get($data, 'value.date');
            if (is_null($date)) {
                return false;
            }
            $date = \Carbon\Carbon::parse($date);
            $diff = \Carbon\Carbon::today()->diffInDays($date, false);
            return $diff <= 3;
        });
    }

    /**
     * FilterOption = Time
     *
     * @return void
     */
    public function testFuncFilterTimeEq()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'time',
            'filter_condition' => FilterOption::EQ,
            'filter_value_text' => '02:02:02'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $time = array_get($data, 'value.time');
            if (is_null($time)) {
                return false;
            }
            return $time == $filter_settings[0]['filter_value_text'];
        });
    }

    /**
     * FilterOption <> Time
     *
     * @return void
     */
    public function testFuncFilterTimeNe()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'time',
            'filter_condition' => FilterOption::NE,
            'filter_value_text' => '02:02:02'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $time = array_get($data, 'value.time');
            if (is_null($time)) {
                return false;
            }
            return $time !== $filter_settings[0]['filter_value_text'];
        });
    }

    /**
     * FilterOption = DateTime
     *
     * @return void
     */
    public function testFuncFilterDateTimeOn()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'datetime',
            'filter_condition' => FilterOption::DAY_ON,
            'filter_value_text' => \Carbon\Carbon::today()->format('Y-m-d')
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $date = array_get($data, 'value.datetime');
            if (is_null($date)) {
                return false;
            }
            $date = \Carbon\Carbon::parse($date);
            return $date->format('Y-m-d') == $filter_settings[0]['filter_value_text'];
        });
    }

    /**
     * FilterOption >= DateTime
     *
     * @return void
     */
    public function testFuncFilterDateTimeOnOrAfter()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'datetime',
            'filter_condition' => FilterOption::DAY_ON_OR_AFTER,
            'filter_value_text' => \Carbon\Carbon::today()->format('Y-m-d')
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $date = array_get($data, 'value.datetime');
            if (is_null($date)) {
                return false;
            }
            $date = \Carbon\Carbon::parse($date);
            $date = \Carbon\Carbon::create($date->year, $date->month, $date->day);

            $diff = \Carbon\Carbon::today()->diffInDays($date, false);
            return $diff >= 0;
        });
    }

    /**
     * FilterOption <= DateTime
     *
     * @return void
     */
    public function testFuncFilterDateTimeOnOrBefore()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'datetime',
            'filter_condition' => FilterOption::DAY_ON_OR_BEFORE,
            'filter_value_text' => \Carbon\Carbon::today()->format('Y-m-d')
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $date = array_get($data, 'value.datetime');
            if (is_null($date)) {
                return false;
            }
            $date = \Carbon\Carbon::parse($date);
            $diff = \Carbon\Carbon::today()->diffInDays($date, false);
            return $diff <= 0;
        });
    }

    /**
     * FilterOption = USER_EQ
     *
     * @return void
     */
    public function testFuncFilterUserEq()
    {
        $this->init();

        $user_id = TestDefine::TESTDATA_USER_LOGINID_DEV1_USERC;
        $filter_settings = [[
            'column_name' => 'user',
            'filter_condition' => FilterOption::USER_EQ,
            'filter_value_text' => $user_id
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) use ($user_id) {
            $user = array_get($data, 'value.user');
            if (is_null($user)) {
                return false;
            }
            return isMatchString($user, $user_id);
        });
    }

    /**
     * FilterOption = USER_NE
     *
     * @return void
     */
    public function testFuncFilterUserNe()
    {
        $this->init();

        $user_id = TestDefine::TESTDATA_USER_LOGINID_ADMIN;
        $filter_settings = [[
            'column_name' => 'user',
            'filter_condition' => FilterOption::USER_NE,
            'filter_value_text' => $user_id
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) use ($user_id) {
            $user = array_get($data, 'value.user');
            if (is_null($user)) {
                return false;
            }
            return !isMatchString($user, $user_id);
        });
    }

    /**
     * FilterOption = USER_NOT_NULL
     *
     * @return void
     */
    public function testFuncFilterUserNotNull()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'user',
            'filter_condition' => FilterOption::USER_NOT_NULL,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return array_get($data, 'value.user') !== null;
        });
    }

    /**
     * FilterOption = USER_NULL
     *
     * @return void
     */
    public function testFuncFilterUserNull()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'user',
            'filter_condition' => FilterOption::USER_NULL,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return array_get($data, 'value.user') === null;
        });
    }

    /**
     * FilterOption = USER_EQ_USER
     *
     * @return void
     */
    public function testFuncFilterLoginUser()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'user',
            'filter_condition' => FilterOption::USER_EQ_USER,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $user_id = \Exment::getUserId();
            return array_get($data, 'value.user') == $user_id;
        }, ['login_user_id' => TestDefine::TESTDATA_USER_LOGINID_DEV1_USERC]);
    }

    /**
     * FilterOption = USER_NE_USER
     *
     * @return void
     */
    public function testFuncFilterNotLoginUser()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'user',
            'filter_condition' => FilterOption::USER_NE_USER,
        ]];

        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $user = array_get($data, 'value.user');
            if (is_null($user)) {
                return false;
            }

            $user_id = \Exment::getUserId();
            return !isMatchString($user, $user_id);
        }, ['login_user_id' => TestDefine::TESTDATA_USER_LOGINID_DEV1_USERC]);
    }

    /**
     * FilterOption = SELECT_EXISTS(user/multiple)
     *
     * @return void
     */
    public function testFuncFilterUserEqMulti()
    {
        $this->init();

        $target_value = TestDefine::TESTDATA_USER_LOGINID_USER2;
        $filter_settings = [[
            'column_name' => 'user_multiple',
            'filter_condition' => FilterOption::SELECT_EXISTS,
            'filter_value_text' => $target_value
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return $this->checkArray($filter_settings[0]['filter_value_text'], array_get($data, 'value.user_multiple'));
        });
    }

    /**
     * @param mixed|null $expected
     * @param mixed|null $actual
     * @return bool
     */
    protected function checkArray($expected, $actual)
    {
        if (is_null($actual)) {
            return is_null($expected);
        } else {
            return in_array($expected, $actual);
        }
    }

    /**
     * FilterOption = NOT_NULL(user/multiple)
     *
     * @return void
     */
    public function testFuncFilterUserNotNullMulti()
    {
        //$this->skipTempTestIfTrue(true, 'Now multi and not null filter is bug.');

        $this->init();

        $filter_settings = [[
            'column_name' => 'user_multiple',
            'filter_condition' => FilterOption::NOT_NULL,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return !empty(array_get($data, 'value.user_multiple'));
        });
    }

    /**
     * FilterOption = SELECT_EXISTS(organization)
     *
     * @return void
     */
    public function testFuncFilterOrganizationExists()
    {
        $this->init();

        $target_value = TestDefine::TESTDATA_ORGANIZATION_DEV;
        $filter_settings = [[
            'column_name' => 'organization',
            'filter_condition' => FilterOption::SELECT_EXISTS,
            'filter_value_text' => $target_value
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) use ($target_value) {
            return array_get($data, 'value.organization') == $target_value;
        });
    }

    /**
     * FilterOption = SELECT_NOT_EXISTS(organization/multiple)
     *
     * @return void
     */
    public function testFuncFilterOrganizationNotExists()
    {
        $this->init();

        $target_value = TestDefine::TESTDATA_ORGANIZATION_DEV;
        $filter_settings = [[
            'column_name' => 'organization_multiple',
            'filter_condition' => FilterOption::SELECT_NOT_EXISTS,
            'filter_value_text' => $target_value
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return !$this->checkArray($filter_settings[0]['filter_value_text'], array_get($data, 'value.organization_multiple'));
        });
    }

    /**
     * FilterOption = NULL(organization/multiple)
     *
     * @return void
     */
    public function testFuncFilterOrganizationNullMulti()
    {
        //$this->skipTempTestIfTrue(true, 'Now multi and not null filter is bug.');

        $this->init();

        $filter_settings = [[
            'column_name' => 'organization_multiple',
            'filter_condition' => FilterOption::NULL,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return empty(array_get($data, 'value.organization_multiple'));
        });
    }

    /**
     * FilterOption = NUMBER_GT
     *
     * @return void
     */
    public function testFuncFilterNumberGt()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'integer',
            'filter_condition' => FilterOption::NUMBER_GT,
            'filter_value_text' => 1000
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return array_get($data, 'value.integer') > $filter_settings[0]['filter_value_text'];
        });
    }

    /**
     * FilterOption = NUMBER_LT
     *
     * @return void
     */
    public function testFuncFilterNumberLt()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'integer',
            'filter_condition' => FilterOption::NUMBER_LT,
            'filter_value_text' => 1000
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $value = array_get($data, 'value.integer');
            if (is_null($value)) {
                return false;
            }
            return array_get($data, 'value.integer') < $filter_settings[0]['filter_value_text'];
        });
    }


    /**
     * FilterOption = NUMBER_GTE
     *
     * @return void
     */
    public function testFuncFilterNumberGte()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'integer',
            'filter_condition' => FilterOption::NUMBER_GTE,
            'filter_value_text' => 1000
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return array_get($data, 'value.integer') >= $filter_settings[0]['filter_value_text'];
        });
    }

    /**
     * FilterOption = NUMBER_LTE
     *
     * @return void
     */
    public function testFuncFilterNumberLte()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'integer',
            'filter_condition' => FilterOption::NUMBER_LTE,
            'filter_value_text' => 1000
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $value = array_get($data, 'value.integer');
            if (is_null($value)) {
                return false;
            }
            return array_get($data, 'value.integer') <= $filter_settings[0]['filter_value_text'];
        });
    }

    /**
     * FilterOption = NUMBER_GT
     *
     * @return void
     */
    public function testFuncFilterNumberGtDec()
    {
        $this->init();

        $target_value = 0.36;
        $filter_settings = [[
            'column_name' => 'decimal',
            'filter_condition' => FilterOption::NUMBER_GT,
            'filter_value_text' => "$target_value"
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) use ($target_value) {
            return array_get($data, 'value.decimal') > $target_value;
        });
    }

    /**
     * FilterOption = NUMBER_LT
     *
     * @return void
     */
    public function testFuncFilterNumberLtDec()
    {
        $this->init();

        $target_value = 0.36;
        $filter_settings = [[
            'column_name' => 'decimal',
            'filter_condition' => FilterOption::NUMBER_LT,
            'filter_value_text' => "$target_value"
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) use ($target_value) {
            $value = array_get($data, 'value.decimal');
            if (is_null($value)) {
                return false;
            }
            return array_get($data, 'value.decimal') < $target_value;
        });
    }


    /**
     * FilterOption = NUMBER_GTE
     *
     * @return void
     */
    public function testFuncFilterNumberGteDec()
    {
        $this->init();

        $target_value = 0.36;
        $filter_settings = [[
            'column_name' => 'decimal',
            'filter_condition' => FilterOption::NUMBER_GTE,
            'filter_value_text' => "$target_value"
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) use ($target_value) {
            return array_get($data, 'value.decimal') >= $target_value;
        });
    }

    /**
     * FilterOption = NUMBER_LTE
     *
     * @return void
     */
    public function testFuncFilterNumberLteDec()
    {
        $this->init();

        $target_value = 0.36;
        $filter_settings = [[
            'column_name' => 'decimal',
            'filter_condition' => FilterOption::NUMBER_LTE,
            'filter_value_text' => "$target_value"
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) use ($target_value) {
            $value = array_get($data, 'value.decimal');
            if (is_null($value)) {
                return false;
            }
            return array_get($data, 'value.decimal') <= $target_value;
        });
    }

    /**
     * FilterOption = SELECT_EXISTS
     *
     * @return void
     */
    public function testFuncFilterSelectExists()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'select',
            'filter_condition' => FilterOption::SELECT_EXISTS,
            'filter_value_text' => 'bar'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $value = array_get($data, 'value.select');
            if (is_null($value)) {
                return false;
            }
            return array_get($data, 'value.select') === $filter_settings[0]['filter_value_text'];
        });
    }

    /**
     * FilterOption = SELECT_NOT_EXISTS
     *
     * @return void
     */
    public function testFuncFilterSelectNotExists()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'select',
            'filter_condition' => FilterOption::SELECT_NOT_EXISTS,
            'filter_value_text' => 'bar'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $value = array_get($data, 'value.select');
            if (is_null($value)) {
                return false;
            }
            return array_get($data, 'value.select') !== $filter_settings[0]['filter_value_text'];
        });
    }

    /**
     * FilterOption = SELECT_EXISTS(select_valtext)
     *
     * @return void
     */
    public function testFuncFilterSelectExistsVal()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'select_valtext',
            'filter_condition' => FilterOption::SELECT_EXISTS,
            'filter_value_text' => 'bar'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $value = array_get($data, 'value.select_valtext');
            if (is_null($value)) {
                return false;
            }
            return array_get($data, 'value.select_valtext') === $filter_settings[0]['filter_value_text'];
        });
    }

    /**
     * FilterOption = SELECT_NOT_EXISTS(select_valtext)
     *
     * @return void
     */
    public function testFuncFilterSelectNotExistsVal()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'select_valtext',
            'filter_condition' => FilterOption::SELECT_NOT_EXISTS,
            'filter_value_text' => 'bar'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $value = array_get($data, 'value.select_valtext');
            if (is_null($value)) {
                return false;
            }
            return array_get($data, 'value.select_valtext') !== $filter_settings[0]['filter_value_text'];
        });
    }

    /**
     * FilterOption = SELECT_EXISTS(select_table)
     *
     * @return void
     */
    public function testFuncFilterSelectExistsTable()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'select_table',
            'filter_condition' => FilterOption::SELECT_EXISTS,
            'filter_value_text' => 2
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $value = array_get($data, 'value.select_table');
            if (is_null($value)) {
                return false;
            }
            return isMatchString(array_get($data, 'value.select_table'), $filter_settings[0]['filter_value_text']);
        });
    }

    /**
     * FilterOption = SELECT_NOT_EXISTS(select_table)
     *
     * @return void
     */
    public function testFuncFilterSelectNotExistsTable()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'select_table',
            'filter_condition' => FilterOption::SELECT_NOT_EXISTS,
            'filter_value_text' => 2
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $value = array_get($data, 'value.select_table');
            if (is_null($value)) {
                return false;
            }
            return !isMatchString(array_get($data, 'value.select_table'), $filter_settings[0]['filter_value_text']);
        });
    }

    /**
     * FilterOption = SELECT_EXISTS(multiple select)
     *
     * @return void
     */
    public function testFuncFilterSelectExistsMulti()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'select_multiple',
            'filter_condition' => FilterOption::SELECT_EXISTS,
            'filter_value_text' => 'bar'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return $this->checkArray($filter_settings[0]['filter_value_text'], array_get($data, 'value.select_multiple'));
        });
    }

    /**
     * FilterOption = SELECT_NOT_EXISTS(multiple select)
     *
     * @return void
     */
    public function testFuncFilterSelectNotExistsMulti()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'select_multiple',
            'filter_condition' => FilterOption::SELECT_NOT_EXISTS,
            'filter_value_text' => 'foo'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return !$this->checkArray($filter_settings[0]['filter_value_text'], array_get($data, 'value.select_multiple'));
        });
    }

    /**
     * FilterOption = SELECT_EXISTS(select_valtext/multiple select)
     *
     * @return void
     */
    public function testFuncFilterSelectExistsValMulti()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'select_valtext_multiple',
            'filter_condition' => FilterOption::SELECT_EXISTS,
            'filter_value_text' => 'bar'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return $this->checkArray($filter_settings[0]['filter_value_text'], array_get($data, 'value.select_valtext_multiple'));
        });
    }

    /**
     * FilterOption = SELECT_NOT_EXISTS(select_valtext/multiple select)
     *
     * @return void
     */
    public function testFuncFilterSelectNotExistsValMulti()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'select_valtext_multiple',
            'filter_condition' => FilterOption::SELECT_NOT_EXISTS,
            'filter_value_text' => 'baz'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return !$this->checkArray($filter_settings[0]['filter_value_text'], array_get($data, 'value.select_valtext_multiple'));
        });
    }

    /**
     * FilterOption = SELECT_EXISTS(select_table/multiple select)
     *
     * @return void
     */
    public function testFuncFilterSelectExistsTableMulti()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'select_table_multiple',
            'filter_condition' => FilterOption::SELECT_EXISTS,
            'filter_value_text' => 2
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return $this->checkArray($filter_settings[0]['filter_value_text'], array_get($data, 'value.select_table_multiple'));
        });
    }

    /**
     * FilterOption = SELECT_NOT_EXISTS(select_table/multiple select)
     *
     * @return void
     */
    public function testFuncFilterSelectNotExistsTableMulti()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'select_table_multiple',
            'filter_condition' => FilterOption::SELECT_NOT_EXISTS,
            'filter_value_text' => 4
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return !$this->checkArray($filter_settings[0]['filter_value_text'], array_get($data, 'value.select_table_multiple'));
        });
    }

    /**
     * FilterOption = like id
     *
     * @return void
     */
    public function testFuncFilterIdLike()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'id',
            'condition_type' => ConditionType::SYSTEM,
            'filter_condition' => FilterOption::LIKE,
            'filter_value_text' => 8
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return strpos(strval(array_get($data, 'id')), strval($filter_settings[0]['filter_value_text'])) === 0;
        });
    }

    /**
     * FilterOption = created_at
     *
     * @return void
     */
    public function testFuncFilterCreatedAtDayOn()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'created_at',
            'condition_type' => ConditionType::SYSTEM,
            'filter_condition' => FilterOption::DAY_ON,
            'filter_value_text' => \Carbon\Carbon::now()->format('Y-m-d')
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $date = \Carbon\Carbon::parse(array_get($data, 'created_at'));
            return $date->isSameDay(\Carbon\Carbon::today());
        });
    }

    /**
     * FilterOption = updated_at is today
     *
     * @return void
     */
    public function testFuncFilterUpdatedAtToday()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'updated_at',
            'condition_type' => ConditionType::SYSTEM,
            'filter_condition' => FilterOption::DAY_TODAY,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $date = \Carbon\Carbon::parse(array_get($data, 'updated_at'));
            return $date->isSameDay(\Carbon\Carbon::today());
        });
    }

    /**
     * FilterOption = created_user is login user
     *
     * @return void
     */
    public function testFuncFilterCreatedUserEqUser()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'created_user',
            'condition_type' => ConditionType::SYSTEM,
            'filter_condition' => FilterOption::USER_EQ_USER,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $user_id = \Exment::getUserId();
            return array_get($data, 'created_user_id') == $user_id;
        });
    }

    /**
     * FilterOption = updated_user is not target user
     *
     * @return void
     */
    public function testFuncFilterUpdatedUserNe()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'updated_user',
            'condition_type' => ConditionType::SYSTEM,
            'filter_condition' => FilterOption::NE,
            'filter_value_text' => TestDefine::TESTDATA_USER_LOGINID_DEV1_USERC
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return array_get($data, 'updated_user_id') !== TestDefine::TESTDATA_USER_LOGINID_DEV1_USERC;
        });
    }

    /**
     * FilterOption = EQ(parent_id/1:N relation)
     *
     * @return void
     */
    public function testFuncParentIdEq()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'parent_id',
            'condition_type' => ConditionType::PARENT_ID,
            'filter_condition' => FilterOption::EQ,
            'filter_value_text' => '2'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $parent_value = $data->getParentValue();
            return isset($parent_value) && $parent_value->id == $filter_settings[0]['filter_value_text'];
        }, ['target_table_name' => 'child_table']);
    }

    /**
     * FilterOption = NE(parent_id/1:N relation)
     *
     * @return void
     */
    public function testFuncParentIdNe()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'parent_id',
            'condition_type' => ConditionType::PARENT_ID,
            'filter_condition' => FilterOption::NE,
            'filter_value_text' => '2'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $parent_value = $data->getParentValue();
            return isset($parent_value) && $parent_value->id != $filter_settings[0]['filter_value_text'];
        }, ['target_table_name' => 'child_table']);
    }

    /**
     * FilterOption = EQ(workflow status)
     *
     * @return void
     */
    public function testFuncWorkflowStatusEq()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'workflow_status',
            'condition_type' => ConditionType::WORKFLOW,
            'filter_condition' => FilterOption::WORKFLOW_EQ_STATUS,
            'filter_value_text' => '7'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $workflow_status = array_get($data, 'workflow_status');
            if (!$workflow_status) {
                return false;
            }
            return $workflow_status->id == $filter_settings[0]['filter_value_text'];
        }, ['target_table_name' => 'custom_value_edit']);
    }

    /**
     * FilterOption = NE(workflow status)
     *
     * @return void
     */
    public function testFuncWorkflowStatusNe()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'workflow_status',
            'condition_type' => ConditionType::WORKFLOW,
            'filter_condition' => FilterOption::WORKFLOW_NE_STATUS,
            'filter_value_text' => 'start'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $workflow_status = array_get($data, 'workflow_status');
            return !is_null($workflow_status);
        }, ['target_table_name' => 'custom_value_edit']);
    }

    /**
     * FilterOption = EQ(workflow status/multiple_select)
     *
     * @return void
     */
    public function testFuncWorkflowStatusEqMulti()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'workflow_status',
            'condition_type' => ConditionType::WORKFLOW,
            'filter_condition' => FilterOption::WORKFLOW_EQ_STATUS,
            'filter_value_text' => '["start","7"]'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $workflow_status = array_get($data, 'workflow_status');
            if (empty($workflow_status)) {
                return true;
            }
            return $workflow_status->id == '7';
        }, ['target_table_name' => 'custom_value_edit']);
    }

    /**
     * FilterOption = NE(workflow status/multiple_select)
     *
     * @return void
     */
    public function testFuncWorkflowStatusNeMulti()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'workflow_status',
            'condition_type' => ConditionType::WORKFLOW,
            'filter_condition' => FilterOption::WORKFLOW_NE_STATUS,
            'filter_value_text' => '["start","7"]'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $workflow_status = array_get($data, 'workflow_status');
            if (empty($workflow_status)) {
                return false;
            }
            return $workflow_status->id != '7';
        }, ['target_table_name' => 'custom_value_edit']);
    }

    /**
     * FilterOption = workflow status join other condition and option
     *
     * @return void
     */
    public function testFuncWorkflowStatusAnd()
    {
        $this->init();

        $filter_settings = [];
        $filter_settings[] = [
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_TODAY_OR_AFTER,
        ];
        $filter_settings[] = [
            'column_name' => 'workflow_status',
            'condition_type' => ConditionType::WORKFLOW,
            'filter_condition' => FilterOption::WORKFLOW_EQ_STATUS,
            'filter_value_text' => '["start","7"]'
        ];
        $today = \Carbon\Carbon::today();
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) use ($today) {
            $date = array_get($data, 'value.date');
            if (is_null($date)) {
                return false;
            }
            $date = \Carbon\Carbon::parse($date);
            if ($date < $today) {
                return false;
            }
            $workflow_status = array_get($data, 'workflow_status');
            if (empty($workflow_status) || $workflow_status->id == '7') {
                return true;
            }
            return false;
        }, ['target_table_name' => 'custom_value_edit']);
    }

    /**
     * FilterOption = workflow status join other condition or option
     *
     * @return void
     */
    public function testFuncWorkflowStatusOr()
    {
        $this->init();

        $filter_settings = [];
        $filter_settings[] = [
            'column_name' => 'odd_even',
            'filter_condition' => FilterOption::EQ,
            'filter_value_text' => 'even'
        ];
        $filter_settings[] = [
            'column_name' => 'workflow_status',
            'condition_type' => ConditionType::WORKFLOW,
            'filter_condition' => FilterOption::WORKFLOW_NE_STATUS,
            'filter_value_text' => '["start","7"]'
        ];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $odd_even = array_get($data, 'value.odd_even');
            if ($odd_even == 'even') {
                return true;
            }
            $workflow_status = array_get($data, 'workflow_status');
            if (empty($workflow_status) || $workflow_status->id == '7') {
                return false;
            }
            return true;
        }, ['target_table_name' => 'custom_value_edit', 'condition_join' => 'or']);
    }

    /**
     * FilterOption = EQ(workflow user)
     *
     * @return void
     */
    public function testFuncWorkflowUser()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'workflow_work_users',
            'condition_type' => ConditionType::WORKFLOW,
            'filter_condition' => FilterOption::WORKFLOW_EQ_WORK_USER,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $workflow_work_users = array_get($data, 'workflow_work_users');

            foreach ($workflow_work_users as $workflow_work_user) {
                $users = array_get($workflow_work_user, 'users');
                if (isset($users)) {
                    foreach ($users as $user) {
                        if ($user->id != TestDefine::TESTDATA_USER_LOGINID_DEV_USERB) {
                            return false;
                        }
                    }
                } else {
                    if ($workflow_work_user->id != TestDefine::TESTDATA_USER_LOGINID_DEV_USERB) {
                        return false;
                    }
                }

                return true;
            }
        }, [
            'login_user_id' => TestDefine::TESTDATA_USER_LOGINID_DEV_USERB,
            'target_table_name' => 'custom_value_edit_all'
        ]);
    }

    /**
     * FilterOption = EQ(workflow user/target organization)
     *
     * @return void
     */
    public function testFuncWorkflowUserOrg()
    {
        $this->init();

        $filter_settings = [[
            'column_name' => 'workflow_work_users',
            'condition_type' => ConditionType::WORKFLOW,
            'filter_condition' => FilterOption::WORKFLOW_EQ_WORK_USER,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $workflow_work_users = array_get($data, 'workflow_work_users');
            foreach ($workflow_work_users as $workflow_work_user) {
                $users = array_get($workflow_work_user, 'users');
                if (isset($users)) {
                    foreach ($users as $user) {
                        if ($user->id != TestDefine::TESTDATA_USER_LOGINID_DEV_USERB) {
                            return false;
                        }
                    }
                } else {
                    if ($workflow_work_user->id != TestDefine::TESTDATA_USER_LOGINID_DEV_USERB) {
                        return false;
                    }
                }
                return true;
            }
        }, [
            'login_user_id' => TestDefine::TESTDATA_USER_LOGINID_DEV_USERB,
            'target_table_name' => 'custom_value_edit'
        ]);
    }

    /**
     * FilterOption = multiple filter condition (join AND)
     *
     * @return void
     */
    public function testFuncFilterMultipleAnd()
    {
        $this->init();

        $filter_settings = [];
        $filter_settings[] = [
            'column_name' => 'date',
            'filter_condition' => FilterOption::DAY_TODAY_OR_AFTER,
        ];
        $filter_settings[] = [
            'column_name' => 'integer',
            'filter_condition' => FilterOption::NUMBER_GT,
            'filter_value_text' => 100
        ];
        $filter_settings[] = [
            'column_name' => 'select_valtext_multiple',
            'filter_condition' => FilterOption::SELECT_EXISTS,
            'filter_value_text' => 'foo'
        ];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            return array_get($data, 'value.date') >= \Carbon\Carbon::now()->format('Y-m-d') &&
                array_get($data, 'value.integer') > 100 &&
                in_array('foo', array_get($data, 'value.select_valtext_multiple'));
        });
    }

    /**
     * FilterOption = multiple filter condition (join OR)
     *
     * @return void
     */
    public function testFuncFilterMultipleOr()
    {
        $this->init();

        $filter_settings = [];
        $filter_settings[] = [
            'column_name' => 'datetime',
            'filter_condition' => FilterOption::DAY_TODAY,
        ];
        $filter_settings[] = [
            'column_name' => 'boolean',
            'filter_condition' => FilterOption::EQ,
            'filter_value_text' => 'ng'
        ];
        $filter_settings[] = [
            'column_name' => 'currency',
            'filter_condition' => FilterOption::NUMBER_GT,
            'filter_value_text' => 70000
        ];

        $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $cnt = 0;

            $datetime = \Carbon\Carbon::parse(array_get($data, 'value.datetime'));
            if ($datetime->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d')) {
                $cnt++;
            }
            if (array_get($data, 'value.currency') > 70000) {
                $cnt++;
            }
            if (array_get($data, 'value.boolean') == 'ng') {
                $cnt++;
            }

            return $cnt > 0;
        }, ['condition_join' => 'or']);
    }

    /**
     * FilterOption = filter condition (reverse)
     *
     * @return void
     */
    public function testFuncFilterReverse()
    {
        $this->init();

        $filter_settings = [];
        $filter_settings[] = [
            'reference_table' => 'custom_value_view_all',
            'column_name' => 'index_text',
            'reference_column' => 'select_table',
            'filter_condition' => FilterOption::LIKE,
            'filter_value_text' => 'index_003'
        ];

        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $select_value = $data->getValue('select_table');
            if ($select_value instanceof CustomValue) {
                return !str_starts_with($select_value->getValue('index_text'), 'index_003');
            }
            return false;
        }, ['condition_reverse' => '1']);
    }

    /**
     * FilterOption = multiple filter condition (reverse)
     *
     * @return void
     */
    public function testFuncFilterMultipleReverse()
    {
        $this->init();

        $filter_settings = [];
        $filter_settings[] = [
            'column_name' => 'datetime',
            'filter_condition' => FilterOption::DAY_TODAY_OR_AFTER,
        ];
        $filter_settings[] = [
            'column_name' => 'boolean',
            'filter_condition' => FilterOption::EQ,
            'filter_value_text' => 'ng'
        ];
        $filter_settings[] = [
            'column_name' => 'currency',
            'filter_condition' => FilterOption::NUMBER_GT,
            'filter_value_text' => 70000
        ];

        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $cnt = 0;

            $datetime = \Carbon\Carbon::parse(array_get($data, 'value.datetime'));
            if ($datetime->format('Y-m-d') >= \Carbon\Carbon::now()->format('Y-m-d')) {
                $cnt++;
            }
            if (array_get($data, 'value.currency') > 70000) {
                $cnt++;
            }
            if (array_get($data, 'value.boolean') == 'ng') {
                $cnt++;
            }

            return $cnt === 0;
        }, ['condition_join' => 'or', 'condition_reverse' => '1']);
    }

    /**
     * FilterOption = SELECT_EXISTS(select unicode)
     *
     * @return void
     */
    public function testFuncFilterSelectUnicodeExists1()
    {
        $this->init();

        // Whther use unicode
        $searchArray = '["ドトール", "珈琲館"]';
        $isUseUnicode = \ExmentDB::isUseUnicodeMultipleColumn();

        $options = [
            'target_table_name' => TestDefine::TESTDATA_TABLE_NAME_UNICODE_DATA,
        ];
        $filter_settings = [[
            'column_name' => 'select',
            'filter_condition' => FilterOption::SELECT_EXISTS,
            'filter_value_text' => $isUseUnicode ? unicode_encode($searchArray) : $searchArray,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $actual = array_get($data, 'value.select');
            return $actual == 'ドトール' || $actual == '珈琲館';
        }, $options);
    }

    /**
     * FilterOption = SELECT_EXISTS(select unicode)
     *
     * @return void
     */
    public function testFuncFilterSelectUnicodeExistsOld1()
    {
        $this->init();

        $options = [
            'target_table_name' => TestDefine::TESTDATA_TABLE_NAME_UNICODE_DATA,
        ];
        $filter_settings = [[
            'column_name' => 'select',
            'filter_condition' => FilterOption::SELECT_EXISTS,
            'filter_value_text' => 'コメダ珈琲'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $actual = array_get($data, 'value.select');
            return $actual == 'コメダ珈琲';
        }, $options);
    }

    /**
     * FilterOption = SELECT_EXISTS(select unicode)
     *
     * @return void
     */
    public function testFuncFilterSelectUnicodeExistsOld2()
    {
        $this->init();

        // Whther use unicode
        $searchArray = '["コメダ珈琲", "スターバックス"]';
        $isUseUnicode = \ExmentDB::isUseUnicodeMultipleColumn();

        $options = [
            'target_table_name' => TestDefine::TESTDATA_TABLE_NAME_UNICODE_DATA,
        ];
        $filter_settings = [[
            'column_name' => 'select',
            'filter_condition' => FilterOption::SELECT_EXISTS,
            'filter_value_text' => $isUseUnicode ? unicode_encode($searchArray) : $searchArray,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $actual = array_get($data, 'value.select');
            return $actual == 'コメダ珈琲' || $actual == 'スターバックス';
        }, $options);
    }

    /**
     * FilterOption = SELECT_NOT_EXISTS(select unicode)
     *
     * @return void
     */
    public function testFuncFilterSelectUnicodeNotExists()
    {
        $this->init();

        // Whther use unicode
        $searchArray = '["コメダ珈琲", "スターバックス"]';
        $isUseUnicode = \ExmentDB::isUseUnicodeMultipleColumn();

        $options = [
            'target_table_name' => TestDefine::TESTDATA_TABLE_NAME_UNICODE_DATA,
        ];
        $filter_settings = [[
            'column_name' => 'select',
            'filter_condition' => FilterOption::SELECT_NOT_EXISTS,
            'filter_value_text' => $isUseUnicode ? unicode_encode($searchArray) : $searchArray,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $actual = array_get($data, 'value.select');
            return $actual != 'コメダ珈琲' && $actual != 'スターバックス';
        }, $options);
    }

    /**
     * FilterOption = SELECT_NOT_EXISTS(select unicode)
     *
     * @return void
     */
    public function testFuncFilterSelectUnicodeNotExistsOld1()
    {
        $this->init();

        $options = [
            'target_table_name' => TestDefine::TESTDATA_TABLE_NAME_UNICODE_DATA,
        ];
        $filter_settings = [[
            'column_name' => 'select',
            'filter_condition' => FilterOption::SELECT_NOT_EXISTS,
            'filter_value_text' => '上島珈琲店'
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $actual = array_get($data, 'value.select');
            return $actual != '上島珈琲店';
        }, $options);
    }

    /**
     * FilterOption = SELECT_NOT_EXISTS(select unicode)
     *
     * @return void
     */
    public function testFuncFilterSelectUnicodeNotExistsOld2()
    {
        $this->init();

        // Whther use unicode
        $searchArray = '["上島珈琲店", "珈琲館"]';
        $isUseUnicode = \ExmentDB::isUseUnicodeMultipleColumn();

        $options = [
            'target_table_name' => TestDefine::TESTDATA_TABLE_NAME_UNICODE_DATA,
        ];
        $filter_settings = [[
            'column_name' => 'select',
            'filter_condition' => FilterOption::SELECT_NOT_EXISTS,
            'filter_value_text' => $isUseUnicode ? unicode_encode($searchArray) : $searchArray,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $actual = array_get($data, 'value.select');
            return $actual != '上島珈琲店' && $actual != '珈琲館';
        }, $options);
    }

    /**
     * FilterOption = SELECT_EXISTS(multiple select unicode)
     *
     * @return void
     */
    public function testFuncFilterSelectUnicodeExistsMulti()
    {
        $this->init();

        $options = [
            'target_table_name' => TestDefine::TESTDATA_TABLE_NAME_UNICODE_DATA,
        ];

        // Whther use unicode
        $searchArray = '["イタリア", "中国"]';
        $isUseUnicode = \ExmentDB::isUseUnicodeMultipleColumn();

        $filter_settings = [[
            'column_name' => 'select_multiple',
            'filter_condition' => FilterOption::SELECT_EXISTS,
            'filter_value_text' => $isUseUnicode ? unicode_encode($searchArray) : $searchArray,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $actual = array_get($data, 'value.select_multiple');
            return in_array('イタリア', $actual) || in_array('中国', $actual);
        }, $options);
    }

    /**
     * FilterOption = SELECT_NOT_EXISTS(multiple select unicode)
     *
     * @return void
     */
    public function testFuncFilterSelectUnicodeNotExistsMulti()
    {
        $this->init();

        // Whther use unicode
        $searchArray = '["アメリカ", "日本"]';
        $isUseUnicode = \ExmentDB::isUseUnicodeMultipleColumn();

        $options = [
            'target_table_name' => TestDefine::TESTDATA_TABLE_NAME_UNICODE_DATA,
        ];
        $filter_settings = [[
            'column_name' => 'select_multiple',
            'filter_condition' => FilterOption::SELECT_NOT_EXISTS,
            'filter_value_text' => $isUseUnicode ? unicode_encode($searchArray) : $searchArray,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $actual = array_get($data, 'value.select_multiple');
            return !in_array('アメリカ', $actual) && !in_array('日本', $actual);
        }, $options);
    }

    /**
     * FilterOption = SELECT_EXISTS(multiple select_valtext unicode)
     *
     * @return void
     */
    public function testFuncFilterSelectUnicodeExistsVal()
    {
        $this->init();

        // Whther use unicode
        $searchArray = '["ろ", "と"]';
        $isUseUnicode = \ExmentDB::isUseUnicodeMultipleColumn();

        $options = [
            'target_table_name' => TestDefine::TESTDATA_TABLE_NAME_UNICODE_DATA,
        ];
        $filter_settings = [[
            'column_name' => 'select_valtext_multiple',
            'filter_condition' => FilterOption::SELECT_EXISTS,
            'filter_value_text' => $isUseUnicode ? unicode_encode($searchArray) : $searchArray,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $actual = array_get($data, 'value.select_valtext_multiple');
            return in_array('ろ', $actual) || in_array('と', $actual);
        }, $options);
    }

    /**
     * FilterOption = SELECT_NOT_EXISTS(multiple select_valtext unicode)
     *
     * @return void
     */
    public function testFuncFilterSelectUnicodeNotExistsVal()
    {
        $this->init();

        // Whther use unicode
        $searchArray = '["い", "ち"]';
        $isUseUnicode = \ExmentDB::isUseUnicodeMultipleColumn();

        $options = [
            'target_table_name' => TestDefine::TESTDATA_TABLE_NAME_UNICODE_DATA,
        ];
        $filter_settings = [[
            'column_name' => 'select_valtext_multiple',
            'filter_condition' => FilterOption::SELECT_NOT_EXISTS,
            'filter_value_text' => $isUseUnicode ? unicode_encode($searchArray) : $searchArray,
        ]];
        /** @phpstan-ignore-next-line Result of method Exceedone\Exment\Tests\Unit\CustomViewFilterTest::getColumnFilterData() (void) is used.   */
        $array = $this->getColumnFilterData($filter_settings, function ($data, $filter_settings) {
            $actual = array_get($data, 'value.select_valtext_multiple');
            return !in_array('い', $actual) && !in_array('ち', $actual);
        }, $options);
    }


    /**
     * @return void
     */
    protected function init()
    {
        $this->initAllTest();
    }

    /**
     * @param array<mixed> $filter_settings
     * @param \Closure $testCallback
     * @param array<mixed> $options
     * @return void
     */
    protected function getColumnFilterData(array $filter_settings, \Closure $testCallback, array $options = [])
    {
        $options = array_merge(
            [
                'login_user_id' => TestDefine::TESTDATA_USER_LOGINID_ADMIN,
                'filter_settings' => $filter_settings,
            ],
            $options
        );

        // get custom view data
        $data = $this->getCustomViewData($options);

        $this->__testFilter($data, $filter_settings, $testCallback, $options);
    }


    /**
     * @param \Illuminate\Support\Collection<int,string|mixed> $collection
     * @param array<mixed> $filter_settings
     * @param \Closure $testCallback
     * @param array<mixed> $options
     * @return void
     */
    protected function __testFilter(\Illuminate\Support\Collection $collection, array $filter_settings, \Closure $testCallback, array $options = [])
    {
        $options = array_merge(
            [
                'target_table_name' => TestDefine::TESTDATA_TABLE_NAME_ALL_COLUMNS_FORTEST,
            ],
            $options
        );

        // get filter matched count.
        foreach ($collection as $data) {
            $matchResult = $testCallback($data, $filter_settings);

            $this->assertTrue($matchResult, 'matchResult is false. Target id is ' . $data->id);
        }

        // check not getted values.
        $custom_table = CustomTable::getEloquent($options['target_table_name']);
        $notMatchedValues = $custom_table->getValueQuery()->whereNotIn('id', $collection->pluck('id')->toArray())->get();

        foreach ($notMatchedValues as $data) {
            $matchResult = $testCallback($data, $filter_settings);

            /** @var mixed $data */
            $this->assertTrue(!$matchResult, 'Expect matchResult is false, but matched. Target id is ' . $data->id);
        }
    }
}
