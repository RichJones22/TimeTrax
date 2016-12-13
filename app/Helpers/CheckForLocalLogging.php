<?php

declare(strict_types=1);

namespace app\Helpers;

use Exception;

/**
 * Utility class for determining if APP_LOCAL_LOG is set in the .env file.
 *
 * if APP_LOCAL_LOG=true; we are logging to the Laravel default location; typically, /storage/logs/*.
 *
 * Class CheckForLocalLogging.
 */
class CheckForLocalLogging
{
    /**
     * thrown message.
     */
    const APP_ENV_NOT_FOUND = 'APP_ENV not found in .env file...';
    const ENV_FILE_NOT_FOUND = '.env file not found...';
    /**
     * expected to be in the .env file.
     */
    const APP_ENV = 'APP_ENV';
    /**
     * optional .env variable; when exists and set to true, this will log to the default laravel log.
     * storage/logs/laravel-*.
     */
    const APP_LOCAL_LOG = 'APP_LOCAL_LOG';
    /**
     * Allowed value for APP_ENV variable in the .env file.
     */
    const LOCAL = 'LOCAL';
    /**
     * @var string
     */
    private $environmentFile;
    /**
     * @var array
     */
    private $ini_array;
    /**
     * @var string
     */
    private $environment;
    /**
     * @var bool
     */
    private $isEnvironmentFileValid;
    /**
     * @var bool
     */
    private $logLocal;

    /**
     * CheckForLocalLogging constructor.
     *
     * @param string|null $environmentFile
     *
     * @throws Exception
     */
    public function __construct(string $environmentFile = null)
    {
        // set and validate $environmentFile.
        $environmentFile = $this->validateEnvironmentFile($environmentFile);

        // save $environmentFile.
        $this->setEnvironmentFile($environmentFile);

        // create array of .env file.
        $this->setEnvArray(parse_ini_file($this->getEnvironmentFile()));

        // set environment, ie. local, development, production
        $this->setEnvironment();
    }

    /**
     * @return string
     */
    public function getEnvironmentFile(): string
    {
        return $this->environmentFile;
    }

    /**
     * @param string $environmentFile
     *
     * @return CheckForLocalLogging
     */
    public function setEnvironmentFile(string $environmentFile): CheckForLocalLogging
    {
        $this->environmentFile = $environmentFile;

        return $this;
    }

    /**
     * @return array
     */
    public function getEnvArray(): array
    {
        return $this->ini_array;
    }

    /**
     * @param array $ini_array
     *
     * @return CheckForLocalLogging
     */
    public function setEnvArray(array $ini_array): CheckForLocalLogging
    {
        $this->ini_array = $ini_array;

        return $this;
    }

    /**
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * - verify that self::APP_ENV exists and is set to 'local'
     * - verify that self::APP_LOCAL_LOG exists and is set to 'true'.
     *
     * if above are true then log to laravel's default logging location, using the isLogLocalSet() method.
     *
     * @return CheckForLocalLogging
     *
     * @throws Exception
     */
    public function setEnvironment(): CheckForLocalLogging
    {
        $this->isEnvironmentFileValid = false;
        $this->logLocal = false;

        // validate that APP_ENV exists.
        if (array_key_exists(self::APP_ENV, $this->getEnvArray())) {
            $this->isEnvironmentFileValid = true;
            $this->environment = $this->getEnvArray()[self::APP_ENV];

            // if not the 'local' environment leave...
            if (strtoupper($this->environment) !== self::LOCAL)
            {
                return $this;
            }

            // check if we are logging to laravel storage dir.
            // = APP_LOCAL_LOG exists and is set to true.
            if (array_key_exists(self::APP_LOCAL_LOG, $this->getEnvArray())) {
                if ($this->getEnvArray()[self::APP_LOCAL_LOG] === true) {
                    $this->logLocal = true;
                }
            }

            return $this;
        }

        // early exist if environment file missing self::APP_ENV;
        throw new Exception(self::APP_ENV_NOT_FOUND);
    }

    /**
     * @return bool
     */
    public function isLogLocalSet(): bool
    {
        return $this->logLocal;
    }

    /**
     * @param string $environmentFile
     *
     * @return string
     *
     * @throws Exception
     */
    protected function validateEnvironmentFile(string $environmentFile = null): string
    {
        // if $environmentFile is null, set assumed
        if ($environmentFile === null) {
            $environmentFile = base_path().'/.env';
        }

        // validate that .env file exists.
        if ( ! file_exists($environmentFile)) {
            throw new Exception($this::ENV_FILE_NOT_FOUND);
        }

        return $environmentFile;
    }
}
