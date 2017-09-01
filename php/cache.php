<?php

class SimpleCache {
    // Path to cache folder (with trailing /)
    public $cache_path = 'cache/';
    // Length of time to cache a file (in seconds)
    public $cache_time = 3600;
    // Cache file extension
    public $cache_extension = '.cache';
    // This is just a functionality wrapper function
    public function get_data($label, $url)
    {
        if($data = $this->get_cache($label)){
            return $data;
        } else {
            $data = $this->do_curl($url);
            $this->set_cache($label, $data);
            return $data;
        }
    }
    public function set_cache($label, $data)
    {
        file_put_contents($this->cache_path . $this->safe_filename($label) . $this->cache_extension, serialize($data));
    }
    public function get_cache($label)
    {
        if($this->is_cached($label)){
            $filename = $this->cache_path . $this->safe_filename($label) . $this->cache_extension;
            return file_get_contents(unserialize($filename));
        }
        return false;
    }
    public function is_cached($label)
    {
        $filename = $this->cache_path . $this->safe_filename($label) . $this->cache_extension;
        if(file_exists($filename) && (filemtime($filename) + $this->cache_time >= time())) return true;
        return false;
    }


    private function safe_filename($filename)
    {
        $r = preg_replace('/[^0-9a-z\.\_\-]/i','', strtolower($filename));
        return $r;
    }
}