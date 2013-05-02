<?php

use	Behat\Behat\Context\BehatContext,
	Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;

class TableContext extends BehatContext
{
	private $mainContext;

	public function __costruct($parameters, $mainContext)
	{ // {{{
		$this->mainContext = $mainContext;

		parent::__construct($params);
	} // }}}

	/**
	 * @Given /^以下の記事が登録されていること$/
	 */
	public function iRegisterTableEntryData(TableNode $table)
	{ // {{{
		$list = $table->getHash();

		foreach ($list as $cols) {
			// register_data($cols);
			print_r($cols);
		}
	} // }}}

	/**
	 * @AfterScenario @tableTestData
	 */
	public function cleanTableTestData()
	{ // {{{
		// clear_test_data();
		echo "テストデータ削除.\n";
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

