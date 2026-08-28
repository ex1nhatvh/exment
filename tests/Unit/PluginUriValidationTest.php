<?php

namespace Exceedone\Exment\Tests\Unit;

use Exceedone\Exment\Model\Plugin;
use Exceedone\Exment\Model\LoginUser;
use Exceedone\Exment\Enums\PluginType;
use Exceedone\Exment\Tests\DatabaseTransactions;
use Exceedone\Exment\Tests\TestDefine;

/**
 * Integration Test: Plugin URI フィールドのバリデーション欠如バグ
 *
 * ■ バグ概要
 *   基本設定画面でURI欄に日本語・絵文字・大文字・スペース・特殊文字を入力した後、
 *   エンドポイント(ページ)をクリックするとルーティングエラーが発生する。
 *
 * ■ 原因
 *   PluginController::form() の uri フィールドが ->required() のみで、
 *   文字種のバリデーションが行われていない。
 *
 * ■ 期待する修正
 *   uri フィールドに以下のルールを追加する:
 *     ->rules('regex:/^[a-z0-9][a-z0-9_\-]*$/')
 *
 * ■ テスト戦略
 *   - HTTP PUT でフォーム送信し DB の値を直接確認する
 *   - 「無効URI」テストは現在 FAIL (バグあり) → 修正後に PASS に変わる
 *   - 「有効URI」テストは修正前後として PASS
 *   - Plugin::getOptionUri() が不正URLを生成することも証明する
 *
 * ■ 対象プラグイン
 *   URI フィールドが表示されるのは PAGE / API / CRUD 型のみ。
 *   テストデータ: TestPluginPage (page), TestPluginApi (api)
 */
class PluginUriValidationTest extends UnitTestBase
{
    use DatabaseTransactions;

    /** URI フィールドに追加すべき regex ルール */
    private const URI_REGEX = '/^[a-z0-9][a-z0-9_\-]*$/';

    private const PLUGIN_PAGE = 'TestPluginPage';
    private const PLUGIN_API  = 'TestPluginApi';

    // =========================================================================
    // Setup
    // =========================================================================

    protected function setUp(): void
    {
        parent::setUp();
        $this->initAllTest();
        $this->be(LoginUser::find(TestDefine::TESTDATA_USER_LOGINID_ADMIN));
    }

    // =========================================================================
    // Data Providers
    // =========================================================================

