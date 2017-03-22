<?php
/**
 * User: shoal
 * Date: 2017/3/22
 * Time: 9:38
 */

namespace chenqd\mixin;


use Illuminate\Container\Container;

Trait HasMixin
{
    private $_mixin_container=[];
    public function mixinMap()
    {
        return [];
    }

    public function mixinCall($mix_key, $key=null)
    {
        if (is_array($key)) {
            $key = implode('.', $key);
        }

        if (!isset($this->_mixin_container[$mix_key])) {
            $settings = array_get($this->mixinMap(), $mix_key, $mix_key);
            if (is_array($settings)) {
                $class = array_pull($settings, 0);
                $settings['entity'] = $this;
                $params = $settings;
            } else {
                $class = $settings;
                $params = [$this];
            }
            $container = Container::getInstance();
            if (method_exists($container, 'makeWith')) {
                $this->_mixin_container[$mix_key] = $container->makeWith($class, $params);
            }  else {
                $this->_mixin_container[$mix_key] = $container->make($class, $params);
            }
        }

        if (is_null($key)) {
            return $this->_mixin_container[$mix_key];
        }
        return data_get($this->_mixin_container[$mix_key], $key);
    }
}