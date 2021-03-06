<?php
namespace phpbu\App\Backup;

use SebastianFeldmann\Cli\Command\Result as CommandResult;
use SebastianFeldmann\Cli\Command\Runner\Result as RunnerResult;

/**
 * Cli Test
 *
 * @package    phpbu
 * @subpackage tests
 * @author     Sebastian Feldmann <sebastian@phpbu.de>
 * @copyright  Sebastian Feldmann <sebastian@phpbu.de>
 * @license    https://opensource.org/licenses/MIT The MIT License (MIT)
 * @link       http://www.phpbu.de/
 * @since      Class available since Release 2.1.0
 */
abstract class CliTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Create App\Result mock.
     *
     * @return \phpbu\App\Result
     */
    protected function getAppResultMock()
    {
        return $this->getMockBuilder('\\phpbu\\App\\Result')->disableOriginalConstructor()->getMock();
    }

    /**
     * Create CLI Runner mock.
     *
     * @return \SebastianFeldmann\Cli\Command\Runner
     */
    protected function getRunnerMock()
    {
        return $this->getMockBuilder('\\SebastianFeldmann\\Cli\\Command\\Runner')
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Create runner result mock.
     *
     * @param  int    $code
     * @param  string $cmd
     * @param  string $out
     * @param  string $err
     * @return \SebastianFeldmann\Cli\Command\Runner\Result
     */
    protected function getRunnerResultMock(int $code, string $cmd, string $out = '', string $err = '')
    {
        $cmdRes = new CommandResult($cmd, $code, $out, $err);
        $runRes = new RunnerResult($cmdRes);

        return $runRes;
    }

    /**
     * Create Cli\Result mock.
     *
     * @param  integer $code
     * @param  string  $cmd
     * @param  string  $output
     * @return \SebastianFeldmann\Cli\Command\Result
     */
    protected function getCliResultMock($code, $cmd, $output = '')
    {
        $cliResult = $this->getMockBuilder('\\SebastianFeldmann\\Cli\\Command\\Result')
                          ->disableOriginalConstructor()
                          ->getMock();

        $cliResult->method('getCode')->willReturn($code);
        $cliResult->method('getCmd')->willReturn($cmd);
        $cliResult->method('getStdOut')->willReturn($output);
        $cliResult->method('getStdOutAsArray')->willReturn(explode(PHP_EOL, $output));
        $cliResult->method('isSuccessful')->willReturn($code == 0);

        return $cliResult;
    }

    /**
     * Create Target mock.
     *
     * @param  string $file
     * @param  string $fileCompressed
     * @return \phpbu\App\Backup\Target
     */
    protected function getTargetMock(string $file = '', string $fileCompressed = '')
    {
        $compress = !empty($fileCompressed);
        $pathName = $compress ? $fileCompressed : $file;
        $target = $this->getMockBuilder('\\phpbu\\App\\Backup\\Target')
                       ->disableOriginalConstructor()
                       ->getMock();
        $target->method('getPathnamePlain')->willReturn($file);
        $target->method('getPathname')->willReturn($pathName);
        $target->method('getPath')->willReturn(dirname($pathName));
        $target->method('fileExists')->willReturn(true);
        $target->method('shouldBeCompressed')->willReturn($compress);


        return $target;
    }

    /**
     * Create Compression Mock.
     *
     * @param  string $cmd
     * @param  string $suffix
     * @return \phpbu\App\Backup\Target\Compression
     */
    protected function getCompressionMock($cmd, $suffix)
    {
        $compression = $this->getMockBuilder('\\phpbu\\App\\Backup\\Target\\Compression')
                            ->disableOriginalConstructor()
                            ->getMock();
        $compression->method('isPipeable')->willReturn(in_array($cmd, ['gzip', 'bzip2']));
        $compression->method('getCommand')->willReturn($cmd);
        $compression->method('getSuffix')->willReturn($suffix);
        $compression->method('getPath')->willReturn(PHPBU_TEST_BIN);

        return $compression;
    }
}
