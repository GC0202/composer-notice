<?php
/**
 * 第三方通知聚合
 * (c) Chaim <gc@dtapp.net>
 */

namespace Tool\Notice;

/**
 * 倍洽机器人
 * Class BeAryChat
 * @package DtApp\Notice
 */
class BeAryChat extends Base
{
    /**
     * 倍洽自定义机器人接口链接
     * @var string
     */
    private $webhook = '';

    /**
     * 设置配置
     * BeAryChat constructor.
     * @param array $config 配置信息数组
     */
    public function __construct(array $config = [])
    {
        if (!empty($config['webhook'])) $this->webhook = $config['webhook'];
    }

    /**
     * 发送文本消息
     * @param string $content 消息内容
     * @return bool 发送结果
     */
    public function text(string $content = '')
    {
        return $this->sendMsg([
            'text' => $content
        ]);
    }

    /**
     * 组装发送消息
     * @param array $data 消息内容数组
     * @return bool 发送结果
     */
    public function sendMsg(array $data)
    {
        $result = $this->postHttp($this->webhook, $data, true);
        if ($result['code'] !== 0) return true;
        return false;
    }
}
