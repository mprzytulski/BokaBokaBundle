<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 09.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\Annotations;

class MessageAttribute
{

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

}