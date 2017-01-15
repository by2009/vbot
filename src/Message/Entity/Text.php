<?php
/**
 * Created by PhpStorm.
 * User: Hanson
 * Date: 2016/12/16
 * Time: 18:33
 */

namespace Hanson\Robot\Message\Entity;


use Hanson\Robot\Message\MessageInterface;
use Hanson\Robot\Support\Console;

class Text extends Message implements MessageInterface
{

    public function __construct($msg)
    {
        parent::__construct($msg);

        $this->make();
    }

    /**
     * 发送消息
     *
     * @param $word string 消息内容
     * @param $username string 目标username
     * @return bool
     */
    public static function send($username, string $word)
    {
        if(!$word){
            return false;
        }

        $random = strval(time() * 1000) . '0' . strval(rand(100, 999));

        $data = [
            'BaseRequest' => server()->baseRequest,
            'Msg' => [
                'Type' => 1,
                'Content' => $word,
                'FromUserName' => myself()->username,
                'ToUserName' => $username,
                'LocalID' => $random,
                'ClientMsgId' => $random,
            ],
            'Scene' => 0
        ];
        $result = http()->post(server()->baseUri . '/webwxsendmsg?pass_ticket=' . server()->passTicket,
            json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES), true
        );

        if($result['BaseResponse']['Ret'] != 0){
            Console::log('发送消息失败');
            return false;
        }

        return true;
    }

    public function make()
    {
        $this->content = $this->msg['Content'];
    }
}