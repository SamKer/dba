<?php
namespace DBA;

interface IPlugin {
    /**
     * Define requiredParams
     * @return array
     */
    public function implementsParams();

    /**
     * check config parameters
     * @param array $config
     * @return false;
     *
     */
    public function checkConfig($config);
}
