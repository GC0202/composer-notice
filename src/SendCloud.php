<?php
/**
 * 第三方通知聚合
 * (c) Chaim <gc@dtapp.net>
 */

namespace tool\Notice;


/**
 * SendCloud
 * Class SendCloud
 * @package DtApp\Notice
 */
class SendCloud extends Base
{
    /**
     * apiUser
     * @var string
     */
    private $api_user = '';

    /**
     * apiKey
     * @var string
     */
    private $api_key = '';

    /**
     * 发件人地址
     * @var string
     */
    private $from = '';

    /**
     * 发件人名称
     * @var string
     */
    private $from_name = '';

    /**
     * 邮件模板调用名称
     * @var string
     */
    private $template = '';

    /**
     * 设置配置
     * SendCloud constructor.
     * @param array $config 配置信息数组
     */
    public function __construct(array $config = [])
    {
        if (!empty($config['api_user'])) $this->api_user = $config['api_user'];
        if (!empty($config['api_key'])) $this->api_key = $config['api_key'];
        if (!empty($config['from'])) $this->from = $config['from'];
        if (!empty($config['from_name'])) $this->from_name = $config['from_name'];
        if (!empty($config['template'])) $this->template = $config['template'];
        parent::__construct($config);
    }

    /**
     * 发送邮箱
     * @param string $email 收件人地址
     * @param string $title 邮箱标题
     * @param string $desc 邮箱描述
     * @param array $content 邮箱内容
     * @return bool
     */
    public function send(string $email, string $title, string $desc, array $content)
    {
        $result = json_decode($this->send_mail($email, $title, $content, $desc), true);
        if ($result['result'] == true) return true;
        return false;
    }

    /**
     * 发送请求
     * @param string $to 地址列表
     * @param string $subject 邮件标题
     * @param string $xsmtpapi SMTP 扩展字段
     * @param string $content_summary 邮件摘要
     * @return false|string
     */
    private function send_mail(string $to, string $subject, string $xsmtpapi, string $content_summary)
    {
        $data = http_build_query([
            'apiUser' => $this->api_user,
            'apiKey' => $this->api_key,
            'from' => $this->from,
            'fromName' => $this->from_name,
            'to' => $to,
            'subject' => $subject,
            'templateInvokeName' => $this->template,
            'contentSummary' => $content_summary,
            'xsmtpapi' => json_encode([
                'to' => [$to],
                'sub' => $xsmtpapi
            ])
        ]);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $data
            ));
        $context = stream_context_create($options);
        $result = file_get_contents($this->sendcloud_url, false, $context);
        return $result;
    }
}
