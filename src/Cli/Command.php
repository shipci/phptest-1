<?php
/*
 * This file is part of PHPTest
 *
 * Copyright (c) 2014 Andrew Lawson <http://adlawson.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PhpTest\Cli;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends BaseCommand
{
    /**
     * @var ControllerInterface[]
     */
    protected $controllers = [];

    /**
     * @param string $name
     * @param ControllerInterface[] $controllers
     */
    public function __construct($name, array $controllers)
    {
        foreach ($controllers as $controller) {
            $this->addController($controller);
        }

        parent::__construct($name);
    }

    /**
     * @param ControllerInterface $controller
     */
    public function addController(ControllerInterface $controller)
    {
        $this->controllers[] = $controller;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        foreach ($this->controllers as $controller) {
            $controller->configure($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->controllers as $controller) {
            $code = (integer) $controller->execute($input, $output);

            if (0 !== $code) {
                return $code;
            }
        }

        return 0;
    }
}
