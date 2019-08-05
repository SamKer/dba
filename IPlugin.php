<?php
namespace DB2S3;

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
