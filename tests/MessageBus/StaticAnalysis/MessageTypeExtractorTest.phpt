<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis;

use Nepada\MessageBus\StaticAnalysis\HandlerType;
use Nepada\MessageBus\StaticAnalysis\MessageTypeExtractor;
use NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\ValidCommand;
use NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\ValidHandler;
use NepadaTests\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
class MessageTypeExtractorTest extends TestCase
{

    public function testExtract(): void
    {
        $extractor = new MessageTypeExtractor();

        Assert::same(ValidCommand::class, $extractor->extract(HandlerType::fromString(ValidHandler::class))->toString());
    }

}


(new MessageTypeExtractorTest())->run();
