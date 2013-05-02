<?php

use Behat\Behat\Context\ClosuredContextInterface,
	Behat\Behat\Context\TranslatedContextInterface,
	Behat\Behat\Context\BehatContext,
	Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
	Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

//
// Require 3rd-party libraries here:
//

//
// Behat setting
//
define('BEHAT_ERROR_REPORTING', E_ERROR | E_WARNING | E_PARSE);

/**
 * Features context.
 */
class FeatureContext extends MinkContext
{
	/**
	 * Initializes context.
	 * Every scenario gets it's own context object.
	 *
	 * @param array $parameters context parameters (set them up through behat.yml)
	 */
	public function __construct(array $parameters)
	{ // {{{
		$this->useContext('table', new TableContext($parameters));
	} // }}}

	/**
	 * @Given /^"([^"]*)" で接続する$/
	 */
	public function iConnectWithSomeTerminal($ua)
	{ // {{{
		$ua_list = array(
			'iPhone5'	=> 'Mozilla/5.0 (iPhone; CPU iPhone OS 5_0_1 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Mobile/9A405 Safari/7534.48.3',
		);


		$this->getSession()
			->getDriver()
			->getClient()
			->setServerParameters(array('HTTP_USER_AGENT' => $ua_list[$ua] ?: $ua));
	} // }}}

	/**
	 * @Then /^JSONの "([^"]*)" が "([^"]*)" であること$/
	 *
	 * @param  string $property_path 検査対象プロパティ
	 *                               "questions.2.title" のように指定
	 * @param  string $expected      期待値
	 */
	public function jsonValueEqual($property_path, $expected)
	{ // {{{
		$actual = $this->getJsonProperty($property_path);

		if ($actual != $expected) {
			throw new Exception("{$expected} expected but was {$actual}");
		}
	} // }}}

	/**
	 * @Then /^JSONの "([^"]*)" に "([^"]*)" が含まれていること$/
	 *
	 * @param  string $property_path 検査対象プロパティ
	 *                               "questions.2.title" のように指定
	 * @param  string $expected      正規表現パターン
	 */
	public function jsonValueMatch($property_path, $expected)
	{ // {{{
		$actual = $this->getJsonProperty($property_path);

		if (!preg_match('/'.$expected.'/', $actual)) {
			throw new Exception("{$actual} did not match {$expected}");
		}
	} // }}}

	/**
	 * @Then /^JSONの "([^"]*)" が (\d+) 件であること$/
	 *
	 * @param  string  $property_path 検査対象プロパティ
	 *                                "questions.2.title" のように指定
	 * @param  integer $expected      対象プロパティが持つ要素数
	 */
	public function jsonCountEqual($property_path, $expected)
	{ // {{{
		$json_property = $this->getJsonProperty($property_path);

		if ($expected == 0 && !$json_property) return true;

		if (!is_array($json_property)) {
			throw new Exception("{$property_path} was not array");
		}

		$actual = count($json_property);

		if ($expected != $actual) {
			throw new Exception("{$expected} expected but was {$actual}");
		}
	} // }}}

	/**
	 * @Then /^JSONの "([^"]*)" リストの "([^"]*)" に "([^"]*)" が含まれていること$/
	 */
	public function jsonListValueMatch($property_path, $target_path, $expected)
	{ // {{{
		$list = $this->getJsonProperty($property_path);

		if (!$list) return true;

		if (!is_array($list)) {
			throw new Exception("{$property_path} was not array");
		}

		$i = 0;
		foreach ($list as $item) {
			$actual = $this->arraySearchByDotJoinedKey($target_path, $item);

			if (!preg_match('/'.$expected.'/', $actual)) {
				throw new Exception("item:{$i} {$actual} did not match {$expected}");
			}

			$i++;
		}
	} // }}}


	/**
	 * hoge.fuga 形式の要素指定で JSON の値を取得する
	 *
	 * @param  string $property_path プロパティ指定
	 *                                "questions.2.title" のように指定
	 * @return mixed  json_decode された対象プロパティ(配列)
	 */
	private function getJsonProperty($property_path)
	{ // {{{
		$contents = $this->getSession()->getPage()->getContent();
		if (!$contents) {
			throw new Exception('response is empty');
		}

		$json = json_decode($contents, true);
		if (!$json) {
			throw new Exception('failed to decode json');
		}

		return $this->arraySearchByDotJoinedKey($property_path, $json);
	} // }}}

	/**
	 * hoge.fuga 形式のキー指定で多次元配列の値を取得する
	 *
	 * @param  string $needle   ドット区切りのキー指定
	 *                          "questions.2.title" のように指定
	 * @param  array  $haystack 対象配列
	 * @return mixed  値
	 */
	private function arraySearchByDotJoinedKey($needle, $haystack)
	{ // {{{
		$result = $haystack;
		$keys = explode('.', $needle);
		$tmp = array();

		foreach ($keys as $key) {
			$tmp[] = $key;
			if (!array_key_exists($key, $result)) {
				throw new Exception("property '". implode('.', $tmp). "' was not found");
			}
			$result = $result[$key];
		}

		return $result;
	} // }}}

}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: noet sw=4 ts=4 sts=4 fdm=marker
 * vim<600: noet sw=4 ts=4 sts=4
 */
