<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Framework\Console\Command;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\MaintenanceMode;
use Magento\Framework\Module\ModuleList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MaintenanceDisableCommand extends Command
{
    /**
     * @var MaintenanceMode $maintenanceMode
     */
    private $maintenanceMode;

    public function __construct(MaintenanceMode $maintenanceMode)
    {
        $this->maintenanceMode = $maintenanceMode;
        parent::__construct();
    }

    /**
     * Gets input options for the command
     *
     * @return InputOption[]
     */
    public function getOptions()
    {
        return [
            new InputOption(
                'ip',
                null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                'Allowed IP addresses'
            ),
        ];
    }

    /**
     * Initialization of the command
     *
     * @return void
     */
    protected function configure()
    {
        $options = $this->getOptions();
        $this->setName('maintenance:disable')
            ->setDescription('Disable maintenance mode')
            ->setDefinition($options);

        $this->ignoreValidationErrors();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $addresses = $input->getOption('ip');
        $this->maintenanceMode->set(false);
        $output->writeln('<info>Disabled maintenance mode</info>');
        if (!empty($addresses)) {
            $addresses = implode(',', $addresses);
            $addresses = ('none' == $addresses) ? '' : $addresses;
            $this->maintenanceMode->setAddresses($addresses);
            $output->writeln(
                '<info>Set exempt IP-addresses: ' . (implode(', ', $this->maintenanceMode->getAddressInfo()) ?: 'none')
                . '</info>'
            );
        }
    }
}
