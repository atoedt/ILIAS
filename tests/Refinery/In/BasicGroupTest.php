<?php
/* Copyright (c) 1998-2019 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * @author  Niels Theen <ntheen@databay.de>
 */

namespace ILIAS\Refinery\In;

use ILIAS\In\BasicGroup;
use ILIAS\In\Transformation\Parallel;
use ILIAS\In\Transformation\Series;
use ILIAS\Refinery\To\Transformation\IntegerTransformation;
use ILIAS\Refinery\To\Transformation\StringTransformation;

require_once('./libs/composer/vendor/autoload.php');

class BasicGroupTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var BasicGroup
	 */
	private $group;

	public function setUp()
	{
		$this->group = new BasicGroup();
	}

	public function testParallelInstanceCreated()
	{
		$transformation = $this->group->parallel(array(new StringTransformation(), new IntegerTransformation()));
		$this->assertInstanceOf(Parallel::class, $transformation);
	}

	public function testSeriesInstanceCreated()
	{
		$transformation = $this->group->series(array(new StringTransformation(), new IntegerTransformation()));
		$this->assertInstanceOf(Series::class, $transformation);
	}
}
