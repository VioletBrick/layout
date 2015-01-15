<?php
/** {license_text}  */

namespace Layout;

use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Fluent;

/** {license_text}  */
class Config
{
    protected $configFiles = array();
    protected $loadPaths = array();
    protected $data = array();

    /**
     * @param string $path
     * @return $this
     */
    public function addLoadPath($path)
    {
        array_unshift($this->loadPaths, $path);
        
        return $this;
    }

    /**
     * @param string $fileName
     * @return bool|string
     */
    public function resolvePath($fileName)
    {
        foreach ($this->loadPaths as $path)
        {
            $filePath = "{$path}/{$fileName}";
            if (is_file($filePath) && is_readable($filePath)) {
                return $filePath;
            }
        }

        return false;
    }
    
    protected function loadFile($fileName)
    {
        if ($filePath = $this->resolvePath($fileName)) {
            
        }
    }
    
    public function load()
    {
        
    }
}
