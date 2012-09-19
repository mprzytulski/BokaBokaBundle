<?php

namespace Aurora\BokaBokaBundle\Messaging\RabbitMQ\Message;

/**
 * @Annotation
 */
class Attributes extends \SplEnum implements \JsonSerializable
{

    protected $attrs;

//    const __default = self::CONTENT_TYPE;

    const CONTENT_TYPE     = 'content_type';
    const CONTENT_ENCODING = 'content_encoding';
    const MESSAGE_ID       = 'message_id';
    const USER_ID          = 'user_id';
    const APP_ID           = 'app_id';
    const DELIVERY_MODE    = 'delivery_mode';
    const PRIORITY         = 'priority';
    const TIMESTAMP        = 'timestamp';
    const EXPIRATION       = 'expiration';
    const TYPE             = 'type';
    const REPLY_TO         = 'reply_to';

    public function __construct(array $attrs = null)
    {
        $allowed = $this->getConstList();
        $not_allowed = array_diff($attrs, $allowed);

        if(count($not_allowed) > 0) {
            throw new \InvalidArgumentException("Not allowed attrs: ".implode(', ', $not_allowed));
        }

        $this->attrs = $attrs;
    }

    protected function getAttr($name, $default = null)
    {
        return isset($this->atts[$name]) ? $this->atts[$name] : $default;
    }

    protected function setAttr($name, $value)
    {
        $this->attrs[$name] = $value;
    }

    public function setContentType($type)
    {
        $this->setAttr('content_type', $type);
    }

    public function getContentType()
    {
        $this->getAttr('content_type');
    }

    public function setContentEncoding($encoding)
    {
        $this->setAttr('content_encoding', $encoding);
    }

    public function getContentEncoding()
    {
        $this->getAttr('content_encoding');
    }

    public function setMessageId($id)
    {
        $this->setAttr('message_id', $id);
    }

    public function getMessageId()
    {
        $this->getAttr('message_id');
    }

    public function setUserId($id)
    {
        $this->setAttr('user_id', $id);
    }

    public function getUserId()
    {
        $this->getAttr('user_id');
    }

    public function setAppId($id)
    {
        $this->setAttr('app_id', $id);
    }

    public function getAppId()
    {
        $this->getAttr('app_id');
    }

    public function setDeliveryMode($mode)
    {
        $this->setAttr('delivery_mode', $mode);
    }

    public function getDeliveryMode()
    {
        $this->getAttr('delivery_mode');
    }

    public function setPriority($priority)
    {
        $this->setAttr('priority', $priority);
    }

    public function getPriority()
    {
        $this->getAttr('priority');
    }

    public function setTimestamp($timestamp)
    {
        $this->setAttr('timestamp', $timestamp);
    }

    public function getTimestamp()
    {
        $this->getAttr('timestamp');
    }

    public function setExpiration($expiration)
    {
        $this->setAttr('expiration', $expiration);
    }

    public function getExpiration()
    {
        $this->getAttr('expiration');
    }

    public function setType($type)
    {
        $this->setAttr('type', $type);
    }

    public function getType()
    {
        $this->getAttr('type');
    }

    public function setReplyTo($reply_to)
    {
        $this->setAttr('reply_to', $reply_to);
    }

    public function getReplyTo()
    {
        $this->getAttr('reply_to');
    }

    public function jsonSerialize()
    {
        return $this->attrs;
    }

    public function __toString()
    {
        return json_encode($this->attrs);
    }

}