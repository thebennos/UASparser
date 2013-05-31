<?php
/**
 * UASParser PHPUnit tests
 * @author Marcus Bointon https://github.com/Synchro
 */

require 'PHPUnit/Autoload.php';
require 'UAS/Parser.php';

class ParserTest extends PHPUnit_Framework_TestCase
{
    protected static $uasparser;
    protected static $cache_path;

    public function setUp() {
        self::$uasparser = new UAS\Parser;
        self::$cache_path = sys_get_temp_dir().'/uascache/';
    }

    public function testSetPath() {
        $this->assertTrue(self::$uasparser->SetCacheDir(self::$cache_path));
    }

    public function testPath() {
        self::$uasparser->SetCacheDir(self::$cache_path);
        $this->assertEquals(self::$uasparser->GetCacheDir(), realpath(self::$cache_path));
    }

    public function testExpires() {
        self::$uasparser->updateInterval = 99999;
        $this->assertEquals(self::$uasparser->updateInterval, 99999);
    }

    public function testUpdateDatabase() {
        self::$uasparser->SetCacheDir(self::$cache_path);
        $this->assertTrue(self::$uasparser->downloadData());
    }

    public function testCurrent() {
        self::$uasparser->SetCacheDir(self::$cache_path);
        $u = self::$uasparser->Parse();
        $this->assertTrue(is_array($u));
        $this->assertArrayHasKey('typ', $u);
        $this->assertArrayHasKey('ua_family', $u);
        $this->assertArrayHasKey('ua_name', $u);
        $this->assertArrayHasKey('ua_version', $u);
        $this->assertArrayHasKey('ua_url', $u);
        $this->assertArrayHasKey('ua_company', $u);
        $this->assertArrayHasKey('ua_company_url', $u);
        $this->assertArrayHasKey('ua_icon', $u);
        $this->assertArrayHasKey('ua_info_url', $u);
        $this->assertArrayHasKey('os_family', $u);
        $this->assertArrayHasKey('os_name', $u);
        $this->assertArrayHasKey('os_url', $u);
        $this->assertArrayHasKey('os_company', $u);
        $this->assertArrayHasKey('os_company_url', $u);
        $this->assertArrayHasKey('os_icon', $u);
    }

    public function testSafari() {
        self::$uasparser->SetCacheDir(self::$cache_path);
        $u = self::$uasparser->Parse('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/536.26.17 (KHTML, like Gecko) Version/6.0.2 Safari/536.26.17');
        $this->assertTrue(is_array($u));
        $this->assertEquals($u['typ'], 'Browser');
        $this->assertEquals($u['ua_family'], 'Safari');
        $this->assertEquals($u['ua_name'], 'Safari 6.0.2');
        $this->assertEquals($u['ua_version'], '6.0.2');
        $this->assertEquals($u['ua_url'], 'http://en.wikipedia.org/wiki/Safari_%28web_browser%29');
        $this->assertEquals($u['ua_company'], 'Apple Inc.');
        $this->assertEquals($u['ua_company_url'], 'http://www.apple.com/');
        $this->assertEquals($u['ua_icon'], 'safari.png');
        $this->assertEquals($u['ua_info_url'], 'http://user-agent-string.info/list-of-ua/browser-detail?browser=Safari');
        $this->assertEquals($u['os_family'], 'OS X');
        $this->assertEquals($u['os_name'], 'OS X 10.8 Mountain Lion');
        $this->assertEquals($u['os_url'], 'http://www.apple.com/osx/');
        $this->assertEquals($u['os_company'], 'Apple Computer, Inc.');
        $this->assertEquals($u['os_company_url'], 'http://www.apple.com/');
        $this->assertEquals($u['os_icon'], 'macosx.png');
    }
}
