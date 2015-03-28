<?php
/** {license_text}  */
namespace Layout;

use Carbon\Carbon;
use Core\Support\DebugTrait;
use Illuminate\Cache\CacheManager;
use Symfony\Component\Yaml\Parser as Parser;

/** {license_text}  */
class Config
    implements ConfigInterface
{
    use DebugTrait;

    const ACTION_EXTEND  = '%extend';
    const ACTION_REWRITE = '%rewrite';
    const ACTION_REMOVE  = '%remove';

    protected $actions = array(self::ACTION_REWRITE, self::ACTION_REMOVE, self::ACTION_EXTEND);

    protected $systemNodes  = array();

    /** @var  Parser */
    protected $parser;
    protected $schema;

    protected $configPath   = array();
    protected $scopeData   = array();
    protected $data         = array();
    protected $handles      = array();

    protected $cacheEnabled = true;
    /** @var  CacheStore */
    protected $cache;
    protected $cachePrefix = 'layout::';
    protected $cacheExpiresAt;

    /**
     * @param Parser $parser
     * @param CacheManager $cache
     * @param Carbon $carbon
     */
    public function __construct(Parser $parser, CacheManager $cache, Carbon $carbon)
    {
        $this->parser         = $parser;
        $this->cache          = $cache;
        $this->cacheExpiresAt = $carbon->now()->addMinutes(60);
    }

    /**
     * @param $value
     */
    public function setCacheExpiresAt($value)
    {
        $this->cacheExpiresAt = $value;
    }

    /**
     * @param bool $flag
     */
    public function setCacheEnabled($flag = true)
    {
        $this->cacheEnabled = $flag;
    }

    /**
     * @param $key
     * @param $value
     */
    protected function addCache($key, $value)
    {
        if ($this->cacheEnabled) {
            $this->cache->put($this->cachePrefix . $key, $value, $this->cacheExpiresAt);
        }
    }

    /**
     * @param $key
     * @return bool|mixed
     */
    protected function getCache($key)
    {
        if ($this->cacheEnabled) {
            return $this->cache->get($this->cachePrefix . $key, false);
        }

        return false;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function addConfigPath($path)
    {
        if (is_dir($path)) {
            array_unshift($this->configPath, $path);
        }

        return $this;
    }

    /**
     * @param array $target
     * @param array $data
     * @return array
     */
    protected function arrayMerge(array $target, array $data)
    {
        foreach ($data as $key => $value) {
            if (isset($target[$key])) {
                if (in_array($key, $this->actions)) {
                    $target[$key] = array_merge((array) $target[$key], (array) $value);
                } else if (is_array($target[$key]) || is_array($value)) {
                    $target[$key] = $this->arrayMerge((array) $target[$key], (array) $value);
                } else {
                    $target[$key] = $value;
                }
            } else {
                $target[$key] = $value;
            }
        }

        return $target;
    }

    /**
     * Unset removed nodes
     */
    protected function applyRemoves()
    {
        foreach ($this->scopeData as $key => $value) {
            if (self::ACTION_REMOVE == $key) {
                foreach((array)$value as $path) {
                    $path = explode('/', $path);
                    $config = &$this->scopeData;
                    while ($name = array_shift($path)) {
                        if (empty($path)) {
                            unset($config[$name]);
                            continue 2;
                        } else {
                            if (isset($config[$name])) {
                                $config = &$config[$name];
                            } else {
                                continue 2;
                            }
                        }
                    }
                }
                unset($this->scopeData[$key]);
            }
        }
    }

    /**
     * Rewrite value by path
     */
    protected function applyExtends()
    {
        foreach ($this->scopeData as $key => $value) {
            if (self::ACTION_EXTEND == $key) {
                foreach((array) $value as $path => $data) {
                    $path = explode('/', $path);
                    $config = &$this->scopeData;
                    while ($name = array_shift($path)) {
                        if (empty($config[$name])) {
                            break;
                        }
                        if (empty($path)) {
                            $config[$name] = $this->arrayMerge($config[$name], $data);
                            break;
                        } else {
                            if (isset($config[$name])) {
                                $config = &$config[$name];
                            } else {
                                break;
                            }
                        }
                    }
                }
                unset($this->scopeData[$key]);
            }
        }
    }

    /**
     * Rewrite value by path
     */
    protected function applyRewrites()
    {
        foreach ($this->scopeData as $key => $value) {
            if (self::ACTION_REWRITE == $key) {
                foreach((array) $value as $path => $data) {
                    $path = explode('/', $path);
                    $config = &$this->scopeData;
                    while ($name = array_shift($path)) {
                        if (empty($config[$name])) {
                            break;
                        }
                        if (empty($path)) {
                            $config[$name] = $data;
                            break;
                        } else {
                            if (isset($config[$name])) {
                                $config = &$config[$name];
                            } else {
                                break;
                            }
                        }
                    }
                }
                unset($this->scopeData[$key]);
            }
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getHandles()
    {
        return $this->handles;
    }

    /**
     * @param $handle
     */
    public function addHandle($handle)
    {
        $this->handles[] = $handle;
    }

    /**
     * @param array $handles
     */
    public function setHandles(array $handles)
    {
        $this->handles = $handles;
    }

    /**
     * @param array $handles
     * @param bool $includeDefaultHandle
     * @return $this
     */
    public function load($handles = [], $includeDefaultHandle = true)
    {
        $this->startDebugMeasure('layout_config_load', 'Loading Layout Configuration');

        if ($handles && !is_array($handles)) {
            $handles = [$handles];
        }
        if ($includeDefaultHandle) {
            $handles[] = 'default';
        }

        $handles = array_unique($handles);

        sort($handles);
        
        $cacheKey = 'config::' . implode('|', $handles);

        $this->scopeData = $this->getCache($cacheKey);

        if (!$this->scopeData) {
            $data = $this->getCache('config');
            if (!$data) {
                $data        = [];
                $loadedFiles = [];
                foreach ($this->configPath as $path) {
                    foreach (scandir($path) as $fileName) {
                        $filePath = "{$path}/{$fileName}";
                        if (is_file($filePath)) {
                            if (!in_array($fileName, $loadedFiles)) {
                                $data          = $this->arrayMerge($data, (array)$this->parser->parse(file_get_contents($filePath)));
                                $loadedFiles[] = $fileName;
                            }
                        }
                    }
                }
                $this->addCache('config', $data);
            }

            foreach ($data as $handle => $handleData) {
                if (in_array($handle, $handles)) {
                    if (empty($this->scopeData)) {
                        $this->scopeData = $handleData;
                    } else {
                        $this->scopeData = $this->arrayMerge($this->scopeData, $handleData);
                    }
                }
            }

            $this->applyRewrites();
            $this->applyExtends();
            $this->applyRemoves();

            $this->scopeData = array_shift($this->scopeData);
            $this->addCache($cacheKey, $this->scopeData);
        }

        $this->data = $this->scopeData;

        $this->stopDebugMeasure('layout_config_load');

        return $this;
    }

    /**
     * @param string $target
     */
    public function setTarget($target)
    {
        if ($target) {
            $path = explode('/', $target);
            if (!empty($path)) {
                $data = $this->scopeData;
                while ($elementName = array_shift($path)) {
                    if (isset($data[$elementName])) {
                        $data = $data[$elementName];
                    } else {
                        $data = null;
                        break;
                    }

                }
                $this->data = $data;
            }
        }
    }
}
