<?php

namespace app\admin\model;

use app\base\model\BaseModel;

/**
 * 网站配置.
 */
class ConfigModel extends BaseModel
{
    /**
     * 获取信息.
     *
     * @return array 网站配置
     */
    public function getInfo()
    {
        $list = $this->select();
        $config = [];
        foreach ($list as $key => $value) {
            $config[$value['name']] = $value['data'];
        }

        return $config;
    }

    /**
     * 更新信息.
     *
     * @param int $siteId 站点配置ID
     *
     * @return bool 更新状态
     */
    public function saveData()
    {
        $data = request('post.');
        if (empty($data)) {
            $this->error = '数据创建失败！';

            return false;
        }
        foreach ($data as $key => $value) {
            $currentData = [];
            $currentData['data'] = $value;
            $where = [];
            $where['name'] = $key;
            $status = $this->data($currentData)->where($where)->save();
            if ($status === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * 获取当前模板文件.
     *
     * @return array 文件列表
     */
    public function tplList()
    {
        $config = $this->getInfo();
        // 多语言
        if (defined('LANG_OPEN')) {
            $tplDir = ROOT_PATH.THEME_NAME.'/'.$config['tpl_name'].'/'.APP_LANG;
        } else {
            $tplDir = ROOT_PATH.THEME_NAME.'/'.$config['tpl_name'];
        }

        if (!is_dir($tplDir)) {
            return false;
        }
        $listFile = scandir($tplDir);
        if (is_array($listFile)) {
            $list = [];
            foreach ($listFile as $key => $value) {
                if ($value != '.' && $value != '..' && !is_dir($tplDir.DIRECTORY_SEPARATOR.$value)) {
                    $list[$key]['file'] = $value;
                    $list[$key]['name'] = substr($value, 0, -5);
                }
            }
        }

        return $list;
    }

    /**
     * 获取模板路径.
     *
     * @return array 主题列表
     */
    public function themesList()
    {
        $tplDir = ROOT_PATH.THEME_NAME;
        if (!is_dir($tplDir)) {
            return false;
        }
        $listFile = scandir($tplDir);
        if (is_array($listFile)) {
            $list = [];
            foreach ($listFile as $key => $value) {
                if ($value != '.' && $value != '..' && !strpos($value, '.')) {
                    $list[$key]['file'] = $value;
                    $list[$key]['name'] = $value;
                }
            }
        }

        return $list;
    }
}
