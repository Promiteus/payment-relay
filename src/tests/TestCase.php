<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Symfony\Component\Console\Output\ConsoleOutput;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    private const CHECK_PREFIX = '+ OK';

    /**
     * @var ConsoleOutput
     */
    private $output;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->output = new ConsoleOutput();
    }

    final public function okMsg(string $reason = ''): void {
        if ($reason === '') {
            $this->console(self::CHECK_PREFIX);
            return;
        }
        $this->console($reason);
        $this->console(self::CHECK_PREFIX);
    }

    final public function console(string $msg): void {
        $this->output->writeln($msg);
    }

    /**
     * @return ConsoleOutput
     */
    public function getOutput(): ConsoleOutput
    {
        return $this->output;
    }


}
