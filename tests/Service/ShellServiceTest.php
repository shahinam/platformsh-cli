<?php
declare(strict_types=1);

namespace Platformsh\Cli\Tests\Service;

use PHPUnit\Framework\TestCase;
use Platformsh\Cli\Service\Shell;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class ShellServiceTest extends TestCase
{

    /**
     * Test ShellHelper::execute().
     */
    public function testExecute()
    {
        $shell = new Shell(new ConsoleOutput(OutputInterface::VERBOSITY_DEBUG));

        // Find a command that will work on all platforms.
        $workingCommand = strpos(PHP_OS, 'WIN') !== false ? 'help' : 'pwd';

        // Test commandExists().
        $this->assertTrue($shell->commandExists($workingCommand));
        $this->assertFalse($shell->commandExists('nonexistent'));

        // With $mustRun disabled.
        $this->assertNotEmpty($shell->execute([$workingCommand]));
        $this->assertFalse($shell->execute(['which', 'nonexistent']));

        // With $mustRun enabled.
        $this->assertNotEmpty($shell->execute([$workingCommand], null, true));
        $this->expectException('Exception');
        $shell->execute(['which', 'nonexistent'], null, true);
    }
}