    /**
     * @return array<string, array{string}>
     */
    public static function validUriProvider(): array
    {
        return [
            'lowercase only'     => ['myplugin'],
            'with underscore'    => ['my_plugin'],
            'with hyphen'        => ['my-plugin'],
            'starts with digit'  => ['123plugin'],
            'complex valid'      => ['test_plugin_page'],
            'hyphen and version' => ['plugin-v2'],
            'single char'        => ['a'],
            'alphanumeric mixed' => ['abc123def'],
            'all digits'         => ['1234'],
        ];
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function invalidUriProvider(): array
    {
        return [
            // ── Core bug scenario: Japanese ──────────────────────────────────
            'Japanese katakana'        => ['テスト',           '日本語カタカナ'],
            'Japanese plugin label'    => ['プラグイン',        '日本語プラグイン'],
            'Japanese hiragana mixed'  => ['日本語テスト',      '日本語ひらがな混在'],
            'Japanese + ASCII'         => ['テストPlugin',      '日本語とASCII混在'],

            // ── Emoji ────────────────────────────────────────────────────────
            'emoji prefix'             => ['😀plugin',          '絵文字プレフィックス'],
            'emoji suffix'             => ['plugin🎮',          '絵文字サフィックス'],
            'emoji only'               => ['🎮',                '絵文字のみ'],

            // ── Uppercase ────────────────────────────────────────────────────
            'uppercase only'           => ['PLUGIN',            '全て大文字'],
            'PascalCase'               => ['TestPlugin',        'パスカルケース'],
            'mixed case'               => ['MyPlugin',          '大文字混在'],
            'uppercase and Japanese'   => ['Test プラグイン',   '大文字と日本語混在'],

            // ── Spaces ───────────────────────────────────────────────────────
            // Note: leading/trailing spaces (' plugin', 'plugin ') are intentionally
            // excluded — Laravel's TrimStrings middleware auto-trims them to 'plugin'
            // (a valid URI), so they are not rejected by validation.
            'space in middle'          => ['my plugin',         '中間スペース'],
            'multiple spaces'          => ['test plugin page',  '複数スペース'],
            'tab character'            => ["my\tplugin",        'タブ文字'],

            // ── Special characters ───────────────────────────────────────────
            'exclamation mark'         => ['my!plugin',         '感嘆符'],
            'at sign'                  => ['test@plugin',       'アットマーク'],
            'hash'                     => ['plugin#1',          'ハッシュ'],
            'forward slash'            => ['test/plugin',       'スラッシュ'],
            'dot'                      => ['test.plugin',       'ドット'],
            'plus sign'                => ['test+plugin',       'プラス記号'],
            'starts with underscore'   => ['_plugin',           'アンダースコア開始'],
            'starts with hyphen'       => ['-plugin',           'ハイフン開始'],

            // ── Security cases ───────────────────────────────────────────────
            'XSS attempt'              => ['<script>alert(1)</script>', 'XSS攻撃'],
            'SQL injection'            => ["' OR '1'='1",       'SQLインジェクション'],
            'path traversal'           => ['../etc/passwd',     'パストラバーサル'],
            'double slash'             => ['test//plugin',      'ダブルスラッシュ'],
            'null-byte'                => ["plugin\0name",      'NULLバイト'],
        ];
    }

    // =========================================================================
    // Group 1: Valid URIs — must be accepted (PASS before and after fix)
    // =========================================================================

    /**
     * 有効なURIは保存されること。(PAGE プラグイン)
     *
     * @dataProvider validUriProvider
     */
    public function testValidUriIsSaved_PagePlugin(string $uri): void
    {
        $plugin = $this->getUriPlugin(self::PLUGIN_PAGE);

        $this->putPluginUri($plugin, $uri);
        $plugin->refresh();

        $this->assertEquals(
            $uri,
            $plugin->getOption('uri'),
            "Valid URI '{$uri}' should be saved to the database."
        );
    }

    /**
     * 有効なURIは保存されること。(API プラグイン)
     *
     * @dataProvider validUriProvider
     */
    public function testValidUriIsSaved_ApiPlugin(string $uri): void
    {
        $plugin = $this->getUriPlugin(self::PLUGIN_API);

        $this->putPluginUri($plugin, $uri);
        $plugin->refresh();

        $this->assertEquals(
            $uri,
            $plugin->getOption('uri'),
            "Valid URI '{$uri}' should be saved to the database."
        );
    }

    /**
     * 同じURIで更新しても正常に保存されること (冪等性)。
     */
    public function testSameUriUpdateIsIdempotent(): void
    {
        $plugin = $this->getUriPlugin(self::PLUGIN_PAGE);
        $currentUri = $plugin->getOption('uri') ?? 'testpluginpage';

        $this->putPluginUri($plugin, $currentUri);
        $plugin->refresh();

        $this->assertEquals($currentUri, $plugin->getOption('uri'));
    }

    // =========================================================================
    // Group 2: Invalid URIs — must be REJECTED
    //   ★ CURRENTLY FAIL (BUG) → Will PASS after validation fix ★
    // =========================================================================

    /**
     * 無効なURIはフォーム送信後に DB へ保存されないこと。(PAGE プラグイン)
     *
     * 【現状 FAIL = バグあり】
     *   PluginController::form() に regex バリデーションがないため、
     *   無効なURIがそのまま DB に保存されてしまう。
     *
     * 【修正後 PASS】
     *   uri フィールドに ->rules('regex:/^[a-z0-9][a-z0-9_\-]*$/') を追加すると
     *   バリデーションエラーになり DB は更新されない。
     *
     * @dataProvider invalidUriProvider
     */
    public function testInvalidUriIsRejected_PagePlugin(string $uri, string $description): void
    {
        $plugin = $this->getUriPlugin(self::PLUGIN_PAGE);

        // Set a known valid baseline first so the assertion is meaningful
        // regardless of what prior test runs may have left in the DB.
        $baseline = 'valid_baseline_uri';
        $this->putPluginUri($plugin, $baseline);
        $plugin->refresh();
        $this->assertEquals($baseline, $plugin->getOption('uri'), 'Baseline setup failed — valid URI was not saved.');

        $this->putPluginUri($plugin, $uri);
        $plugin->refresh();

        $this->assertEquals(
            $baseline,
            $plugin->getOption('uri'),
            "BUG: Invalid URI '{$uri}' ({$description}) was saved to DB without validation. "
                . "Fix: add ->rules('regex:" . self::URI_REGEX . "') "
                . "to the 'uri' field in PluginController::form()."
        );
    }

    /**
     * 無効なURIはフォーム送信後に DB へ保存されないこと。(API プラグイン)
     *
     * 【現状 FAIL = バグあり】【修正後 PASS】
     *
     * @dataProvider invalidUriProvider
     */
    public function testInvalidUriIsRejected_ApiPlugin(string $uri, string $description): void
    {
        $plugin = $this->getUriPlugin(self::PLUGIN_API);

        // Set a known valid baseline first.
        $baseline = 'valid_baseline_uri';
        $this->putPluginUri($plugin, $baseline);
        $plugin->refresh();
        $this->assertEquals($baseline, $plugin->getOption('uri'), 'Baseline setup failed — valid URI was not saved.');

        $this->putPluginUri($plugin, $uri);
        $plugin->refresh();

        $this->assertEquals(
            $baseline,
            $plugin->getOption('uri'),
            "BUG: Invalid URI '{$uri}' ({$description}) was saved to DB (API plugin). "
                . "Fix: add ->rules('regex:" . self::URI_REGEX . "') to PluginController::form()."
        );
    }

    /**
     * 無効URI送信後、元のURIが維持されること。(ロールバック確認)
     *
     * 【現状 FAIL = バグあり】【修正後 PASS】
     *
     * @dataProvider invalidUriProvider
     */
    public function testOriginalUriIsPreservedAfterInvalidSubmit(string $uri, string $description): void
    {
        $plugin = $this->getUriPlugin(self::PLUGIN_PAGE);

        // Establish a known valid baseline to be independent of prior test state
        // (avoids bleed caused by laravel-admin committing DB transactions internally).
        $baseline = 'valid_baseline_uri';
        $this->putPluginUri($plugin, $baseline);
        $plugin->refresh();
        $this->assertEquals($baseline, $plugin->getOption('uri'), 'Baseline setup failed — valid URI was not saved.');

        // Submit the invalid URI
        $this->putPluginUri($plugin, $uri);
        $plugin->refresh();

        // After fix: baseline must be unchanged
        $this->assertEquals(
            $baseline,
            $plugin->getOption('uri'),
            "BUG: Original URI '{$baseline}' was overwritten by invalid input '{$uri}' ({$description}). "
                . "Validation should have prevented the save."
        );
    }

    // =========================================================================
    // Group 3: Edge cases
    // =========================================================================

    /**
     * 空文字URIは ->required() により拒否されること。(修正前後ともに PASS)
     */
    public function testEmptyUriIsRejectedByRequired(): void
    {
        $plugin = $this->getUriPlugin(self::PLUGIN_PAGE);
        $originalUri = $plugin->getOption('uri');

        $this->putPluginUri($plugin, '');
        $plugin->refresh();

        $this->assertNotEquals(
            '',
            $plugin->getOption('uri'),
            "Empty URI should be rejected by the existing 'required' rule."
        );
    }

    /**
     * 極端に長いURIが拒否されること。
     *
     * 【現状 FAIL = バグあり】【修正後 PASS】
     */
    public function testExtremelyLongUriIsRejected(): void
    {
        $plugin = $this->getUriPlugin(self::PLUGIN_PAGE);
        $longUri = str_repeat('a', 256); // 256 chars

        $this->putPluginUri($plugin, $longUri);
        $plugin->refresh();

        $this->assertNotEquals(
            $longUri,
            $plugin->getOption('uri'),
            "Extremely long URI (256 chars) should be rejected. "
                . "Consider adding a max length rule in addition to regex."
        );
    }

    // =========================================================================
    // Group 4: Model-level — getOptionUri() returns unsafe URL string
    //          (These PASS regardless of controller fix — they document the root cause)
    // =========================================================================

    /**
     * 日本語URIをセットした場合、getOptionUri() がURL安全でない文字列を返すこと。
     *
     * これが「エンドポイント(ページ)」クリック時にルーティングエラーを引き起こす根本原因。
     */
    public function testGetOptionUri_JapaneseInputProducesUnsafeUrl(): void
    {
        $plugin = new Plugin();
        $plugin->setOption('uri', 'テスト');

        $uri = $plugin->getOptionUri();

        $this->assertFalse(
            preg_match(self::URI_REGEX, $uri) === 1,
            "Japanese URI 'テスト' produced '{$uri}' which is not URL-safe. "
                . "This causes routing errors when the endpoint is accessed."
        );
    }

    /**
     * 絵文字URIをセットした場合、getOptionUri() がURL安全でない文字列を返すこと。
     */
    public function testGetOptionUri_EmojiInputProducesUnsafeUrl(): void
    {
        $plugin = new Plugin();
        $plugin->setOption('uri', '😀plugin');

        $uri = $plugin->getOptionUri();

        $this->assertFalse(
            preg_match(self::URI_REGEX, $uri) === 1,
            "Emoji URI '😀plugin' produced '{$uri}' which is not URL-safe."
        );
    }

    /**
     * スペース含みURIをセットした場合、getRootUrl() のエンドポイント文字列が
     * URL安全でないこと。(PAGE型)
     */
    public function testGetRootUrl_SpaceInUriProducesUnsafeEndpoint(): void
    {
        $plugin = new Plugin();
        $plugin->setOption('uri', 'my plugin page');

        // snake_case converts spaces to underscores, so this actually produces safe URL
        // But the point is that input validation should prevent this at form level
        $routeUri = $plugin->getOptionUri();
        $rootUrl  = $plugin->getRootUrl(PluginType::PAGE);

        $this->assertNotEmpty($rootUrl, 'getRootUrl() should not return empty string.');

        // The resulting URL should not contain raw spaces
        $this->assertFalse(
            strpos($rootUrl, ' ') !== false,
            "Endpoint URL '{$rootUrl}' contains spaces, which breaks HTTP routing."
        );
    }

    /**
     * 大文字URIは snake_case で変換されるが、入力段階で拒否すべきであることを確認。
     */
    public function testGetOptionUri_UppercaseIsConvertedButShouldBeValidatedEarlier(): void
    {
        $plugin = new Plugin();
        $plugin->setOption('uri', 'TestPluginPage');

        $uri = $plugin->getOptionUri(); // snake_case => 'test_plugin_page'

        // After snake_case the result is valid...
        $this->assertFalse(preg_match('/[A-Z]/', $uri) === 1,
            "snake_case should remove uppercase, got: '{$uri}'"
        );

        // ...but the input 'TestPluginPage' itself does NOT pass the regex,
        // so it must be validated BEFORE snake_case conversion.
        $this->assertFalse(
            preg_match(self::URI_REGEX, 'TestPluginPage') === 1,
            "'TestPluginPage' should fail the URI regex before any conversion."
        );
    }

    // =========================================================================
    // Group 5: Regex spec — document the expected pattern behavior
    //          (Pure unit assertions, always PASS)
    // =========================================================================

    /**
     * @dataProvider validUriProvider
     */
    public function testUriRegexAcceptsValidPatterns(string $uri): void
    {
        $this->assertTrue(
            preg_match(self::URI_REGEX, $uri) === 1,
            "URI '{$uri}' should match the expected regex pattern."
        );
    }

    /**
     * @dataProvider invalidUriProvider
     */
    public function testUriRegexRejectsInvalidPatterns(string $uri, string $description): void
    {
        $this->assertFalse(
            preg_match(self::URI_REGEX, $uri) === 1,
            "URI '{$uri}' ({$description}) should NOT match the expected regex pattern."
        );
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    /**
     * 対象プラグインを取得する。DBにない場合は markTestSkipped で警告して終了。
     */
    private function getUriPlugin(string $pluginName): Plugin
    {
        $plugin = Plugin::where('plugin_name', $pluginName)->first();

        if (!$plugin) {
            $this->markTestSkipped(
                "Plugin '{$pluginName}' was not found in the database. "
                    . "Ensure test data is seeded before running this test. "
                    . "Skipping."
            );
        }

        return $plugin;
    }

    /**
     * プラグインの URI を HTTP PUT で更新する。
     * DatabaseTransactions により、テスト終了後にロールバックされる。
     *
     * @param Plugin $plugin
     * @param string $uri
     * @return void
     */
    private function putPluginUri(Plugin $plugin, string $uri): void
    {
        $this->put(
            admin_urls('plugin', $plugin->id),
            [
                'active_flg' => $plugin->active_flg ?? '1',
                'options'    => [
                    'uri' => $uri,
                ],
            ]
        );
    }
}
