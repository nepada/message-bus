<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\Logging;

use Nepada\MessageBus\Logging\PrivateClassPropertiesExtractor;
use NepadaTests\MessageBus\Logging\Fixtures\TestMessageWithPrivateProperties;
use NepadaTests\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
class PrivateClassPropertiesExtractorTest extends TestCase
{

    public function testExtract(): void
    {
        $object = new TestMessageWithPrivateProperties();
        $extractedProperties = (new PrivateClassPropertiesExtractor())->extract($object);

        Assert::equal(
            [
                'privateProperty' => 'foo',
            ],
            $extractedProperties,
        );
    }

}


(new PrivateClassPropertiesExtractorTest())->run();
