<?php

namespace Hailong;

use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\DBAL\Connections\MasterSlaveConnection;

/**
 * Add common enhancements to the Doctrine default MasterSlaveConnection implementation.
 */
class EnhancedMasterSlaveConnection extends MasterSlaveConnection
{
    /**
     * {@inheritdoc}
     */
    protected function chooseConnectionConfiguration($connectionName, $params)
    {
        if ($connectionName === 'master') {
            return $params['master'];
        }

        $slaveKeys = array_keys($params['slaves']);
        $index = random_int(0, count($slaveKeys) - 1);
        $config = $params['slaves'][$slaveKeys[$index]];

        if (! isset($config['charset']) && isset($params['master']['charset'])) {
            $config['charset'] = $params['master']['charset'];
        }

        return $config;
    }

    /**
     * {@inheritdoc}
     */
    public function executeQuery($query, array $params = [], $types = [], ?QueryCacheProfile $qcp = null)
    {
        // force the SELECT queries to slave database
        if (stripos($query, 'SELECT') === 0) {
            $this->connect('slave');
        }

        return parent::executeQuery($query, $params, $types, $qcp);
    }
}
