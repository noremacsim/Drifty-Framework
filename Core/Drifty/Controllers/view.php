<?php
/*
 * Drifty FrameWork by noremacsim(Cameron Sim)
 *
 * This File has been created by noremacsim(Cameron Sim) under the Drifty FrameWork
 * And will follow all the Drifty FrameWork Licence Terms which can be found under Licence
 *
 * @author     Cameron Sim <mrcameronsim@gmail.com>
 * @author     noremacsim <noremacsim@github>
 */

namespace Drifty\Controllers;

class view
{

    /**
     * @var array
     */
    public $blocks = array();

    /**
     * @var array|false|string
     */
    public $cachePath = 'cache/';

    /**
     * @var array|bool|string
     */
    public $cacheEnabled = false;

    /**
     * @var array
     */
    public $globals = array();

    /**
     * @var array|false|string
     */
    public $tplDirectory = '';


    public function __construct()
    {
        global $driftyApp;
        $this->addGlobal('page', $driftyApp->page);
        $this->cachePath = getenv('CACHE_DIR');
        $this->cacheEnabled = getenv('CACHE_ENABLED');
        $this->tplDirectory = getenv('TPL_DIR');
    }

    /**
     * @param $name
     * @param $data
     */
    public function addGlobal($name, $data)
    {
        $this->globals[$name] = $data;
    }

    /**
     * @param $file
     * @param array $data
     */
    public function render($file, $data = array())
    {
        $data = array_merge($data, $this->globals);
        $cachedFile = $this->cache($this->tplDirectory. $file);
        extract($data, EXTR_SKIP);
        require $cachedFile;
    }

    /**
     * @param $file
     * @return string
     */
    public function cache($file)
    {
        if (!file_exists($this->cachePath)) {
            mkdir($this->cachePath, 0744);
        }
        $cachedFile = $this->cachePath . str_replace(array('/', '.tpl'), array('_', ''), $file . '.php');
        if (!$this->cacheEnabled || !file_exists($cachedFile) || filemtime($cachedFile) < filemtime($file)) {
            $code = $this->includeFiles($file);
            $code = $this->compileCode($code);
            file_put_contents($cachedFile, '<?php class_exists(\'' . __CLASS__ . '\') or exit; ?>' . PHP_EOL . $code);
        }
        return $cachedFile;
    }

    public function clearCache()
    {
        foreach (glob($this->cache_path . '*') as $file) {
            unlink($file);
        }
    }

    /**
     * @param $code
     * @return array|string|string[]|null
     */
    public function compileCode($code)
    {
        $code = $this->compileBlock($code);
        $code = $this->compileSection($code);
        $code = $this->compileEscapedEchos($code);
        $code = $this->compileEchos($code);
        $code = $this->compilePHP($code);
        return $code;
    }

    /**
     * @param $file
     * @return array|string|string[]|null
     */
    public function includeFiles($file)
    {
        $code = file_get_contents($file);
        preg_match_all('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            $code = str_replace($value[0], $this->includeFiles($this->tplDirectory . $value[2]), $code);
        }
        $code = preg_replace('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', '', $code);
        return $code;
    }

    /**
     * @param $code
     * @return array|string|string[]|null
     */
    public function compilePHP($code)
    {
        return preg_replace('~\{%\s*(.+?)\s*\%}~is', '<?php $1 ?>', $code);
    }

    /**
     * @param $code
     * @return array|string|string[]|null
     */
    public function compileEchos($code)
    {
        return preg_replace('~\{{\s*(.+?)\s*\}}~is', '<?php echo $1 ?>', $code);
    }

    /**
     * @param $code
     * @return array|string|string[]|null
     */
    public function compileEscapedEchos($code)
    {
        return preg_replace('~\{{{\s*(.+?)\s*\}}}~is', '<?php echo htmlentities($1, ENT_QUOTES, \'UTF-8\') ?>', $code);
    }

    /**
     * @param $code
     * @return array|mixed|string|string[]
     */
    public function compileBlock($code)
    {
        preg_match_all('/{% ?block ?(.*?) ?%}(.*?){% ?endblock ?%}/is', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            if (!array_key_exists($value[1], $this->blocks)) $this->blocks[$value[1]] = '';
            if (strpos($value[2], '@parent') === false) {
                $this->blocks[$value[1]] = $value[2];
            } else {
                $this->blocks[$value[1]] = str_replace('@parent', $this->blocks[$value[1]], $value[2]);
            }
            $code = str_replace($value[0], '', $code);
        }
        return $code;
    }

    /**
     * @param $code
     * @return array|string|string[]|null
     */
    public function compileSection($code)
    {
        foreach ($this->blocks as $block => $value) {
            $code = preg_replace('/{% ?section ?' . $block . ' ?%}/', $value, $code);
        }
        $code = preg_replace('/{% ?section ?(.*?) ?%}/i', '', $code);
        return $code;
    }
}
